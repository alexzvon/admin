<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopQuickFilterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_quick_filter', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('adress');
            $table->boolean('status')->default(true);

            $table->foreign('category_id')->references('id')->on('shop_categories');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_quick_filter');
    }
}
