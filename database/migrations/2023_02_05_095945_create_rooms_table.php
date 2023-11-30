<?php

use App\Models\Room;
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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->enum('type', Room::TYPE);
            $table->string('title');
            $table->integer('room_count');
            $table->integer('bed_count');
            $table->integer('capacity');
            $table->integer('daily_price');
            $table->text('refund_policy');
            $table->text('description')->nullable();
            $table->string('discount')->default(0);
            $table->integer('available_count');
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->enum('status', Room::STATUS)->default('PENDING');
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
        Schema::dropIfExists('rooms');
    }
};
