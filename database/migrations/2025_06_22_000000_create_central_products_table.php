<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentralProductsTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql')->create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->string('picture')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mysql')->dropIfExists('products');
    }
}
