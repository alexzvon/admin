<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Shop\ReturnRequest\ReturnRequestEditResource;
use App\Http\Resources\Shop\ReturnRequest\ReturnRequestTableResource;
use App\Models\Shop\ReturnRequest\ReturnRequest;
use App\Models\Shop\Supplier\SupplierAttributeOption;
use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\PositionChangeable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\Controllers\Updatable;

/**
 * Class ReturnRequestController
 *
 * @package App\Http\Controllers\Api\Shop
 */
class ReturnRequestController extends ApiController
{
    use Creatable, Updatable, Deleteable, StatusChangeable, PositionChangeable;

    protected static $modelClass = ReturnRequest::class;
    protected static $entityName = 'returns';
    protected static $editResource = ReturnRequestEditResource::class;
    protected static $tableResource = ReturnRequestTableResource::class;

    public function index()
    {
        $r = 1;
        return [
            'returns' => static::toResource(static::$modelClass::get()),
        ];
    }

    public function getAttributes($supplier_id)
    {
        return SupplierAttributeOption::where('returns_id', $supplier_id)->get();
    }
}
