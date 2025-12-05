<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountTokenField extends Model
{
    protected $fillable = ['account_token_id', 'name', 'value'];

    /**
     * Токен, которому принадлежит поле
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(AccountToken::class, 'account_token_id');
    }
}
