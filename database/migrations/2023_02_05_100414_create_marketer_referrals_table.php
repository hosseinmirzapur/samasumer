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
        Schema::create('marketer_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_id')->constrained('marketers')->cascadeOnDelete();
            $table->unsignedBigInteger('child_id');
            $table->string('child_type');
            $table->string('profit_percent')->default(0);
            $table->string('profit_amount')->default(0);
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
        Schema::dropIfExists('marketer_referrals');
    }
};
