<?php

use Illuminate\Database\Seeder;

class CategoryProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "Seeding CategoryProducts\r\n";

        $categoryProductsTable = DB::table(Config::get('migrations.CategoryProducts'));

        $category = DB::table(Config::get('migrations.Categories'))->first();
        $products = DB::table(Config::get('migrations.Products'))->get();

        foreach ($products as $product) {
            $categoryProductsTable->insert([
                'category_id' => $category->id,
                'product_id' => $product->id
            ]);
        }
    }
}