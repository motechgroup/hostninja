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
        if (Schema::hasTable('payment_methods') && !Schema::hasColumn('payment_methods', 'credentials')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->text('credentials')->nullable()->after('show_in_footer');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payment_methods') && Schema::hasColumn('payment_methods', 'credentials')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->dropColumn('credentials');
            });
        }
    }
};
