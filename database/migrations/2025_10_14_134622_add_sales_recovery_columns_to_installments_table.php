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
        Schema::table('installments', function (Blueprint $table) {
            $table->unsignedBigInteger('recovery_man_id')->nullable()->after('booking_id');
            $table->unsignedBigInteger('sale_recovery_id')->nullable()->after('recovery_man_id');
            $table->foreign('recovery_man_id')->references('id')->on('accounts')->nullOnDelete();
            $table->boolean('is_approve')->default(0)->after('sale_recovery_id');
            $table->foreign('sale_recovery_id')->references('id')->on('sale_recoveries')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installments', function (Blueprint $table) {
            $table->dropForeign(['recovery_man_id']);
            $table->dropForeign(['sale_recovery_id']);
            $table->dropColumn(['recovery_man_id', 'sale_recovery_id', 'is_approve']);
        });
    }
};
