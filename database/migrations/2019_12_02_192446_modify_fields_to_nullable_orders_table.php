<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ModifyFieldsToNullableOrdersTable
 */
class ModifyFieldsToNullableOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('tables.Orders'), function (Blueprint $table) {
            $table->string('last_name')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('street')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('tables.Orders'), function (Blueprint $table) {
            $table->string('last_name')->change();
            $table->string('phone')->change();
            $table->string('city')->change();
            $table->string('street')->change();
        });
    }
}
