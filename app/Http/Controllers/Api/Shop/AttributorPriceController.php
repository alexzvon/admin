<?php namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Attribute\LinkAttributeRelationOption;
use App\Models\Shop\PriceType\PriceType;
use App\Models\Shop\Price\Price;
use MosseboShopCore\Shop\Price as ShopPrice;

class AttributorPriceController extends Controller
{
    protected $attributor;


    public function create()
    {
        $model = Price::create(request()->except('api_token'));

        return response()->json([
            'status' => 'success',
            'price' => Price::find($model->id)
        ]);
    }


    public function update()
    {
        $updated = Price::where('id', request('id'))->update([
            "value" => (int) request('value')*100
        ]);

        return response()->json([
            'status' => 'success',
            'updated' => $updated,
            'price' => Price::find(request('id'))
        ]);
    }


    /**
     * Получить все цены для конкретного значения свойства
     *
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getPricesByAttributorId($id)
    {
        $this->getPriceTypes();

        $this->attributor = AttributorController::getOptionById($id);
        $this->attributor->load('prices');
        $this->attributor->load('productPrices');

        return collect($this->priceTypes)
            ->map(function($priceType)
            {
                $prices = [];

                $price = $this->attributor->prices
                    ->where('price_type_id', $priceType->id)
                    ->first();

                $prices[] = $price;

                if ($this->attributor->option_product_id) {
                    $productPrice = $this->attributor->productPrices
                        ->where('price_type_id', $priceType->id)
                        ->first();

                    $prices[] = $productPrice;
                }

                $currentPrice = $this->selectFirstAvailablePrice($prices);

                $price = $currentPrice
                    ? $currentPrice
                    : $this->getEmptyPrice($priceType->id);

                return $price;

            });
    }


    /**
     * Выбираем первый корректный прайс из списка переданных
     */
    protected function selectFirstAvailablePrice($prices)
    {
        foreach($prices as $price) {
            if ($price) {
                return $price;
            }
        }
        return false;
    }


    // /**
    //  * Преобразование цены, хранящейся в Integer, в float
    //  *
    //  * @param int $price
    //  * @return float
    //  */
    // protected function priceIntegerToFloat(int $price) : float
    // {
    //     return ($price > 0) ? (float) $price : 0;
    // }


    /**
     * @param int $type_id
     * @return \App\Models\Shop\Price\Price
     */
    protected function getEmptyPrice($type_id)
    {
        $price = new Price();
        $price->item_type = LinkAttributeRelationOption::PRICE_ITEM_TYPE;
        $price->item_id = $this->attributor->id;
        $price->currency_code = 'RUB';
        $price->price_type_id = $type_id;
        $price->value = 0;

        return $price;
    }


    protected function getPriceTypes()
    {
        $this->priceTypes = PriceType::where('enabled', true)
            ->orderBy('position', 'asc')
            ->get();
    }


}