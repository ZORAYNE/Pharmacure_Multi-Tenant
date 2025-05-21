<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropProductIdAndQuantitySoldFromSalesTable extends Migration
{
    public function up()
    {
        // Disabled migration to prevent dropping columns
        /*
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('sales', 'quantity_sold')) {
                $table->dropColumn('quantity_sold');
            }
        });
        */
    }

    public function down()
    {
        // Disabled migration to prevent restoring columns
        /*
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('id');
            $table->integer('quantity_sold')->after('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
        */
    }
}
