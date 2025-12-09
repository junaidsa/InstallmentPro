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
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('is_business')->default(false)->after('status');
        });
        DB::table('accounts')->insert([
            'group_id' => 1,
            'station_id' => null,
            'name' => 'Business Account',
            'type' => 'Investor',
            'account_no' => 'BUSINESS001',
            'father_name' => null,
            'cnic' => null,
            'email' => 'business@example.com',
            'address' => 'Business Address',
            'contact' => '000000000',
            'balance' => 2250.74,
            'total_investment' => 60000.00,
            'designation' => null,
            'wage_type' => null,
            'wage' => null,
            'status' => 'active',
            'is_business' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_by' => 0,
            'created_at' => '2025-10-28 13:52:05',
            'updated_at' => '2025-11-11 01:14:05',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            //
        });
    }
};
