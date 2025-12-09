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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'recovery_man_id')) {
                $table->unsignedBigInteger('recovery_man_id')->nullable()->after('account_id');
                $table->foreign('recovery_man_id')
                    ->references('id')
                    ->on('accounts')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            try {
                $table->dropForeign(['recovery_man_id']);
            } catch (\Exception $e) {
            }
            if (Schema::hasColumn('bookings', 'recovery_man_id')) {
                $table->dropColumn('recovery_man_id');
            }
        });
    }
};
