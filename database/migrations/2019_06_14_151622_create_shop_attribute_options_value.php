<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopAttributeOptionsValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_attribute_options_value', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->integer('option_id')->unsigned();
            $table->foreign('option_id')->references('id')->on(config('tables.AttributeOptions'))->onDelete('cascade');

            $table->string('type');
            $table->string('value',1024);

            $table->primary(['option_id', 'type'])->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_attribute_options_value');
    }
}
