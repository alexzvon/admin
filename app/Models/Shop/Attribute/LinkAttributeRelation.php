<?php namespace App\Models\Shop\Attribute;

use App\Models\Shop\Price\Price;
use Illuminate\Database\Eloquent\Model;

class LinkAttributeRelation extends Model
{

    protected $table = 'shop_link_attribute_relations';

    protected $casts = [
        'selectable' => 'Boolean',
        'fix_price' => 'Boolean',
        'enabled' => 'Boolean',
        'attribute_id' => 'Integer',
        'relation' => 'String',
        'relation_id' => 'Integer',
    ];

    protected $appends = [];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $guarded = [
        'id'
    ];


    public function isAttributeCharacteristic()
    {
        return in_array($this->attributes['attribute_id'], Attribute::CHARACTERISTIC_IDS);
    }


    public function scopeForProducts($q)
    {
        return $q->where('relation', 'product');
    }


    public function scopeForCartProducts($q)
    {
        return $q->where('relation', 'cartProduct');
    }


    public function scopeForOrderProducts($q)
    {
        return $q->where('relation', 'orderProduct');
    }


    public function scopeForSuppliers($q)
    {
        return $q->where('relation', 'supplier');
    }


    public function linkedOptions()
    {
        return $this
            ->hasMany(LinkAttributeRelationOption::class, 'attribute_relation_id', 'id')
            ->orderBy('id', 'asc');
    }


    public function attribute()
    {
        return $this
            ->hasOne(Attribute::class, 'id', 'attribute_id');
    }



}