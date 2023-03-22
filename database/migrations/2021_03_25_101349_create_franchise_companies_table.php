<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->bigInteger('city_id');
            $table->boolean('status')->default(true);
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('bank_name')->comment('Название банка');
            $table->string('bank_identification_code')->comment('Банковский идентификационный код (БИК)');
            $table->string('bank_correspondent_account')->comment('Корреспондентский счёт');
            $table->string('taxpayer_identification_number')->comment('ИНН');
            $table->string('tax_registration_reason_code')->comment('КПП');
            $table->string('account')->nullable()->comment('Р/с');
            $table->boolean('show_cashback');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('franchise_companies');
    }
}
