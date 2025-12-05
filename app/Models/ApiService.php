<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApiService extends Model
{
    protected $fillable = ['name', 'base_url', 'is_active'];

    /**
     * Поддерживаемые типы токенов сервисом
     */
    public function tokenTypes(): BelongsToMany
    {
        return $this->belongsToMany(TokenType::class, 'api_service_token_types');
    }

    /**
     * Аккаунтные токены для этого сервиса
     */
    public function accountTokens()
    {
        return $this->hasMany(AccountToken::class);
    }
}
