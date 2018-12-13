<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->text('EventName')->nullable();
            $table->text('detail')->nullable();
            $table->text('location')->nullable();
            $table->text('provinces')->nullable();
            $table->text('amphurs')->nullable();
            $table->date('dateClose')->nullable();
            $table->date('dateDeadline')->nullable();
            $table->date('dateRace')->nullable();
            $table->string('type',50)->nullable();
            $table->text('rule')->nullable();            
            $table->text('reward')->nullable();
            $table->text('youtube')->nullable();
            // $table->text('payment')->nullable();
            $table->string('status',10)->nullable();
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
        Schema::dropIfExists('events');
    }
}
