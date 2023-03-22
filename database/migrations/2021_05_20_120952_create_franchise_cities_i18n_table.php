<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseCitiesI18nTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_cities_i18n', function (Blueprint $table) {
            $table->bigInteger('cities_id');
            $table->string('language_code')->default('ru');
            $table->text('title');
            $table->timestamps();

            $table->foreign('cities_id')->references('id')->on('franchise_cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_cities_i18n');
    }
}
