<?php

namespace App\Models\Shop\Product;

use MosseboShopCore\Models\Shop\Product\ProductI18n as BaseProductI18n;

class ProductI18n extends BaseProductI18n
{
    protected $table = 'shop_products_i18n';

    protected $tableKey = 'ProductsI18n';

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'language_code',
        'title',
        'description',
        'meta_title',
        'meta_description'
    ];

    public $timestamps = false;

}