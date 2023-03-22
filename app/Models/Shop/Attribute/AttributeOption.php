<?php

namespace App\Models\Shop\Attribute;

use App\Models\Shop\Price\Price;
use App\Support\Traits\Models\StatusChangeable;
use App\Support\Traits\Models\RequestSaver;
use App\Support\Traits\Models\Positionable;
use App\Support\Traits\Models\I18nTrait;
use App\Models\Shop\Product\Product;
use App\Models\Shop\Product\ProductAttributeOption;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MosseboShopCore\Models\Shop\Attribute\AttributeOption as BaseAttributeOption;

/**
 * Class AttributeOption
 *
 * @package App\Models\Shop\Attribute
 */
class AttributeOption extends BaseAttributeOption
{
    use StatusChangeable, RequestSaver, Positionable, I18nTrait;

    protected $needsToSaveFromRequest = [
        'i18n',
        'value',
    ];

    public function attribute()
    {
        return $this->hasOne(Attribute::class, $this->relationFieldName);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class, ProductAttributeOption::class,
            $this->relationFieldName, 'id', 'id', 'product_id'
        );
    }

    public function productRelations()
    {
        return $this->hasMany(ProductAttributeOption::class, $this->relationFieldName);
    }


    public function prices()
    {
        return $this
          ->hasMany(Price::class, 'item_id')
          ->whereIn('item_type', [
            'attributeOption',
            'productAttributeOption',
            'orderProductAttributeOption',
          ]);
    }

    /**
     * @return HasMany
     */
    public function value(): HasMany
    {
        return $this->hasMany(AttributeOptionValue::class, $this->relationFieldName);
    }

    /**
     * @param array $values
     */
    protected function _saveValue(Array $values = [])
    {
        $this->value()->whereIn('type', array_keys($values))->delete();

        foreach ($values as $type => $value) {
            $value['type'] = $type;
            $value[$this->relationFieldName] = $this->id;
            if (null !== $value['value'] && !empty($value['value'])) {
                (new AttributeOptionValue($value))->save();
            }
        }
    }
}
