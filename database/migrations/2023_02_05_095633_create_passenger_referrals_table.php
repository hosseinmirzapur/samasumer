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
        Schema::create('passenger_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained('passengers')->cascadeOnDelete();
            $table->foreignId('referral_id')->constrained('passengers');
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
        Schema::dropIfExists('passenger_referrals');
    }
};
