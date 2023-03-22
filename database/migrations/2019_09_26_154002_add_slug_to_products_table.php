<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->default(NULL);
        });
    }
}
