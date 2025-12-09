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
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('designation', 50)->nullable()->after('balance');
            $table->string('wage_type', 50)->nullable()->after('designation');
            $table->integer('wage')->nullable()->after('wage_type');
            $table->string('company', 255)->nullable()->after('wage');
            $table->string('contact_person', 20)->nullable()->after('company');
            $table->string('contact_company', 20)->nullable()->after('contact_person');
        });
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
