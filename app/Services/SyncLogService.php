<?php

namespace App\Services;

use App\Models\SyncLog;

class SyncLogService
{
    /**
     * Получение даты последней синхронизации сервиса у аккаунта
     * 
     * @param int $accountId
     * @param int $apiServiceId
     * 
     * @return string|null
     */
    public function getLastSyncDate(int $accountId, int $apiServiceId): ?string
    {
        $log = SyncLog::where('account_id', $accountId)
                      ->where('api_service_id', $apiServiceId)
                      ->first();

        if ($log === null) {
            return null;
        }

        if ($log->last_sync_at === null) {
            return null;
        }

        return $log->last_sync_at->format('Y-m-d');
    }

    /**
     * Создание записи о синхронизации
     * 
     * @param int $accountId
     * @param int $apiServiceId
     * @param string $date
     * 
     * @return void
     */
    public function markSynced(int $accountId, int $apiServiceId): void
    {
        SyncLog::updateOrCreate(
            ['account_id' => $accountId, 'api_service_id' => $apiServiceId],
            ['last_sync_at' => now()]
        );
    }
}
