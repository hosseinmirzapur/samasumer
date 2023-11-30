<?php

use App\Models\Hotelier;
use App\Models\Passenger;
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
        Schema::create('hoteliers', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->string('password');
            $table->enum('blocked', Hotelier::STATUS)->default(false);
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
        Schema::dropIfExists('hoteliers');
    }
};
