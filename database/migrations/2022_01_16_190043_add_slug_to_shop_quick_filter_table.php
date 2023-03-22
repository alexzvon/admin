<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToShopQuickFilterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_quick_filter', function (Blueprint $table) {
            $table->string('slug')->nullable()->index();
        });

        Artisan::call('db:seed', [
            '--class' => QuickFiltersSlugColumnSeeder::class,
            '--force' => 'true'
        ]);

        Schema::table('shop_quick_filter', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_quick_filter', function (Blueprint $table) {
            $table->dropColumn(['slug']);
        });
    }
}
