<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements;

use App\Models\Shop\Attribute\Attribute;
use App\Models\Shop\Product\Product;
use App\Services\ProductsMovements\Import\Interfaces\IImported;
use App\Services\ProductsMovements\Import\Products\ExistentProductProcessor;
use App\Services\ProductsMovements\Import\Products\Imported\Created;
use App\Services\ProductsMovements\Import\Products\Imported\Updated;
use App\Services\ProductsMovements\Import\Products\NonExistentProductProcessor;
use App\Services\ProductsMovements\Import\Products\ParametersBag;
use App\Services\ProductsMovements\Import\Products\ProductProcessor;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ProductsMovements
 *
 * @package App\Services\ProductsMovements
 */
class ProductsMovements
{
    const RELATED_PRODUCTS_ATTRIBUTE_ID = 37;
    const RELATION_TYPE = 'product';

    /**
     * @var array $attributes 
     */
    public $attributes;

    /**
     * @var ProductProcessor $processor
     */
    protected $processor;

    /**
     * @return array
     */
    public function headings(): array
    {
        $result = [
            'ID',
            'Название',
            'Описание',
            'Мета-заголовок',
            'Мета-описание',
            'Ширина',
            'Высота',
            'Длина',
            'Вес',
            'Ширина Доставки',
            'Высота Доставки',
            'Длина Доставки',
            'Вес Доставки',
            'Срок доставки',
            'Срок изготовления',
            'В наличии',
            'Опубликовано',
            'Можно оплатить',
            'ID у поставщика',
            'GTIN',
            'Старая цена',
            'Цена продажи',
            'Акционная цена',
            'Минимальная розничная цена',
            'Закупочная цена',
            'Категории',
            'Стили',
            'Поставщики',
            'Комнаты',
            'Бейджи',
            'Грузовые места',
        ];

        foreach($this->getAllAttributes() as $attribute) {
            $result[] = $attribute->id.'|'.$attribute->i18n->first()->title;
        }

        $result[] = 'Изображения';

        return $result;
    }

    /**
     * @return Collection
     */
    public function getAllAttributes(): Collection
    {
        if (!$this->attributes) {
            $this->attributes = Attribute::with('i18n')->get();
        }

        return $this->attributes;
    }

    /**
     * @param array $row
     *
     * @return IImported
     */
    public function importProduct(array $row): IImported
    {
        $bag = new ParametersBag($row);

        if (self::hasIdAndPresentInDb($row)) {
            $product = Product::find($row['ID']);
            $this->processor = new ExistentProductProcessor($product, $bag);

            $product = self::processProduct();

            return new Updated($product);
        } else {
            $product = new Product();
            $product->save();
            $this->processor = new NonExistentProductProcessor($product, $bag);

            $product = self::processProduct();

            return new Created($product);
        }
    }

    /**
     * @param array $row
     *
     * @return bool
     */
    protected function hasId(array $row): bool
    {
        return array_key_exists('ID', $row);
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    protected function idPresentInDB(int $id): bool
    {
        return Product::where('id', '=', $id)->exists();
    }

    /**
     * @param array $row
     *
     * @return bool
     */
    protected function hasIdAndPresentInDb(array $row): bool
    {
        return $this->hasId($row) && $this->idPresentInDB((int) $row['ID']);
    }

    /**
     * @return Product
     */
    protected function processProduct(): Product
    {
        $this->processor->saveFromImportData();

        return $this->processor->getProduct();
    }
}
