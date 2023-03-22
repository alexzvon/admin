<?php

use Illuminate\Database\Seeder;

class PricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return false;

        echo "Seeding Prices\r\n";

        $productsTable = DB::table(Config::get('migrations.Products'));
        $priceTypesTable = DB::table(Config::get('migrations.PriceTypes'));
        $pricesTable = DB::table(Config::get('migrations.Prices'));

        foreach ($productsTable->get() as $product) {
            $defaultPrice = random_int(5, 50);

            foreach ($priceTypesTable->get() as $priceType) {
                $price = $defaultPrice;

                $data = [
                    'item_type' => 'product',
                    'item_id' => $product->id,
                    'price_type_id' => $priceType->id,
                    'currency_code' => 'RUB',
                    'created_at' => Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon\Carbon::now()->toDateTimeString()
                ];

                if ($priceType->name === 'old-price') {
                    if (random_int(0, 1)) {
                        continue;
                    }

                    $price += random_int(round($price / 10), round($price / 5));
                }

                if ($priceType->name === 'franchisee-price') {
                    $price -= round($price / 30);
                }

                if ($priceType->name === 'purchase-price') {
                    $price -= round($price / 10);
                }

                $data['value'] = round($price) * 1000 * 1000;

                $pricesTable->insert($data);
            }
        }
    }
}

