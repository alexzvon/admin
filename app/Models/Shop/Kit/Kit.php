<?php namespace App\Models\Shop\Kit;

use App\Models\Shop\Price\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kit extends Model
{
    use SoftDeletes;

    protected $table = 'shop_kits';

    protected $guarded = [
      'id',
    ];

    protected $casts = [
      'supplier_id' => 'Integer',
    ];

    protected $with = [
      'prices'
    ];


    public function prices()
    {
        return $this
          ->hasMany(Price::class, 'item_id', 'id')
          ->where('item_type', 'kit')
          ->where('price_type_id', '>', 1);
    }
    
}