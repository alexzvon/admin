<?php

namespace App\Models\Shop\Product;

use MosseboShopCore\Models\Shop\Product\ProductAttributeOption as BaseProductAttributeOption;
use App\Models\Shop\Category\Category;
use App\Models\Shop\Attribute\AttributeOption;

class ProductAttributeOption extends BaseProductAttributeOption
{
    public function attribute()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function option()
    {
        return $this->belongsTo(AttributeOption::class, 'option_id');
    }
}
