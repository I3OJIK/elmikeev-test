<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountToken extends Model
{
    protected $fillable = [
        'account_id',
        'api_service_id',
        'token_type_id',
        'token_value',
    ];

    public $timestamps = false;

    /**
     * Аккаунт, которому принадлежит токен
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * API сервис, для которого этот токен
     */
    public function apiService(): BelongsTo
    {
        return $this->belongsTo(ApiService::class);
    }

    /**
     * Тип токена
     */
    public function tokenType(): BelongsTo
    {
        return $this->belongsTo(TokenType::class, 'token_type_id');
    }

    /**
     * Поля токена
     */
    public function fields(): HasMany
    {
        return $this->hasMany(AccountTokenField::class);
    }
}
