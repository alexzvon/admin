<?php namespace App\Models\Shop\Attribute;

use App\Models\Shop\Price\Price;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Attribute\AttributeOption;

class LinkAttributeRelationOption extends Model
{

    const PRICE_ITEM_TYPE = 'attributeOptionRelation';

    protected $table = 'shop_link_attribute_options';

    protected $casts = [
        'fix_price' => 'Boolean',
        'enabled' => 'Boolean',
        'option_id' => 'Integer',
        'option_product_id' => 'Integer',
    ];

    protected $appends = [];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $guarded = [
        'id'
    ];


    public function linkAttribute()
    {
        return $this
            ->belongsTo(LinkAttributeRelation::class, 'attribute_relation_id', 'id');
    }


    public function option()
    {
        return $this
            ->hasOne(AttributeOption::class, 'id', 'option_id');
    }


    public function prices()
    {
        return $this
            ->hasMany(Price::class, 'item_id', 'id')
            ->where('item_type', '=', static::PRICE_ITEM_TYPE);
    }


    public function price()
    {
        return $this
            ->hasOne(Price::class, 'item_id', 'id')
            ->where('item_type', '=', static::PRICE_ITEM_TYPE)
            ->where('price_type_id', 7);
    }


    public function productPrices()
    {
        return $this
            ->hasMany(Price::class, 'item_id', 'option_product_id')
            ->where('item_type', '=', 'product');
    }


    public function productPrice()
    {
        return $this
            ->hasOne(Price::class, 'item_id', 'option_product_id')
            ->where('item_type', '=', 'product')
            ->where('price_type_id', 7);
    }


}