<?php

namespace App\Services;

use App\DTOs\Company\CreateCompanyData;
use App\Models\Company;

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