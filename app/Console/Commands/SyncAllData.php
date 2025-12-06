<?php

namespace App\Console\Commands;

use App\Enums\EntityType;
use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncAllData extends BaseCommand
{
    protected $signature = 'sync:all-data';

    protected $description = 'Синхронизация всех данных из API в БД';


    public function handle(SyncService $syncService)
    {
        try {
        $this->info("Начало синхронизации всех данных...");
        $syncService->setOutput($this->output);
        
        $results = $syncService->syncAll();

        $this->info("\n Результаты синхронизации:");
        } catch (\Exception $e) {
            return $this->handleGenericException($e);
        }
    }
}
