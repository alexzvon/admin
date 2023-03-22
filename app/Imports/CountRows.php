<?php

declare(strict_types = 1);

namespace App\Imports;

use App\Events\ProductImportedFailedNotification;
use App\Events\ProductImportedSuccessfulNotification;
use App\Services\ProductsMovements\Import\Interfaces\IImported;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Class CountRows
 *
 * @package App\Imports
 */
class CountRows implements ToCollection, WithHeadingRow
{
    public $count;

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $this->count = 0;

        foreach ($rows as $row) {
            if($row->filter()->isNotEmpty()) {
                $this->count++;
            }
        }
    }
}
