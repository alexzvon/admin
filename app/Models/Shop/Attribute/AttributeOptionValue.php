<?php

declare(strict_types = 1);

namespace App\Models\Shop\Attribute;

use MosseboShopCore\Models\Base\BaseModel;

/**
 * Class AttributeOptionValue
 *
 * @package App\Models\Shop\Attribute
 */
class AttributeOptionValue extends BaseModel
{
    public $table = 'shop_attribute_options_value';
    protected $tableKey = 'AttributeOptionsValue';

    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'option_id',
        'type',
        'value'
    ];

    public $timestamps = false;
}
