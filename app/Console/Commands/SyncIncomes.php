<?php

namespace App\Console\Commands;

use App\Enums\EntityType;
use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncIncomes extends Command
{
    protected $signature = 'sync:incomes 
                            {--dateFrom= : Дата начала (Y-m-d)} 
                            {--dateTo= : Дата окончания (Y-m-d)}';

    protected $description = 'Синхронизация доходов из API в БД';


    public function handle(SyncService $syncService)
    {
        $params = [];
        
        if ($this->option('dateFrom')) {
            $params['dateFrom'] = $this->option('dateFrom');
        }
        
        if ($this->option('dateTo')) {
            $params['dateTo'] = $this->option('dateTo');
        }

        $this->info("Начало синхронизации доходов...");
        
        $processed = $syncService->syncEntity(EntityType::INCOMES, $params);
        
        $this->info("Доходы синхронизированы. Записей: {$processed}");
    }
}
