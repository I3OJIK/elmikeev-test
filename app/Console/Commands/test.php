<?php

namespace App\Console\Commands;

use App\Enums\EntityType;
use App\Enums\SyncDataType;
use App\Jobs\TestApiSpeedJob;
use App\Models\Account;
use App\Models\AccountToken;
use App\Services\Api\ApiClientService;
use App\Services\SyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class test extends Command
{
    protected $signature = 'test:start 
                            ';

    protected $description = 'Синхронизация остатков на складах из API в БД';


    public function handle(ApiClientService $ApiClientService)
    {
        $token = AccountToken::first();
    
        for ($i = 1; $i <= 50; $i++) {
            TestApiSpeedJob::dispatch($token, $i)->onQueue('test');
        }
        
        $this->info("Запущено 10 тестовых джобов");


        // $token = AccountToken::first();



        // // $accounts = Account::with('token.tokenType.apiService')->get();
        // // foreach ($accounts as $account) {

            
        // // }
        // $baseUrl = 'http://109.73.206.144:6969/api/orders';
        // $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

        // $dateFrom = '0001-01-01';
        // $dateTo   = now()->format('Y-m-d');
        // $params = [
        //     'dateFrom' => $dateFrom,
        //     'dateTo'   => $dateTo,
        //     'key'      => $apiKey,
        // ];

        // $sum = 1;
        // do {

        //     $params['page'] = $sum;
        //     $start = microtime(true);
        //     $ApiClientService->fetchPage($token,EntityType::SALES,$params);
        //     $elapsed = microtime(true) - $start; // конец замера

        //     $this->info("запрос занял: " . round($elapsed * 1000, 2) . " ms");

        //     $sum = $sum+1;
    //     } while ($sum <= 700);
    }
}
