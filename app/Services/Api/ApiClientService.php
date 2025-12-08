<?php

namespace App\Services\Api;

use App\Enums\EntityType;
use App\Enums\TokenLocation;
use App\Models\AccountToken;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiClientService
{
    /**
     * Получение одной страницы данных 
     * 
     * @param AccountToken $token
     * @param EntityType $entityType
     * @param array $params
     * 
     * @return Response
     */
    public function fetchPage(AccountToken $token, EntityType $entityType, array $params): Response
    {
        $service = $token->apiService; 
        $auth = $this->buildAuth($token);

        // Добавляем query
        if (!empty($auth['query'])) {
            $params = array_merge($params, $auth['query']);
        }
        $request = Http::timeout(30);

        // Добавляем headers 
        if (!empty($auth['headers'])) {
            $request = $request->withHeaders($auth['headers']);
        }

        $response = $request->get(
            $service->base_url . $entityType->value,
            $params
        );

        // Обработка 429
        if ($response->status() === 429) {
            $retryAfter = (int) ($response->header('Retry-After') ?? 5);
            Log::warning("429 Too Many Requests, повтор через {$retryAfter} сек.");
            sleep($retryAfter);
            throw new Exception('429 Too Many Requests');
        }

        if (!$response->successful()) {
            Log::error("API error: " . $response->status());
            throw new Exception("API error: " . $response->status());
        }

        return $response;
    }

    /**
     * Получение количества старниц 
     * 
     * @param AccountToken $token
     * @param EntityType $entityType
     * @param array $params
     * 
     * @return int|null
     */
    public function getLastPage(AccountToken $token, EntityType $entityType, array $params = []): ?int
    {
        $auth = $this->buildAuth($token);

        // Добавляем query параметры из токена
        $params = array_merge($params, $auth['query']);

        $request = Http::timeout(30);

        // Добавляем заголовки
        if (!empty($auth['headers'])) {
            $request = $request->withHeaders($auth['headers']);
        }

        try {
            // выполняем запрос три раза при ошибках
            $response = $request
                ->retry(60, 2000)  
                ->throw()   
                ->get($token->apiService->base_url . $entityType->value, $params);

            $json = $response->json() ?? [];

            if (!$json['data']){
                return 0;
            }

            return $json['meta']['last_page'];

        } catch (Exception $e) {
            Log::error("Не удалось получить last_page для {$entityType->value}: " . $e->getMessage());
            return null;
        }
    }


    /**
     * Собирает токен для header или query
     * 
     * @param AccountToken $token
     * 
     * @return array
     */
    private function buildAuth(AccountToken $token): array
    {
        $type = $token->tokenType;

        //подставляем токен в шаблон
        $value = str_replace("{}", $token->token_value, $type->value_template);

        if ($type->location === TokenLocation::HEADER) {
            return [
                'headers' => [
                    $type->param_name => $value
                ],
                'query' => []
            ];
        }

        if ($type->location === TokenLocation::QUERY) {
            return [
                'headers' => [],
                'query' => [
                    $type->param_name => $value
                ]
            ];
        }

        throw new \Exception("Неизвестный token location: {$type->location}");
    }
}
   