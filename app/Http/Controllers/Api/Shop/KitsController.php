<?php namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Shop\Kit\KitResource;
use App\Http\Resources\Shop\Kit\KitEditResource;
use App\Models\Shop\Price\Price;
use Illuminate\Http\Request;
use App\Models\Shop\Kit\Kit;

class KitsController extends ApiController
{

    protected static $modelClass = Kit::class;
    protected static $entityName = 'kit';
    protected static $editResource = KitEditResource::class;
    protected static $tableResource = KitResource::class;


    public function getKitsBySupplier($supplier_id)
    {
        return [
          'kits' => static::toResource(static::$modelClass::with('prices')->where('supplier_id', $supplier_id)->get())
        ];
    }


    public function getKitsBySupplierAndCategories()
    {
        $supplier_id = $this->request->input('supplier_id');
        $category_ids = $this->request->input('category_ids');

        $kits = static::$modelClass::with('prices')
          ->where('supplier_id', $supplier_id)
          ->whereEnabled(true)
          ->whereIn('category_id', $category_ids)
          ->get();

        return [
          'kits' => static::toResource($kits)
        ];
    }


    public function single($id)
    {
        return [
          'kit' => static::toResource(Kit::with('prices')->where('id', $id)->first())
        ];
    }


    public function create()
    {
        $kit = Kit::create([
          'supplier_id' => $this->request->input('supplier_id'),
          'category_id' => $this->request->input('category_id'),
          'name' => $this->request->input('name'),
          'pricing_effort_type_id' => $this->request->input('pricing_effort_type_id'),
        ]);

        return $this->single($kit->id);
    }


    public function update()
    {
        $kit = Kit::where('id', $this->request->input('id'))->first();

        $kit->update([
          'supplier_id' => $this->request->input('supplier_id'),
          'category_id' => $this->request->input('category_id'),
          'name' => $this->request->input('name'),
          'enabled' => $this->request->input('enabled'),
          'pricing_effort_type_id' => $this->request->input('pricing_effort_type_id'),
        ]);

        if (count($this->request->input('prices')) > 0) {
            $this->updateKitPrices($kit, $this->request->input('prices'));
        }

        return $this->single($kit->id);
    }


    protected function updateKitPrices($kit, $prices)
    {
        foreach($prices as $price) {
            if (+$price['value'] < 1) {

                Price::where('item_type', 'kit')
                  ->where('item_id', $kit->id)
                  ->where('price_type_id', $price['price_type_id'])
                  ->delete();

            } else if ($price['id'] && $price['item_type']==='kit') {

                Price::where('id', $price['id'])->update([
                  'value' => $price['value']*100
                ]);

            } else {

                Price::create([
                  'currency_code' => "RUB",
                  'item_id' => $kit->id,
                  'item_type' => 'kit',
                  'price_type_id' => $price['price_type_id'],
                  'value' => $price['value']
                ]);

            }
        }

        return $this->single($kit->id);
    }



}