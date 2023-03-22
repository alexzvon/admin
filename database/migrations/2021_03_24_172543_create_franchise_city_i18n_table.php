<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseCityI18nTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_city_i18n', function (Blueprint $table) {
            $table->bigInteger('city_id');
            $table->string('language_code')->default('ru');
            $table->string('title');
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('franchise_cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_city_i18n');
    }
}
