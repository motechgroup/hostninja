<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        Schema::create('reseller_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('service_name');
            $table->decimal('sale_amount', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->string('status')->default('paid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseller_commissions');
        Schema::dropIfExists('settings');
    }
};
