<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id Уникальный идентификатор записи
 * @property string|null $date Дата актуальности остатков (null для текущих остатков)
 * @property int $account_id ID аккаунта, к которому относятся остатки
 * @property string $warehouse_name Название склада
 * @property int $nm_id Артикул Wildberries (номенклатура)
 * @property string $last_change_date Дата последнего изменения
 * @property string $supplier_article Артикул поставщика
 * @property string $tech_size Технический размер
 * @property string $barcode Штрихкод
 * @property int $quantity Количество доступное
 * @property bool $is_supply Флаг поставки
 * @property bool $is_realization Флаг реализации
 * @property int $quantity_full Количество полное
 * @property int $in_way_to_client В пути к клиенту
 * @property int $in_way_from_client В пути от клиента
 * @property string $subject Предмет (категория товара)
 * @property string $category Категория товара
 * @property string $brand Бренд товара
 * @property string $sc_code Код склада
 * @property float $price Цена
 * @property float $discount Скидка
 */
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
        return ['date', 'account_id', 'warehouse_name', 'nm_id'];
    }
}
