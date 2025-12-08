<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int        $id             Уникальный идентификатор токена
 * @property int        $account_id     ID аккаунта-владельца
 * @property int        $api_service_id ID API сервиса
 * @property int        $token_type_id  ID типа токена
 * @property string     $token_value    Значение токена 
 */
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
     * 
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * API сервис, для которого этот токен
     * 
     * @return BelongsTo
     */
    public function apiService(): BelongsTo
    {
        return $this->belongsTo(ApiService::class);
    }

    /**
     * Тип токена
     * 
     * @return BelongsTo
     */
    public function tokenType(): BelongsTo
    {
        return $this->belongsTo(TokenType::class, 'token_type_id');
    }
}
