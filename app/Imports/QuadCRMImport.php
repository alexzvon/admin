<?php

namespace App\Imports;

use App\Models\Shop\Product\Product;
use App\Services\Shop\SupplierService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuadCRMImport implements ToModel, WithHeadingRow
{
    /**
     * @var SupplierService
     */
    private $supplierService;

    /**
     * SupplierController constructor.
     * @param SupplierService $supplierService
     */
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function model(array $row)
    {
        //
    }
}
