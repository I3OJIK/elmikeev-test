<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
     * Аккаунту принадлежит токен
     */
    public function token(): HasOne
    {
        return $this->hasOne(AccountToken::class);
    }
}
