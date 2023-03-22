<?php

use App\Models\Shop\Product\Product;
use App\Models\Shop\Supplier\Supplier;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProductSupplierTable
 */
class CreateProductSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_supplier', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->integer('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('id')->on(config('tables.Products'))->onDelete('cascade');

            $table->integer('supplier_id')->unsigned()->index();
            $table->foreign('supplier_id')->references('id')->on(config('tables.Suppliers'))->onDelete('cascade');

            $table->primary(['product_id', 'supplier_id']);
        });

        Product::chunk(200, function ($products) {
            foreach ($products as $product) {
                try {
                    $supplier = Supplier::findOrFail($product->supplier_id);

                    $product->suppliers()->attach($product->supplier_id);
                } catch (\Throwable $exception) {
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_supplier');
    }
}
