<?php

namespace App\Services;

use App\DTOs\ApiService\CreateApiServiceData;
use App\Models\ApiService;

class ApiServiceService
{
    /**
     * Создание апи сервиса
     * 
     * @param CreateApiServiceData $data
     * 
     * @return ApiService
     */
    public function create(CreateApiServiceData $data): ApiService
    {
        return ApiService::create($data->toArray());
    }
}