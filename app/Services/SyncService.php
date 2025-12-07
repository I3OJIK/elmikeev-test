<?php

namespace App\Services;

use App\Enums\EntityType;
use App\Jobs\SyncEntityPageJob;
use App\Models\Account;
use App\Models\AccountToken;
use App\Services\Api\ApiClientService;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Log;

class SyncService
{
    private ?OutputStyle $output = null;

    public function __construct(
        private ApiClientService $apiClient,
        private SyncLogService $SyncLogService
    ) {}

    /**
     * Устанавливает output для вывода в консоль
     */
    public function setOutput(OutputStyle $output): void
    {
        $this->output = $output;
    }

    /**
     * Вывод сообщения в консоль (если есть output)
     */
    private function info(string $message): void
    {
        if ($this->output) {
            $this->output->info($message);
        }
        Log::info($message);
    }
    /**
     * Синхронизация всех аккаунтов и всех токенов.
     */
    public function syncAll(): void
    {
        $accounts = Account::with('token.tokenType.apiServices')->get();
        
        $this->info("Найдено аккаунтов: " . $accounts->count());

        foreach ($accounts as $account) {
             // Проверяем есть ли токен у аккаунта
            if (!$account->token) {
                $this->info("У аккаунта {$account->id} нет токена, пропускаем");
                continue;
            }

            $this->info("Обработка аккаунта {$account->id}");

            $generalParams = $this->getParams($account->token);
            $stockParams = $this->getParamsForStock();

            foreach (EntityType::cases() as $entityType) {

                $this->info("Тип сущности: {$entityType->name} ({$entityType->endpoint()})");

                // если синхронизация не первая, то удаляем данные за день последней синхронизации для избежания дублей
                $this->prepareDatabaseForSync($account->token, $entityType);
                // Выбираем параметры в зависимости от типа
                $params = ($entityType === EntityType::STOCKS) 
                    ? $stockParams 
                    : $generalParams;
                
                $this->info("Параметры: " . json_encode($params));

                $this->dispatchJobsForToken($account->token, $entityType, $params);
            }
        }
        $this->info("Все задачи синхронизации поставлены в очередь!");
    }

    /**
     * Генерация джоб для постраничной синхронизации.
     */
    private function dispatchJobsForToken(
        AccountToken $token,
        EntityType $entityType,
        array $params
    ): void 
    {
        $lastPage = $this->apiClient->getLastPage($token, $entityType, $params);

        if ($lastPage === null) {
            Log::warning("Пропускаем {$entityType->endpoint()} для токена {$token->id}, не удалось получить last_page");
            return;
        };

        if ($lastPage === 0) {
            Log::warning("Новых данных нет");
            return;
        };

        $this->info("Страниц колво : {$lastPage} у entity - {$entityType->endpoint()}");
        // Создаём джобы для всех страниц
        for ($page = 1; $page <= $lastPage; $page++) {
            SyncEntityPageJob::dispatch(
                token: $token,
                entityType: $entityType,
                page: $page,
                params: $params
            )->onQueue('sync');
        }
    }

    /**
     * Формирует параметры запроса(даты) 
     * 
     * @param EntityType $entityType
     * @param array $params
     * 
     * @return array
     */
    private function getParams(AccountToken $token): array
    {
        return [
            'dateFrom' => $this->SyncLogService->getLastSyncDate($token->account_id, $token->api_service_id) ?? '0001-01-01',
            'dateTo' => now()->addDay()->format('Y-m-d'), // плюс день потому что выборка у апи со строгим условием
        ];
    }

    /**
     * Формирует параметры для entity - stock
     * 
     * @return array
     */
    private function getParamsForStock(): array
    {
        return [
            'dateFrom' => now()->format('Y-m-d')
        ];
    }

    /**
     * Подготовка БД перед синхронизацией
     */
    private function prepareDatabaseForSync(
        AccountToken $token, 
        EntityType $entityType, 
    ): void 
    {
        $modelClass = $entityType->modelClass();
        
        // Для stocks полная очистка
        if ($entityType === EntityType::STOCKS) {
            $modelClass::where('account_id', $token->account_id)
                        ->delete();
            
            $this->info("Очищены все данные stocks для account {$token->account_id}");
            return;
        }
        
        // Для остальных, если синхрониазция не первая  - очищаем данные за дату начала синхронизации
        // чтобы избежать дублей
        $lastSyncDate = $this->SyncLogService->getLastSyncDate($token->account_id, $token->api_service_id);
        
        if ($lastSyncDate) {
            $deleteCount = $modelClass::where('account_id', $token->account_id)
                                     ->whereDate('date', '=', $lastSyncDate)
                                     ->delete();
            
            $this->info("Очищено {$deleteCount} записей за {$lastSyncDate} для {$entityType->value}");
            return;
        }
    }
}