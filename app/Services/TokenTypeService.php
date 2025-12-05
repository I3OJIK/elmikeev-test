<?php

namespace App\Services;

use App\DTOs\Account\CreateAccountData;
use App\DTOs\TokenType\CreateTokenTypeData;
use App\Models\Account;
use App\Models\TokenType;

class TokenTypeService
{

    public function create(CreateTokenTypeData $data): TokenType
    {
        return TokenType::create($data->toArray());
    }
}