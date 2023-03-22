<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddSomeFieldsToShopProductsTable
 */
class AddSomeFieldsToShopProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->integer('delivery_time')->nullable();
            $table->integer('production_time')->nullable();
            $table->boolean('available')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropColumn('delivery_time');
            $table->dropColumn('production_time');
            $table->dropColumn('available');
        });
    }
}
