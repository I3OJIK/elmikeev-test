<?php

namespace App\Jobs;

use App\Enums\EntityType;
use App\Models\AccountToken;
use App\Services\Api\ApiClientService;
use App\Services\SyncLogService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncEntityPageJob implements ShouldQueue
{
    use Queueable;

    public $tries = 15;


    public function __construct(
        private AccountToken $token,
        private EntityType $entityType,
        private int $page,
        private array $params,
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(ApiClientService $apiClient, SyncLogService $syncLog): void
    {
        $this->params['page'] = $this->page;

        Try {
            Log::info("Запуск джобы {$this->entityType->value} страница {$this->page}, токен {$this->token->id}");
            // если синхронизация stocks - то сначала чистим бд
            if ($this->entityType === EntityType::STOCKS) {
                $modelClass = $this->entityType->modelClass();
                $modelClass::where('account_id', $this->token->account_id)
                           ->delete();
            }

            $response = $apiClient->fetchPage($this->token, $this->entityType, $this->params);
            // Обработка 429
            if ($response->status() === 429) {
                $retryAfter = (int) ($response->header('Retry-After') ?? 5);
                Log::warning("429 Too Many Requests, повтор через {$retryAfter} сек.");
                sleep($retryAfter);
                throw new Exception('429 Too Many Requests');
            }

            $data = $response->json();
            $rows = $data['data'];

            // Добавляем account_id каждой записи
            foreach ($rows as &$row) {
                $row['account_id'] = $this->token->account_id;
            }

            if (!empty($rows)) {
                $modelClass = $this->entityType->modelClass();
                $modelClass::insert($rows); // теперь точно массив массивов
                Log::info("Сохранено " . count($rows) . " записей для {$this->entityType->value}, страница {$this->page}, status - {$response->status()}, {$response->header('X-Ratelimit-Remaining')} ");
            }

            // Обновляем лог последней синхронизации после успешной вставки последней страницы
            $lastPage = $response['meta']['last_page'] ?? $this->page;
            if ($this->page >= $lastPage) {
                $syncLog->markSynced($this->token->account_id, $this->token->api_service_id);
            }
        } catch (Exception $e) {
            Log::error("Ошибка при синхронизации {$this->entityType->value}, страница {$this->page}: " . $e->getMessage());
            throw $e; 
        }
    }
}
