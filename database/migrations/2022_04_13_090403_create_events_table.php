<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 255); 
            $table->longText('description')->nullable();
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->boolean('fullDay')->default(0);
            $table->boolean('recurring')->default(0);
            $table->dateTime('endRecurrence')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('todo_id')->nullable();
            $table->unsignedBigInteger('recurringPatern_id')->nullable();
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
};
