<?php

namespace App\Jobs;

use App\Enums\EntityType;
use App\Models\AccountToken;
use App\Services\Api\ApiClientService;
use App\Services\SyncLogService;
use App\Services\SyncService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncEntityPageJob implements ShouldQueue
{
    use Queueable;

    public $tries = 15;
    public $timeout = 30;


    public function __construct(
        private AccountToken $token,
        private EntityType $entityType,
        private int $page,
        private array $params,
    )
    {}

    public function handle(ApiClientService $apiClient, SyncLogService $syncLog, SyncService $syncService): void
    {
        $this->params['page'] = $this->page;

        Try {
            $response = $apiClient->fetchPage($this->token, $this->entityType, $this->params);
            
            $data = $response->json();
            $rows = $data['data'];

            // Добавляем account_id каждой записи
            foreach ($rows as &$row) {
                $row['account_id'] = $this->token->account_id;
            }
            
            if (!empty($rows)) {
                $syncService->upsertRows($this->entityType, $rows);
                Log::info("Cтраница {$this->page} сохранена, status api - {$response->status()}");
            }
            // Обновляем лог последней синхронизации после успешной вставки последней страницы
            $lastPage = $data['meta']['last_page'] ?? $this->page;
            if ($this->page >= $lastPage) {
                $syncLog->markSynced($this->token->account_id, $this->token->api_service_id);
            }
        } catch (Exception $e) {
            Log::error("Ошибка при синхронизации {$this->entityType->value}, страница {$this->page}: " . $e->getMessage());
            throw $e; 
        }
    }
}
    
