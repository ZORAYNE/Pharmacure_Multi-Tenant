<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateUsersTable extends Migration
   {
       public function up()
       {
           Schema::connection('tenant')->create('users', function (Blueprint $table) {
               $table->id();
               $table->string('name');
               $table->string('email')->unique();
               $table->string('role')->default('admin');
               $table->timestamp('email_verified_at')->nullable();
               $table->string('password');
               $table->rememberToken();
               $table->timestamps();
           });
       }

       public function down()
       {
           Schema::connection('tenant')->dropIfExists('users');
       }
   }
