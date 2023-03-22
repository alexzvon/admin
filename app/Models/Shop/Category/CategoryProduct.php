<?php

namespace App\Models\Shop\Category;

use MosseboShopCore\Models\Shop\Category\CategoryProduct as BaseCategoryProduct;
use App\Models\Shop\Product\Product;

class CategoryProduct extends BaseCategoryProduct
{
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
