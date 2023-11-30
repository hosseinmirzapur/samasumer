<?php

use App\Models\Agency;
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
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->string('password');
            $table->string('referral_code');
            $table->foreignId('parent_id')->nullable()->constrained('agencies')->cascadeOnDelete();
            $table->enum('status', Agency::STATUS)->default('PENDING');
            $table->enum('type', Agency::TYPE);
            $table->string('promotion_percent')->default(0);
            $table->string('promotion_amount')->default(0);
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
        Schema::dropIfExists('agencies');
    }
};
