<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopPaymentsTinkoffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        echo "Create TinkoffPayments Table\r\n";

        Schema::create(config('tables.TinkoffPayments'), function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('tinkoff_payment_id');
            $table->integer('amount')->unsigned();
            $table->string('currency_code');
            $table->string('status');

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
        Schema::dropIfExists(config('tables.TinkoffPayments'));
    }
}
