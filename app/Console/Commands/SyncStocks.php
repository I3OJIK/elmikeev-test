<?php

namespace App\Console\Commands;

use App\Enums\EntityType;
use App\Enums\SyncDataType;
use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncStocks extends Command
{
    protected $signature = 'sync:stocks 
                            {--dateFrom= : Дата начала (Y-m-d)} 
                            {--dateTo= : Дата окончания (Y-m-d)}';

    protected $description = 'Синхронизация остатков на складах из API в БД';


    public function handle(SyncService $syncService)
    {
        $params = [];
        
        if ($this->option('dateFrom')) {
            $params['dateFrom'] = $this->option('dateFrom');
        }
        
        if ($this->option('dateTo')) {
            $params['dateTo'] = $this->option('dateTo');
        }

        $this->info("Начало синхронизации остатков...");
        
        $processed = $syncService->syncEntity(EntityType::STOCKS, $params);
        
        $this->info("Остатки синхронизированы. Записей: {$processed}");
    }
}
