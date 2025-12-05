<?php

namespace App\Services;

use App\DTOs\Account\CreateAccountData;
use App\Models\Account;

class AccountService
{

    public function create(CreateAccountData $data): Account
    {
        return Account::create($data->toArray());
    }
}