<?php

namespace App\Console\Commands;

use App\Enums\EntityType;
use App\Enums\SyncDataType;
use App\Models\AccountToken;
use App\Services\Api\ApiClientService;
use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncStocks extends Command
{
    protected $signature = 'sync:stocks 
                            ';

    protected $description = 'Синхронизация остатков на складах из API в БД';


    public function handle(ApiClientService $syncService)
    {   
        $acc =  AccountToken::first();
        $params = [
            'dateFrom' => '2025-01-01',
            'dateTo' => now()->format('Y-m-d')
        ];

        $response = $syncService->fetchPage($acc, EntityType::ORDERS, $params);
        dd($response->header('Date'));

        
        
        if ($this->option('dateFrom')) {
            $params['dateFrom'] = $this->option('dateFrom');
        }
        
        if ($this->option('dateTo')) {
            $params['dateTo'] = $this->option('dateTo');
        }

        $this->info("Начало синхронизации остатков...");
        
        $processed = $syncService->syncEntity(EntityType::STOCKS->endpoint(), $params);
        
        $this->info("Остатки синхронизированы. Записей: {$processed}");
    }
}
