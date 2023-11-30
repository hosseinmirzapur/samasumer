<?php

use App\Models\Transaction;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('amount');
            $table->uuid('code');
            $table->string('gateway')->nullable();
            $table->text('description')->nullable();
            $table->string('tx_id')->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->string('owner_type');
            $table->enum('payment_type', Transaction::TYPES);
            $table->enum('status', Transaction::STATUS)->default('PENDING');
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
        Schema::dropIfExists('transactions');
    }
};
