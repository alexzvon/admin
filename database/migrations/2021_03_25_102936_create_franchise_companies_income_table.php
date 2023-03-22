<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseCompaniesIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_companies_income', function (Blueprint $table) {
            $table->bigInteger('companies_id');
            $table->bigInteger('income_id');
            $table->float('percent');

            $table->primary(['companies_id', 'income_id']);
            $table->foreign('companies_id')->references('id')->on('franchise_companies');
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
        Schema::dropIfExists('franchise_companies_income');
    }
}
