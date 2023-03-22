<?php

namespace App\Models\Shop\Supplier;

use App\Support\Traits\Models\StatusChangeable;
use App\Support\Traits\Models\Positionable;
use App\Support\Traits\Models\RequestSaver;
use App\Models\Shop\Product\Product;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MosseboShopCore\Models\Shop\Supplier\Supplier as BaseSupplier;
use App\Models\Shop\Supplier\QuadCRMSupplier;

class Supplier extends BaseSupplier
{
    use StatusChangeable, Positionable, RequestSaver;

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function linksToAttributesAndOptions()
    {
        return $this
          ->hasMany(SupplierAttributeOption::class, 'supplier_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function quadCRMSupplier(): HasOne
    {
        return $this->hasOne(QuadCRMSupplier::class, 'supplier_id', 'id');
    }
}
