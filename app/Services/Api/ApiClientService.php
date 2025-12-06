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
        $params = array_merge($params, $auth['query']);

        $request = Http::timeout(30);

        // Добавляем headers 
        if (!empty($auth['headers'])) {
            $request = $request->withHeaders($auth['headers']);
        }

        $response = $request->get(
            $service->base_url . $entityType->endpoint(),
            $params
        );

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
            // выполняем запрос три раза при ошибках, если succsess ответа нет - 
            $response = $request
                ->retry(4, 2000)  
                ->throw()   
                ->get($token->apiService->base_url . $entityType->endpoint(), $params);

            $json = $response->json() ?? [];

            return $json['meta']['last_page'];

        } catch (Exception $e) {
            Log::error("Не удалось получить last_page для {$entityType->endpoint()}: " . $e->getMessage());
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

        throw new \Exception("Unknown token location: {$type->location}");
    }
}
   