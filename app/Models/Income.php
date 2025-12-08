<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id Уникальный идентификатор записи
 * @property int $account_id ID аккаунта, к которому относится поступление
 * @property string $income_id ID поступления в системе Wildberries
 * @property string $number Номер поступления
 * @property string $date Дата поступления
 * @property string $last_change_date Дата последнего изменения
 * @property string $supplier_article Артикул поставщика
 * @property string $tech_size Технический размер
 * @property string $barcode Штрихкод
 * @property int $quantity Количество товара
 * @property float $total_price Общая стоимость
 * @property string|null $date_close Дата закрытия поступления
 * @property string $warehouse_name Название склада
 * @property int $nm_id Артикул Wildberries (номенклатура)
 */
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

    /**
     * Связь с аккаунтом
     * 
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Получение уникального составного ключа
     * 
     * @return array
     */
    public static function getUniqueKey(): array
    {
        return ['income_id', 'nm_id', 'account_id'];
    }
}
