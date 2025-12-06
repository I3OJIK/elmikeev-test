<?php

namespace App\Models;

use App\Enums\TokenLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     */
    public function apiServices(): BelongsToMany
    {
        return $this->belongsToMany(ApiService::class, 'api_service_token_types');
    }

    /**
     * Все аккаунтные токены этого типа
     */
    public function accountTokens()
    {
        return $this->hasMany(AccountToken::class);
    }
}
