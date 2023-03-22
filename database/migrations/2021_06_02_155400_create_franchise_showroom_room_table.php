<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseShowroomRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_showroom_room', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('group_id');
            $table->bigInteger('cities_id');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->double('lat')->default(0.0);
            $table->double('lon')->default(0.0);
            $table->string('video_youtube')->nullable();;
            $table->string('video_vimeo')->nullable();;
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('franchise_showroom_group');
            $table->foreign('cities_id')->references('id')->on('franchise_cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_showroom_room');
    }
}
