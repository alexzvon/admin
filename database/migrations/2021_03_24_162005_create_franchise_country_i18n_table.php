<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseCountryI18nTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_country_i18n', function (Blueprint $table) {
            $table->bigInteger('country_id');
            $table->string('language_code')->default('ru');
            $table->string('title');
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('franchise_country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_country_i18n');
    }
}
