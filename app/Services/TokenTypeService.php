<?php

namespace App\Services;

use App\DTOs\TokenType\CreateTokenTypeData;
use App\Models\TokenType;

class TokenTypeService
{
    /**
     * Создание типа токена
     * 
     * @param CreateTokenTypeData $data
     * 
     * @return TokenType
     */
    public function create(CreateTokenTypeData $data): TokenType
    {
        return TokenType::create($data->toArray());
    }
}