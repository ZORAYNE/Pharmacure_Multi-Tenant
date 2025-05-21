<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixQuantitySoldInSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'quantity_sold')) {
                $table->integer('quantity_sold')->default(0)->after('product_id');
            } else {
                $table->integer('quantity_sold')->default(0)->change();
            }
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'quantity_sold')) {
                $table->integer('quantity_sold')->nullable()->change();
            }
        });
    }
}
