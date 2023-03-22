<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseCompaniesI18nTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_companies_i18n', function (Blueprint $table) {
            $table->bigInteger('companies_id');
            $table->string('language_code')->default('ru');
            $table->string('title');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('companies_id')->references('id')->on('franchise_companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_companies_i18n');
    }
}
