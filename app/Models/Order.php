<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int        $id                     Уникальный идентификатор записи
 * @property int        $account_id             ID аккаунта, к которому относится заказ
 * @property string     $g_number               Номер заказа в системе Wildberries
 * @property string     $date                   Дата заказа
 * @property string     $last_change_date       Дата последнего изменения
 * @property string     $supplier_article       Артикул поставщика
 * @property string     $tech_size              Технический размер
 * @property string     $barcode                Штрихкод
 * @property float      $total_price            Общая стоимость заказа
 * @property int        $discount_percent       Процент скидки
 * @property string     $warehouse_name         Название склада
 * @property string     $oblast                 Область/регион доставки
 * @property string     $income_id              ID поступления
 * @property string     $odid                   Уникальный ID заказа в системе Wildberries
 * @property int        $nm_id                  Артикул Wildberries (номенклатура)
 * @property string     $subject                Предмет (категория товара)
 * @property string     $category               Категория товара
 * @property string     $brand                  Бренд товара
 * @property bool       $is_cancel              Флаг отмены заказа
 * @property string|null $cancel_dt             Дата отмены заказа
 */
class Order extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'g_number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'total_price',
        'discount_percent',
        'warehouse_name',
        'oblast',
        'income_id',
        'odid',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_cancel',
        'cancel_dt',
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
        return ['g_number', 'nm_id', 'account_id', 'date'];
    }
}
