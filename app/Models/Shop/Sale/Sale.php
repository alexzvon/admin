<?php

namespace App\Models\Shop\Sale;

use App\Support\Traits\Models\StatusChangeable;
use MosseboShopCore\Models\Shop\Sale\Sale as BaseSale;
use App\Support\Traits\Models\RequestSaver;
use App\Models\Shop\Price\Price;

class Sale extends BaseSale
{
    use RequestSaver {
        saveFromRequestData as saveFromRequestDataTrait;
    }

    use StatusChangeable;

    protected $needsToSaveFromRequest = [
        'prices',
    ];

    public function saveFromRequestData(array $data): self
    {
        if (isset($data['product_id'])) {
            $data['item_id'] = $data['product_id'];
            $data['item_type'] = 'product';
        }

        return $this->saveFromRequestDataTrait($data);
    }

    protected function _savePrices(array $pricesData = [])
    {
        // todo: Может быть не только товар
        $product = $this->item()->first();

        if ($product) {
            $prices = $product->prices();

            foreach ($pricesData as $priceTypeId => $pricesByCurrencies) {
                $prices->where('price_type_id', $priceTypeId)->delete();

                foreach ($pricesByCurrencies as $currencyCode => $value) {
                    $prices->save(new Price($pricesToSave[] = [
                        'price_type_id' => $priceTypeId,
                        'currency_code' => $currencyCode,
                        'value'         => $value,
                    ]));
                }
            }
        }
    }
}
