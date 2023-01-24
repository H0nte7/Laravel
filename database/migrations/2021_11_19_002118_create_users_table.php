<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('admin')->nullable();
            $table->integer('ban')->nullable();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('second_phone')->unique()->nullable(true);
            $table->string('email')->unique()->nullable(true);
            $table->string('password');
            $table->string('remember_token')->nullable(true);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('users');
    }
}
