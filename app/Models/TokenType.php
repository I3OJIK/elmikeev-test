<?php

namespace App\Models;

use App\Enums\TokenLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int            $id             Уникальный идентификатор типа токена
 * @property string         $name           Название типа токена (например: "Bearer", "API Key", "Basic Auth")
 * @property TokenLocation  $location       Место передачи токена (header или query)
 * @property string         $param_name     Имя параметра (например: "Authorization", "api_key", "token")
 * @property string         $value_template Шаблон значения (например: "Bearer {}", "Basic {}")
 */
class TokenType extends Model
{
    protected $fillable = [
        'name',
        'location',
        'param_name',
        'value_template'
    ];
    public $timestamps = false;
    protected $casts = [
        'location' => TokenLocation::class, 
    ];

     /**
     * Сервисы у которых используется данный тип токена
     * 
     * @return BelongsToMany
     */
    public function apiServices(): BelongsToMany
    {
        return $this->belongsToMany(ApiService::class, 'api_service_token_types');
    }

    /**
     * Все аккаунтные токены этого типа
     * 
     * @return HasMany
     */
    public function accountTokens(): HasMany
    {
        return $this->hasMany(AccountToken::class);
    }
}
