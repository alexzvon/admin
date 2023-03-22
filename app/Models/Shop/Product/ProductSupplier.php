<?php

namespace App\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    protected $table = 'product_supplier';

    public $timestamps = false;
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_id',
        'supplier_id',
    ];
}
