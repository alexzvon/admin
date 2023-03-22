<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopReturnStatusesI18nTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('tables.ReturnStatusesI18n'), function (Blueprint $table) {
            $table->integer('return_status_id')->unsigned()->index();
            $table->foreign('return_status_id')->references('id')->on(config('tables.ReturnStatuses'))->onDelete('cascade');

            $table->char('language_code', 2);
            $table->foreign('language_code')->references('code')->on(config('tables.Languages'));

            $table->string('name');

            $table->primary(['return_status_id', 'language_code'])->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('tables.ReturnStatusesI18n'));
    }
}
