<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddDisplayedNameToShopQuickFilterI18n extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_quick_filter_i18n', function (Blueprint $table) {
            $table->string('displayed_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_quick_filter_i18n', function (Blueprint $table) {
            $table->dropColumn(['displayed_name']);
        });
    }
}
