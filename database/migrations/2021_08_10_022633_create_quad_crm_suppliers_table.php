<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuadCrmSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quad_crm_suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id');
            $table->boolean('status')->default(false);

            $table->foreign('supplier_id')->references('id')->on('shop_suppliers');

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
        Schema::dropIfExists('quad_crm_suppliers');
    }
}
