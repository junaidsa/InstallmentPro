<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('station_id')->nullable();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('installment_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->enum('payment_mode', ['cash', 'bank', 'cheque', 'online'])->default('cash');
            $table->enum('type', ['credit', 'debit'])->default('credit');
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('remaining', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->default(0);
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('set null');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('installment_id')->references('id')->on('installments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
