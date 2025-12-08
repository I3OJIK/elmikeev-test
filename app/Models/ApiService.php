<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id Уникальный идентификатор сервиса
 * @property string $name Название сервиса 
 * @property string $base_url Базовый URL для API запросов
 */
class ApiService extends Model
{
    protected $fillable = ['name', 'base_url'];

    /**
     * Поддерживаемые типы токенов сервисом
     * 
     * @return BelongsToMany
     */
    public function tokenTypes(): BelongsToMany
    {
        return $this->belongsToMany(TokenType::class, 'api_service_token_types');
    }

    /**
     * Аккаунтные токены для этого сервиса
     * 
     * @return HasMany
     */
    public function accountTokens(): HasMany
    {
        return $this->hasMany(AccountToken::class);
    }
}
