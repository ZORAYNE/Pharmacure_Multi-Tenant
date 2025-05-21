<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForceDropProductIdAndQuantitySoldFromSalesTable extends Migration
{
    public function up()
    {
        // Disabled migration to prevent dropping columns
        /*
        Schema::connection('tenant')->table('sales', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            $table->dropColumn('quantity_sold');
        });
        */
    }

    public function down()
    {
        // Disabled migration to prevent restoring columns
        /*
        Schema::connection('tenant')->table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('id');
            $table->integer('quantity_sold')->after('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
        */
    }
}
