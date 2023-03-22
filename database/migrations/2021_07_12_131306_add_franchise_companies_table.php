<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFranchiseCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_companies', function (Blueprint $table) {
            $column = Schema::hasColumn('franchise_companies', 'name');

            if (!$column) {
                $table->string('name')->nullable()->comment('Отображаемое имя');
            }

            $table->string('delivery_address')->nullable()->comment('Адрес доставки');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_companies', function (Blueprint $table) {
            $column = Schema::hasColumn('franchise_companies', 'name');

            if ($column) {
                $table->dropColumn('name');
            }

            $table->dropColumn('delivery_address');
        });
    }
}
