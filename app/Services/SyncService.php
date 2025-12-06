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
        $this->info("Начинаем синхронизацию всех данных...");
        $accounts = Account::with('token.tokenType.apiServices')->get();
        $this->info("Найдено аккаунтов: " . $accounts->count());
        foreach ($accounts as $account) {
            $this->info("Обработка аккаунта {$account->id}");
            foreach (EntityType::cases() as $entityType) {
                $this->info("Тип сущности: {$entityType->name} ({$entityType->endpoint()})");
                $params = $this->getParamsForType($entityType, $account->token);
                
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
     * Формирует параметры запроса(даты) для указанного типа данных
     * 
     * @param EntityType $entityType
     * @param AccountToken $token
     * @param array $params
     * 
     * @return array
     */
    private function getParamsForType(EntityType $entityType, AccountToken $token): array
    {
        
        // Для складов только текущий день
        if ($entityType === EntityType::STOCKS) {
            return [
                'dateFrom' => now()->format('Y-m-d')
            ];
        }

        // Для остальных - переданные параметры или по умолчанию
        return [
            'dateFrom' => $this->SyncLogService->getLastSyncDate($token->account_id, $token->api_service_id) ?? '0001-01-01',
            'dateTo' => now()->format('Y-m-d'),
        ];
    }
}