<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Shop\Supplier\SupplierEditResource;
use App\Models\Shop\Supplier\Supplier;
use App\Models\Shop\Supplier\SupplierAttributeOption;
use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\PositionChangeable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\Controllers\Updatable;

/**
 * Class SupplierController
 *
 * @package App\Http\Controllers\Api\Shop
 */
class SupplierController extends ApiController
{
    use Creatable, Updatable, Deleteable, StatusChangeable, PositionChangeable;

    protected static $modelClass = Supplier::class;
    protected static $entityName = 'supplier';
    protected static $editResource = SupplierEditResource::class;
    protected static $tableResource = SupplierEditResource::class;

    public function index()
    {
        return [
            'suppliers' => static::toResource(static::$modelClass::get()),
        ];
    }

    public function getAttributes($supplier_id)
    {
        return SupplierAttributeOption::where('supplier_id', $supplier_id)->get();
    }
}
