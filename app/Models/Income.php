<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'income_id',
        'number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'total_price',
        'date_close',
        'warehouse_name',
        'nm_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public static function getUniqueKey(): array
    {
        return ['income_id', 'nm_id', 'account_id'];
    }
}
