<?php

namespace App\Models\Shop\Order;

use MosseboShopCore\Models\Shop\Order\Order as BaseOrder;
use App\Models\User;
use App\Models\Language;
use App\Models\Shop\Promo\PromoUse;
use App\Models\Shop\PayType\PayType;
use App\Models\Shop\DeliveryType\DeliveryType;

class Order extends BaseOrder
{
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function language()
    {
        return $this->hasOne(Language::class, 'code', 'language_code');
    }

    public function status()
    {
        return $this->hasOne(OrderStatus::class, 'id', 'order_status_id');
    }

    public function payType()
    {
        return $this->hasOne(PayType::class, 'id', 'pay_type_id');
    }

    public function deliveryType()
    {
        return $this->hasOne(DeliveryType::class, 'id', 'delivery_type_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, $this->relationFieldName, 'id');
    }

    public function promoUse()
    {
        return $this->hasOne(PromoUse::class, $this->relationFieldName, 'id');
    }
}

