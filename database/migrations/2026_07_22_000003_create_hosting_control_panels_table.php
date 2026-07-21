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
        if (!Schema::hasTable('hosting_control_panels')) {
            Schema::create('hosting_control_panels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('logo')->nullable();
                $table->text('description')->nullable();
                $table->string('official_url')->nullable();
                $table->boolean('featured')->default(false);
                $table->boolean('enabled')->default(true);
                $table->integer('display_order')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting_control_panels');
    }
};
