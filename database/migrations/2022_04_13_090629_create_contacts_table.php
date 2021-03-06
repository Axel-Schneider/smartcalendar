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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('userRequest_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['waiting', 'accept', 'deny', 'block']);
            $table->unsignedBigInteger('blocker_id')->nullable();
            
            $table->foreign('userRequest_id')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('blocker_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
