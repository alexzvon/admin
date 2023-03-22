<?php

namespace App\Http\Requests\Shop;

use App\Http\Requests\ApiRequest;

class SupplierRequest extends ApiRequest
{
    protected $model = \App\Models\Shop\Supplier\Supplier::class;
    protected $permissionsNamespace = 'shop.suppliers';

    protected $withoutRulesPathes = [
      '*/delete',
    ];

    protected function getEntityRules()
    {
        return [
            'name'        => 'bail|required|max:255',
            'description' => 'max:2048',
            'enabled'     => 'boolean',
        ];
    }
}