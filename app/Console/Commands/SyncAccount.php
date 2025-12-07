<?php

namespace App\Console\Commands;

use App\Enums\EntityType;
use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncAccount extends Command
{
    protected $signature = 'sync:account 
                            {--dateFrom= : Дата начала (Y-m-d)} 
                            {--dateTo= : Дата окончания (Y-m-d)}';

    protected $description = 'Синхронизация продаж из API в БД';


    public function handle(SyncService $syncService)
    {

    }
}
