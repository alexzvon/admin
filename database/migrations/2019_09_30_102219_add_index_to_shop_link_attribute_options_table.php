<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddIndexToShopLinkAttributeOptionsTable
 */
class AddIndexToShopLinkAttributeOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_link_attribute_options', function (Blueprint $table) {
            $table->index('attribute_relation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_link_attribute_options', function (Blueprint $table) {
            $table->dropIndex('attribute_relation_id');
        });
    }
}
