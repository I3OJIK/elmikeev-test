<?php

namespace App\Services;

use App\DTOs\Account\CreateAccountData;
use App\DTOs\ApiService\CreateApiServiceData;
use App\Models\Account;
use App\Models\ApiService;

class ApiServiceService
{

    public function create(CreateApiServiceData $data): ApiService
    {
        return ApiService::create($data->toArray());
    }
}