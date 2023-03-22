<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseIncomeI18nTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_income_i18n', function (Blueprint $table) {
            $table->bigInteger('income_id');
            $table->string('language_code')->default('ru');
            $table->string('title');
            $table->timestamps();

            $table->foreign('income_id')->references('id')->on('franchise_income');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_income_i18n');
    }
}
