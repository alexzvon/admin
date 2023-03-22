<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseShowroomRoomI18nTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_showroom_room_i18n', function (Blueprint $table) {
            $table->bigInteger('room_id');
            $table->string('language_code')->default('ru');
            $table->string('name');
            $table->string('address');
            $table->string('work_time');
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('franchise_showroom_room');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_showroom_room_i18n');
    }
}
