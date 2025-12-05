<?php

namespace App\Services;

use App\Data\CreateCompanyRequest;
use App\DTOs\Company\CreateCompanyData;
use App\Enums\EntityType;
use App\Models\Account;
use App\Models\Company;
use App\Services\Api\ApiClientService;

class CompanyService
{

    /**
     * Создание компании
     * 
     * @param CreateCompanyData $data
     * 
     * @return Company
     */
    public function create(CreateCompanyData $data): Company
    {
        return Company::create($data->toArray());
    }
}