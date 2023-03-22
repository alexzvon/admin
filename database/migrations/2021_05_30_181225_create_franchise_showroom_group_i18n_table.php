<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseShowroomGroupI18nTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_showroom_group_i18n', function (Blueprint $table) {
            $table->bigInteger('group_id');
            $table->string('language_code')->default('ru');
            $table->string('name');
            $table->string('name_vin');
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('franchise_showroom_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_showroom_group_i18n');
    }
}
