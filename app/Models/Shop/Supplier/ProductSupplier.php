<?php

namespace App\Models\Shop\Supplier;

use App\Support\Traits\Models\StatusChangeable;
use App\Support\Traits\Models\Positionable;
use App\Support\Traits\Models\RequestSaver;
use App\Models\Shop\Product\Product;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MosseboShopCore\Models\Base\BaseModel;
use MosseboShopCore\Models\Shop\Supplier\Supplier as BaseSupplier;
use App\Models\Shop\Supplier\QuadCRMSupplier;

class ProductSupplier extends BaseModel
{
    protected $table = 'product_supplier';
    protected $fillable = [
        'product_id',
        'supplier_id',
    ];
}
