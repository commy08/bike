<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->text('line_id')->nullable();
            $table->text('line_pic')->nullable();
            $table->text('line_token')->nullable();
            $table->string('type',10)->nullable();
            $table->string('firstname',100)->nullable();
            $table->string('lastname',100)->nullable();
            $table->string('sex',10)->nullable();
            $table->text('address')->nullable();
            $table->text('provinces')->nullable();
            $table->text('amphurs')->nullable();
            $table->date('birthday')->nullable();
            $table->text('picID')->nullable();
            $table->text('picORG')->nullable();
            $table->text('OrgName')->nullable();
            $table->text('tradeNum')->nullable();
            $table->string('tel',10)->nullable();
            $table->string('status',10)->nullable();
            $table->string('remember_token',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
