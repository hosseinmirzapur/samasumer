<?php

use App\Models\PassengerProfile;
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
        Schema::create('passenger_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained('passengers')->cascadeOnDelete();
            $table->string('persian_name')->nullable();
            $table->string('persian_lastname')->nullable();
            $table->string('english_name')->nullable();
            $table->string('english_lastname')->nullable();
            $table->string('nat_id')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('email')->nullable();
            $table->enum('gender', PassengerProfile::GENDERS)->nullable();
            $table->string('card_number')->nullable();
            $table->string('iban')->nullable();
            $table->enum('card_verified', PassengerProfile::CARD_STATUS)->default('ACCEPTED');
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
        Schema::dropIfExists('passenger_profiles');
    }
};
