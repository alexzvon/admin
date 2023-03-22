<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopQuickFilterI18nTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_quick_filter_i18n', function (Blueprint $table) {
            $table->integer('quick_filter_id')->unsigned();
            $table->string('language_code')->default('ru');
            $table->string('title');

            $table->foreign('quick_filter_id')->references('id')->on('shop_quick_filter');

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
        Schema::dropIfExists('shop_quick_filter_i18n');
    }
}
