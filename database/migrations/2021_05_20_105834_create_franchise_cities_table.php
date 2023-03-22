<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('country_code', 2);
            $table->integer('regions_id')->nullable();
            $table->integer('regions_parent_id')->nullable();
            $table->integer('cities_id');
            $table->bigInteger('gmt_id');
            $table->float('percent')->default(0.0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('country_code')->references('code')->on('countries');
            $table->foreign('regions_id')->references('id')->on('regions');
            $table->foreign('regions_parent_id')->references('id')->on('regions');
            $table->foreign('cities_id')->references('id')->on('cities');
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
        Schema::dropIfExists('franchise_cities');
    }
}
