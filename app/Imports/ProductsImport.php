<?php

declare(strict_types = 1);

namespace App\Imports;

use App\Events\ProductImportedFailedNotification;
use App\Events\ProductImportedSuccessfulNotification;
use App\Services\ProductsMovements\Import\Interfaces\IImported;
use App\Services\ProductsMovements\Import\Products\Imported\Imported;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Class ProductsImport
 *
 * @package App\Imports
 */
class ProductsImport implements ToCollection, WithHeadingRow
{
    public $data;

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $products = collect();

        foreach ($rows as $id => $row) {
            if($row->filter()->isNotEmpty()) {
                try {
                    /** @var Imported $importedObject */
                    $importedObject = \ProductsMovements::importProduct($row->toArray());
                    $products->push($importedObject);
                    event(new ProductImportedSuccessfulNotification($importedObject->getObject()->id));
                } catch (\Throwable $exception) {
                    \Log::alert('Ошибка на строке: ' . ($id + 2) .': '. $exception->getMessage());
                    \Log::alert($exception->getTraceAsString());
                    event(new ProductImportedFailedNotification('test'));
                    $this->data['failed'][] = ['id' => $row['ID'] ?? null];
                }
            }
        }

        foreach ($products as $imported) {
            /** @var IImported $imported */
            if ($imported->isCreated()) {
                $this->data['created'][] = $imported->getObject();
            } else {
                $this->data['updated'][] = $imported->getObject();
            }
        }

//        $this->data = $products;
    }
}
