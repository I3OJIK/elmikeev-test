<?php

namespace App\Console\Commands;

use App\Enums\EntityType;
use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncSales extends Command
{
    protected $signature = 'sync:sales 
                            {--dateFrom= : Дата начала (Y-m-d)} 
                            {--dateTo= : Дата окончания (Y-m-d)}';

    protected $description = 'Синхронизация продаж из API в БД';


    public function handle(SyncService $syncService)
    {
        $params = [];
        
        if ($this->option('dateFrom')) {
            $params['dateFrom'] = $this->option('dateFrom');
        }
        
        if ($this->option('dateTo')) {
            $params['dateTo'] = $this->option('dateTo');
        }

        $this->info("Начало синхронизации продаж...");
        $start = microtime(true);
        $processed = $syncService->syncEntity(EntityType::SALES, $params);
        $stop = microtime(true);
        \dump($stop-$start);
        $this->info("Продажи синхронизированы. Записей: {$processed}");
    }
}
