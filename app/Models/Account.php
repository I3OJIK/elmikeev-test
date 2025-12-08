<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id Уникальный идентификатор аккаунта
 * @property int $company_id Ссылка на компанию
 * @property string $name Название аккаунта
 * @property Carbon|null $created_at Дата создания
 * @property Carbon|null $updated_at Дата обновления
 */
class Account extends Model
{
    protected $fillable = ['company_id', 'name'];

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
