<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recovery_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('sale_recovery_id');
            $table->unsignedBigInteger('recovery_man_id');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->foreign('sale_recovery_id')->references('id')->on('sale_recoveries')->onDelete('cascade');
            $table->foreign('recovery_man_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recovery_payment');
    }
};
