<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProviderIdToProductVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_product_videos', function (Blueprint $table) {
            $table->smallInteger('provider_id')->default(1);
            $table->renameColumn('youtube_id', 'video_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_product_videos', function (Blueprint $table) {
            $table->dropColumn('provider_id');
            $table->renameColumn('video_id', 'youtube_id');
        });
    }
}
