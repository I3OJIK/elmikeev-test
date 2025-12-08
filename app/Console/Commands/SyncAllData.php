<?php

namespace App\Console\Commands;

use App\Services\SyncService;

class SyncAllData extends BaseCommand
{
    protected $signature = 'app:sync:all-data';

    protected $description = 'Синхронизация всех данных из API в БД';

    public function handle(SyncService $syncService)
    {
        try {
            $this->info("Начало синхронизации всех данных...");
            $syncService->setOutput($this->output);
            
            $syncService->syncAll();
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
