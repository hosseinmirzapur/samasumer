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
        Schema::create('marketer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_id')->constrained('marketers')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('card_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('national_card')->nullable();
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
        Schema::dropIfExists('marketer_profiles');
    }
};
