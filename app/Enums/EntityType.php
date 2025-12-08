<?php

namespace App\Enums;

use App\Models\Income;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Stock;

enum EntityType: string
{
    case SALES = 'sales';
    case ORDERS = 'orders';
    case STOCKS = 'stocks';
    case INCOMES = 'incomes';
    
    /**
     * Получить класс модели для типа данных
     * 
     * @return string
     */
    public function getModelClass(): string
    {
        return match($this) {
            self::SALES => Sale::class,
            self::ORDERS => Order::class,
            self::STOCKS => Stock::class,
            self::INCOMES => Income::class,
        };
    }
}