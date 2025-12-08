<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int            $id             Уникальный идентификатор записи лога
 * @property int            $account_id     ID аккаунта
 * @property int            $api_service_id ID API сервиса
 * @property Carbon|null    $last_sync_at   Дата и время последней успешной синхронизации
 */
class SyncLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['account_id', 'api_service_id', 'last_sync_at'];

    protected $casts = [
        'last_sync_at' => 'datetime'
    ];
}
