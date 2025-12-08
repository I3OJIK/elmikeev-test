<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id Уникальный идентификатор компании
 * @property string $name Название компании
 * @property Carbon|null $created_at Дата создания
 * @property Carbon|null $updated_at Дата обновления
 */
class Company extends Model
{
    protected $fillable = ['name'];

    /**
     * Компания может иметь несколько аккаунтов
     * 
     * @return HasMany
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
