<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('elevator_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('elevator_id');
            $table->integer('current_floor');
            $table->integer('target_floor');
            $table->integer('priority')->default(1);
            $table->enum('status', ['waiting', 'in-progress', 'completed', 'cancelled']);
            $table->timestamps();
            $table->foreign('elevator_id')->references('id')->on('elevators');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elevators_reservations');
    }
};
