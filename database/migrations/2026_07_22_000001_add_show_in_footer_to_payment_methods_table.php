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
        if (Schema::hasTable('payment_methods') && !Schema::hasColumn('payment_methods', 'show_in_footer')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->boolean('show_in_footer')->default(true)->after('is_enabled');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payment_methods') && Schema::hasColumn('payment_methods', 'show_in_footer')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->dropColumn('show_in_footer');
            });
        }
    }
};
