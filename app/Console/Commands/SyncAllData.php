<?php

namespace App\Console\Commands;

use App\Enums\EntityType;
use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncAllData extends Command
{
    protected $signature = 'sync:all-data';

    protected $description = 'Синхронизация всех данных из API в БД';


    public function handle(SyncService $syncService)
    {
        
        $this->info("Начало синхронизации всех данных...");
        
        $results = $syncService->syncAll(EntityType::STOCKS);
        
        $this->info("\n Результаты синхронизации:");
        foreach ($results as $type => $count) {
            $this->info("{$type}: {$count} записей");
        }
    }
}
