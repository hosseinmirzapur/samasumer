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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('time_start')->nullable();
            $table->string('time_end')->nullable();
            $table->boolean('has_breakfast')->nullable();
            $table->boolean('has_lunch')->nullable();
            $table->boolean('has_dinner')->nullable();
            $table->text('address')->nullable();
            $table->string('policy')->nullable();
            $table->text('description')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('hotels');
    }
};
