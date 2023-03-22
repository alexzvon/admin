<?php

namespace App\Models\Shop\Style;

use App\Support\Traits\Models\StructureTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use MosseboShopCore\Models\Shop\Style\Style as BaseStyle;
use App\Models\Shop\Product\Product;
use App\Models\Shop\Product\ProductCount;

class Style extends BaseStyle implements HasMedia
{
    use StructureTrait;

    protected $mediaCollectionName = 'image';

    protected $needsToSaveFromRequest = [
        'i18n',
        'images'
    ];

    public function productsRelations()
    {
        return $this->hasMany(StyleProduct::class, $this->relationFieldName);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class, StyleProduct::class,
            $this->relationFieldName, 'id', 'id', 'product_id'
        );
    }

    public function productCounts()
    {
        return $this->hasMany(ProductCount::class, $this->relationFieldName);
    }
}