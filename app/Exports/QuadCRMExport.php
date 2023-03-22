<?php

namespace App\Exports;

use App\Models\Shop\Product\Product;
use App\Services\Shop\SupplierService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuadCRMExport implements FromCollection, WithHeadings
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

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'ID у поставщика',
            'Поставщики',
            'Старая цена',
            'Цена продажи',
            'Акционная цена',
            'Закупочная цена',
            'Под заказ',
            'В наличии',
            'Дата поступления',
            'Срок изготовления',
        ];
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $products = $this->supplierService->getSupplierProducts();

        return collect($products);
    }
}
