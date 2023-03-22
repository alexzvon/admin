<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_city', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('country_id');
            $table->bigInteger('gmt_id');
            $table->float('percent')->default(0.0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('franchise_country');
            $table->foreign('gmt_id')->references('id')->on('franchise_gmt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_city');
    }
}
