<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
             $table->string('picture')->nullable();
            $table->string('name');
            $table->string('brand');
            $table->decimal('price', 8, 2);
            $table->integer('stock_quantity');
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
