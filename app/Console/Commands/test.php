<?php

namespace App\Console\Commands;

use App\Enums\EntityType;
use App\Enums\SyncDataType;
use App\Models\Account;
use App\Services\SyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class test extends Command
{
    protected $signature = 'test:start 
                            ';

    protected $description = 'Синхронизация остатков на складах из API в БД';


    public function handle(SyncService $syncService)
    {
        // dd(Account::first()->token);



        // $accounts = Account::with('token.tokenType.apiService')->get();
        // foreach ($accounts as $account) {

            
        // }
        $baseUrl = 'http://109.73.206.144:6969/api/orders';
        $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

        $dateFrom = '0001-01-01';
        $dateTo   = now()->format('Y-m-d');


        $sum = 0;
        do {
            $params = [
                'dateFrom' => $dateFrom,
                'dateTo'   => $dateTo,
                'key'      => $apiKey,
            ];

            // Делаем GET-запрос
            $response = Http::timeout(10)->get($baseUrl, $params);
            // Выводим все заголовки
            $this->info("Страница $sum, заголовки ответа:");
            foreach ($response->headers() as $key => $values) {
                $this->line("$key: " . implode(', ', $values));
            }
            
            if ($response->status() == 429) {
                $sum= $sum - 1;
                $retryAfter = (int) $response->header('Retry-After');
                sleep($retryAfter); // ждем указанное время
                
            }

            $sum= $sum+1;
        } while ($sum <= 700);
    }
}
