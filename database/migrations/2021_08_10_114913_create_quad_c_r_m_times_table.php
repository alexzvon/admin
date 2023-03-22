<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Services\Shop\QuadCRMTimesService;

class CreateQuadCRMTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quad_c_r_m_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->nullable();
            $table->time('time');
            $table->enum('status', [
                QuadCRMTimesService::TIME_FOR_EXPORT,
                QuadCRMTimesService::TIME_FOR_IMPORT
            ])->unique();

            $table->foreign('admin_id')->references('id')->on('admins');

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
        Schema::dropIfExists('quad_c_r_m_times');
    }
}
