<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'date',
        'account_id',
        'warehouse_name',
        'nm_id',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'is_supply',
        'is_realization',
        'quantity_full',
        'in_way_to_client',
        'in_way_from_client',
        'subject',
        'category',
        'brand',
        'sc_code',
        'price',
        'discount',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public static function getUniqueKey(): array
    {
        return ['date', 'account_id', 'warehouse_name', 'nm_id'];
    }
}
