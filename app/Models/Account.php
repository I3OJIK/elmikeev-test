<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = ['company_id', 'name', 'is_active'];

    /**
     * Аккаунт принадлежит компании
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Аккаунт может иметь несколько токенов
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(AccountToken::class);
    }
}
