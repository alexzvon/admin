<?php namespace App\Http\Controllers\Exports;

use App\Models\Shop\Attribute\Attribute;
use App\Models\Shop\Badge\Badge;
use App\Models\Shop\Product\Product;
use App\Services\ProductsMovements\Facades\ProductsMovements;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportProducts implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $attributes = false;

    public function map($product): array
    {

        $r = 1;
        $result = [
            $product->id, // ID
            $product->i18n->first() ? $product->i18n->first()->title : '', // Название
            $product->i18n->first() ? $product->i18n->first()->description : '', // Описание
            $product->i18n->first() ? $product->i18n->first()->meta_title : '', // Мета-заголовок
            $product->i18n->first() ? $product->i18n->first()->meta_description : '', // Мета-описание
            $product->width, // Ширина
            $product->height, // Высота
            $product->length, // Длина
            $product->weight, // Вес
            $product->delivery_width, // Ширина Доставки
            $product->delivery_height, // Высота Доставки
            $product->delivery_length, // Длина Доставки
            $product->delivery_weight, // Вес Доставки
            $product->delivery_time, // Срок доставки
            $product->production_time, // Срок изготовления
            $product->available ? '1' : '0', // В наличии
            $product->enabled ? '1' : '0', // Опубликовано
            $product->is_payable ? '1' : '0', // Можно оплатить
            $product->id_from_supplier, // ID у поставщика
            $product->gtin, // GTIN
            $this->getProductPrice($product, 1), // Старая цена
            $this->getProductPrice($product, 7), // Цена продажи
            $this->getProductPrice($product, 6), // Акционная цена
            $this->getProductPrice($product, 2), // Минимальная розничная цена
            $this->getProductPrice($product, 4), // Закупочная цена

            // Категории
            $product->categories->map(function($cat) {
                return $cat->id.'|'.$cat->i18n->first()->title;
            })->implode('; '),

            // Стили
            $product->styles->map(function($style) {
                return $style->id.'|'.$style->i18n->first()->title;
            })->implode('; '),

            // Поставщики
            $product->suppliers->map(function($supplier) {
                return $supplier->id.'|'.$supplier->name;
            })->implode('; '),

            // Комнаты
            $product->rooms->map(function($room) {
                return $room->id.'|'.$room->i18n->first()->title;
            })->implode('; '),

            // Бeйджи
            $product->badges->map(function($badge) {
                return $badge->type->id.'|'.$badge->type->icon;
            })->implode('; '),

            // Грузовые места
            $product->cargoPlaces->map(function($place) {
                return '['.$place->width.','.$place->height.','.$place->length.','.$place->weight.']';
            })->implode(','),
        ];

        foreach($this->getAllAttributes() as $attribute) {
            $result[] = $product->linkedAttributes
                ->where('attribute_id', $attribute->id)
                ->map(function($linkedAttribute) {
                    return $linkedAttribute->linkedOptions
                        ->map(function($linkedOption) {
                            return $linkedOption->option->id.'|'.$linkedOption->option->i18n->first()->value;
                        })
                        ->implode("; ");
                })->implode("");
        }

        return $result;
    }


    public function headings(): array
    {
        return ProductsMovements::headings();
    }


    public function query()
    {
        return Product::query()
            ->with([
                'i18n',
                'prices',
                'badges',
                'categories.i18n',
                'styles.i18n',
                'rooms.i18n',
                'linkedAttributes.linkedOptions.option.i18n',
                'suppliers',
                'cargoPlaces',
            ])
            ->where('deleted_at', null);
    }



    // PROTECTED METHODS


    protected function getProductPrice($product, $price_type_id) : int
    {
        return $product->prices->where('price_type_id', $price_type_id)->first()
            ? $product->prices->where('price_type_id', $price_type_id)->first()->value
            : 0;
    }


    protected function getAllAttributes()
    {
        if (!$this->attributes) {
            $this->attributes = Attribute::with('i18n')->get();
        }
        return $this->attributes;
    }
}
