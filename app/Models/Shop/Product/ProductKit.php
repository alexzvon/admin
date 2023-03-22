<?php

namespace App\Models\Shop\Product;

use App\Models\Shop\Kit\Kit;
use App\Models\Shop\Price\Price;
use Illuminate\Database\Eloquent\Model;

class ProductKit extends Model
{
    protected $table = 'shop_product_kits';

    protected $guarded = [
      'id'
    ];

    protected $fillable = [
      'product_id',
      'kit_id',
      'enabled',
    ];

    protected $usedPriceTypeIds = [2,3,4,6,7,8];


    public function kit()
    {
        return $this
          ->hasOne(Kit::class, 'id', 'kit_id');
    }


    public function supplierKitPrices()
    {
        return $this
          ->hasMany(Price::class, 'item_id', 'kit_id')
          ->where('item_type', '=', 'kit');
    }


    public function prices()
    {
        return $this
          ->hasMany(Price::class, 'item_id', 'id')
          ->where('item_type', '=', 'productKit');
    }





}