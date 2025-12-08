<?php

namespace App\Services;

use App\Enums\EntityType;
use App\Jobs\SyncEntityPageJob;
use App\Models\Account;
use App\Models\AccountToken;
use App\Services\Api\ApiClientService;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncService
{
    private ?OutputStyle $output = null;

    public function __construct(
        private ApiClientService $apiClient,
        private SyncLogService $SyncLogService
    ) 
    {}

    /**
     * 
     *  @return void
     * Устанавливает output для вывода в консоль
     */
    public function setOutput(OutputStyle $output): void
    {
        $this->output = $output;
    }

    /**
     * Вывод сообщения в консоль (если есть output)
     * 
     *  @return void
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
     * 
     * @return void
     */
    public function syncAll(): void
    {
        $accounts = Account::with('token.tokenType.apiServices')->whereHas('token')->get();
        
        $this->info("Найдено аккаунтов: " . $accounts->count());

        foreach ($accounts as $account) {
            $this->info("Обработка аккаунта {$account->id}");

            $generalParams = $this->getParams($account->token);
            $stockParams = $this->getParamsForStock();

            foreach (EntityType::cases() as $entityType) {
                $this->info("Тип сущности: {$entityType->name} ({$entityType->endpoint()})");

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
     * Выполнить вставку или обновление данных при дубликатах
     * 
     * @param array $rows
     * 
     * @return void
     */
    public function upsertRows(EntityType $entityType,array $rows): void
    {
        $modelClass = $entityType->modelClass();
        
        $modelClass = $entityType->modelClass();
        $uniqueKeys = $modelClass::getUniqueKey();
        $updateColumns = $this->getUpdateColumns($rows, $uniqueKeys);

        try {
            $affected = DB::transaction(function () use ($modelClass, $rows, $uniqueKeys, $updateColumns) {
                return $modelClass::upsert($rows, $uniqueKeys, $updateColumns);
            }, 3);
        } catch (\Exception $e) {
            Log::error("Upsert failed", [
                'entity' => $entityType->value,
                'error' => $e->getMessage(),
                'unique_keys' => $uniqueKeys,
            ]);
            throw $e;
        }

        $this->info("При добавлении в БД затронуто affected записей - {$affected}");
    }
    
    /**
     * Получить колонки для обновления
     * 
     * @param mixed $model
     * @param array $rows
     * 
     * @return array
     */
    private function getUpdateColumns($model, array $rows): array
    {
        //обновляем все, кроме уникальных ключей
        $allColumns = array_keys($rows[0] ?? []);
        $uniqueKeys = $model->getUniqueKey();
        
        return array_values(array_diff($allColumns, $uniqueKeys));
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
            'dateTo' => now()->addDay()->format('Y-m-d'), // плюс день потому что выборка у апи со строгим условием (как минимум у orders)
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
}