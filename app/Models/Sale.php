<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int            $id                     Уникальный идентификатор записи
 * @property int            $account_id             ID аккаунта, к которому относится продажа
 * @property string         $g_number               Номер заказа
 * @property string         $date                   Дата продажи
 * @property string         $last_change_date       Дата последнего изменения
 * @property string         $supplier_article       Артикул поставщика
 * @property string         $tech_size              Технический размер
 * @property string         $barcode                Штрихкод
 * @property float          $total_price            Общая стоимость продажи
 * @property int            $discount_percent       Процент скидки
 * @property bool           $is_supply              Флаг поставки
 * @property bool           $is_realization         Флаг реализации
 * @property float          $promo_code_discount    Скидка по промокоду
 * @property string         $warehouse_name         Название склада
 * @property string         $country_name           Название страны
 * @property string         $oblast_okrug_name      Название федерального округа
 * @property string         $region_name            Название региона
 * @property string         $income_id              ID поступления
 * @property string         $sale_id                Уникальный ID продажи в системе Wildberries
 * @property string|null    $odid                   Уникальный ID заказа
 * @property float          $spp                    Согласованная скидка постоянного покупателя (СПП)
 * @property float          $for_pay                Сумма к перечислению поставщику
 * @property float          $finished_price         Цена фактическая (с учетом всех скидок)
 * @property float          $price_with_disc        Цена со скидкой
 * @property int            $nm_id                  Артикул Wildberries (номенклатура)
 * @property string         $subject                Предмет (категория товара)
 * @property string         $category               Категория товара
 * @property string         $brand                  Бренд товара
 * @property bool|null      $is_storno              Флаг сторно (возврата)
 */
class Sale extends Model
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
        'is_supply',
        'is_realization',
        'promo_code_discount',
        'warehouse_name',
        'country_name',
        'oblast_okrug_name',
        'region_name',
        'income_id',
        'sale_id',
        'odid',
        'spp',
        'for_pay',
        'finished_price',
        'price_with_disc',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_storno',
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
        return ['sale_id', 'account_id'];
    }
}
