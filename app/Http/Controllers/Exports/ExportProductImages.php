<?php

declare(strict_types=1);

namespace App\Http\Controllers\Exports;

use App\Models\Shop\Attribute\Attribute;
use App\Models\Shop\Product\Product;
use App\Services\ProductsMovements\Facades\ProductsMovements;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportProductImages implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $attributes = false;

    public function map($product): array
    {
        $result = [
            $product->id, // ID
            /** @var \App\MediaLibrary\Models\Media $img */
            $product->media->map(function($img) {
                return Storage::url(json_decode($img->pathes)->original);
            })->implode('; '),
        ];

        return $result;
    }


    public function headings(): array
    {
        return [
            'ID',
            'Изображения',
            ];
    }


    public function query()
    {
        return Product::query()
            ->with([
                'media',
            ])
            ->where('deleted_at', null);
    }
}
