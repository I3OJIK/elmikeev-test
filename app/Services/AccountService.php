<?php

namespace App\Services;

use App\DTOs\Account\CreateAccountData;
use App\Models\Account;

class AccountService
{
    /**
     * Создание аккаунта
     * 
     * @param CreateAccountData $data
     * 
     * @return Account
     */
    public function create(CreateAccountData $data): Account
    {
        return Account::create($data->toArray());
    }
}