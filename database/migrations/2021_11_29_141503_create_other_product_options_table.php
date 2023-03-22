<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_product_options', function (Blueprint $table) {
            $table->integer('parent_id');
            $table->integer('children_id');

            $table->primary(['parent_id', 'children_id']);

            $table->foreign('parent_id')->references('id')->on('shop_products');
            $table->foreign('children_id')->references('id')->on('shop_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_product_options');
    }
}
