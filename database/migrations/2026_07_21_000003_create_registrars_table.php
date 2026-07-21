<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('enabled')->default(false);
            $table->boolean('default')->default(false);
            $table->boolean('sandbox')->default(true);
            $table->text('credentials')->nullable(); // Encrypted JSON
            $table->string('endpoint')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->json('supported_features')->nullable();
            $table->timestamp('last_connection')->nullable();
            $table->timestamp('last_sync')->nullable();
            $table->timestamps();
        });

        Schema::create('registrar_api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registrar_id')->nullable()->constrained('registrars')->onDelete('set null');
            $table->string('driver');
            $table->string('action');
            $table->string('endpoint')->nullable();
            $table->text('request_payload')->nullable();
            $table->text('response_payload')->nullable();
            $table->integer('http_status')->default(200);
            $table->integer('execution_time_ms')->default(0);
            $table->text('error')->nullable();
            $table->integer('retries')->default(0);
            $table->timestamps();
        });

        Schema::table('domains', function (Blueprint $table) {
            if (!Schema::hasColumn('domains', 'registrar_id')) {
                $table->foreignId('registrar_id')->nullable()->constrained('registrars')->onDelete('set null');
            }
            if (!Schema::hasColumn('domains', 'registrar_domain_id')) {
                $table->string('registrar_domain_id')->nullable();
            }
            if (!Schema::hasColumn('domains', 'whois_privacy_enabled')) {
                $table->boolean('whois_privacy_enabled')->default(false);
            }
            if (!Schema::hasColumn('domains', 'dnssec_enabled')) {
                $table->boolean('dnssec_enabled')->default(false);
            }
            if (!Schema::hasColumn('domains', 'glue_records')) {
                $table->json('glue_records')->nullable();
            }
            if (!Schema::hasColumn('domains', 'whois_info')) {
                $table->json('whois_info')->nullable();
            }
            if (!Schema::hasColumn('domains', 'last_synced_at')) {
                $table->timestamp('last_synced_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrar_api_logs');
        Schema::dropIfExists('registrars');
    }
};
