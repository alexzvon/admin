<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMeasurementsForDeliveryToShopProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->integer('delivery_width')->unsigned()->nullable();
            $table->integer('delivery_height')->unsigned()->nullable();
            $table->integer('delivery_length')->unsigned()->nullable();
            $table->integer('delivery_weight')->unsigned()->nullable();
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
            $table->dropColumn('delivery_width');
            $table->dropColumn('delivery_height');
            $table->dropColumn('delivery_length');
            $table->dropColumn('delivery_weight');
        });
    }
}
