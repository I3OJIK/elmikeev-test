<?php

namespace App\Services\Api;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiClientService
{
    private string $baseUrl;
    private string $apiKey;
    private $lastRequestEndTime = null;

    public function __construct()
    {
        $this->baseUrl = env('API_URL');
        $this->apiKey = env('API_KEY');
    }

    /**
     * Выполняет пагинационный запрос данных из API
     * 
     * @param string $endpoint
     * @param array $params параметры запроса
     * @param callable $processor функция для обработки батчей
     * 
     * @return void
     */
    function fetchPaginatedData(string $endpoint, array $params, callable $processor): void
    {
        $page = 1;
        $batchData = [];

        do{
            $params['page'] = $page;
            $request = $this->makeRequest($endpoint, $params);
            $data = $request['data'];
            $lastPage = $request['meta']['last_page'];
            if (empty($data)){
                break;
            }

            $batchData = array_merge($batchData, $data);
            
            if (count($batchData) >= 2000 || $page >= $lastPage) {
                $processor($batchData);
                $batchData = [];
            }

            if ($page>=$lastPage){
                break;
            }

            $page++;
        } while(true);
    }


    /**
     * Выполняет одиночный запрос к api
     * 
     * @param string $endpoint
     * @param array $params
     * 
     * @return array
     */
    private function makeRequest(string $endpoint, array $params): array
    {
        // Соблюдение rate limit - 1 запрос в секунду
        if ($this->lastRequestEndTime) {
            $timeSinceLastRequest = microtime(true) - $this->lastRequestEndTime;
            if ($timeSinceLastRequest < 1) { 
                $sleepTime = 1 - $timeSinceLastRequest;
                usleep($sleepTime * 1000000);
            }
        }

        \dump($params['page'], $endpoint);
        $params['key'] = $this->apiKey;
        $response = Http::timeout(10)
            ->throw()
            ->retry(5, 2000,  function (RequestException $re) {
                return $re->getCode() == 429;
            })
            ->get($this->baseUrl . $endpoint, $params);

        $this->lastRequestEndTime = microtime(true);

        return json_decode($response->body(),true);
    }
}