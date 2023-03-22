<?php

namespace App\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;

class OtherProductOptions extends Model
{
    protected $table = 'other_product_options';

    protected $fillable = [ 'parent_id', 'children_id' ];

    protected $primaryKey = [ 'parent_id', 'children_id' ];

    public $incrementing = false;

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parentProduct()
    {
        return $this->hasOne(Product::class, 'id', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function childrenProduct()
    {
        return $this->hasOne(Product::class, 'id', 'children_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parentProductI18n()
    {
        return $this->hasOne(ProductI18n::class, 'product_id', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function childrenProductI18n()
    {
        return $this->hasOne(ProductI18n::class, 'product_id', 'children_id');
    }
}
