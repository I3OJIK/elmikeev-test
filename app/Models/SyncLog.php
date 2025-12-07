<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['account_id', 'api_service_id', 'last_sync_at'];

    protected $casts = [
        'last_sync_at' => 'datetime'
    ];
}
