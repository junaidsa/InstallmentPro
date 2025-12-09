<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sale_recoveries', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->default(0)->after('recovery_man_id');
            $table->decimal('remaining_amount', 15, 2)->default(0)->after('amount');
        });
    }

    public function down()
    {
        Schema::table('sale_recoveries', function (Blueprint $table) {
            $table->dropColumn(['amount', 'remaining_amount']);
        });
    }
};
