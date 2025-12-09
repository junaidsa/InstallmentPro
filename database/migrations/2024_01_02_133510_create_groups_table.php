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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('contact', 15);
            $table->string('city', 15);
            $table->string('address', 50);
            $table->integer('status');
            $table->string('logo')->nullable();
            $table->json('settings')->nullable();
            $table->dateTime('trial_expiry')->nullable();
            $table->integer('fee')->nullable();
            $table->string('billing_cycle', 10)->nullable();
            $table->dateTime('paid_till')->nullable();
            $table->dateTime('next_payments')->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
