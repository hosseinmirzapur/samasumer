<?php

use App\Models\HotelierProfile;
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
        Schema::create('hotelier_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->string('cred_id')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('card_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('license')->nullable();
            $table->string('national_card')->nullable();
            $table->enum('card_status', HotelierProfile::CARD_STATUS)->default('ACCEPTED');
            $table->enum('hotelier_status', HotelierProfile::STATUS)->default('PENDING');
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
        Schema::dropIfExists('hotel_profiles');
    }
};
