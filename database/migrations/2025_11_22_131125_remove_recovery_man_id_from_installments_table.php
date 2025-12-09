<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('installments', function (Blueprint $table) {
        if (Schema::hasColumn('installments', 'recovery_man_id')) {
            try {
                DB::statement('ALTER TABLE installments DROP FOREIGN KEY installments_recovery_man_id_foreign');
            } catch (\Exception $e) {
            }
            $table->dropColumn('recovery_man_id');
        }
    });
    }

    public function down(): void
    {
        Schema::table('installments', function (Blueprint $table) {
            if (!Schema::hasColumn('installments', 'recovery_man_id')) {
                $table->unsignedBigInteger('recovery_man_id')->nullable();
                $table->foreign('recovery_man_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            }
        });
    }
};
