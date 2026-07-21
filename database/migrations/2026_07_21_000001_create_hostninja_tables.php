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
        // Add HostNinja columns to users table if missing
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('customer'); // customer, admin, reseller, support_agent
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'company')) {
                $table->string('company')->nullable();
            }
            if (!Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false);
            }
            if (!Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 10, 2)->default(0.00);
            }
        });

        // Domains
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('domain_name');
            $table->string('extension');
            $table->date('registration_date');
            $table->date('expiry_date');
            $table->string('status')->default('active'); // active, pending, expired, transferred
            $table->string('registrar')->default('HostNinja Registrar');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('auto_renew')->default(true);
            $table->boolean('is_locked')->default(true);
            $table->json('nameservers')->nullable();
            $table->json('dns_records')->nullable();
            $table->timestamps();
        });

        // Domain Orders
        Schema::create('domain_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('domain_name');
            $table->string('action_type')->default('register'); // register, transfer, renew
            $table->integer('years')->default(1);
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('completed');
            $table->timestamps();
        });

        // Hosting Plans
        Schema::create('hosting_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->decimal('price_monthly', 10, 2);
            $table->decimal('price_yearly', 10, 2);
            $table->integer('storage_gb');
            $table->integer('bandwidth_gb');
            $table->integer('email_accounts');
            $table->integer('databases');
            $table->boolean('ssl_free')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Servers
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address');
            $table->string('hostname');
            $table->string('type')->default('cPanel'); // cPanel, Custom, DirectAdmin
            $table->string('status')->default('online'); // online, maintenance, offline
            $table->integer('active_accounts')->default(0);
            $table->integer('max_accounts')->default(500);
            $table->integer('disk_usage_percent')->default(25);
            $table->integer('cpu_usage_percent')->default(15);
            $table->timestamps();
        });

        // Hosting Services
        Schema::create('hosting_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hosting_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('server_id')->nullable()->constrained()->onDelete('set null');
            $table->string('domain_name');
            $table->string('username')->nullable();
            $table->string('status')->default('active'); // active, pending, suspended, cancelled
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly
            $table->decimal('amount', 10, 2);
            $table->date('next_due_date');
            $table->timestamps();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('pending'); // paid, pending, overdue, cancelled
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payment_method'); // mpesa, airtel_money, stripe, paypal
            $table->string('transaction_reference');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('completed');
            $table->timestamps();
        });

        // Tickets
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ticket_number')->unique();
            $table->string('subject');
            $table->string('category')->default('technical'); // technical, billing, domain, account
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('open'); // open, answered, customer_reply, closed
            $table->foreignId('assigned_to')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });

        // Ticket Messages
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->boolean('is_internal')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('hosting_services');
        Schema::dropIfExists('servers');
        Schema::dropIfExists('hosting_plans');
        Schema::dropIfExists('domain_orders');
        Schema::dropIfExists('domains');
    }
};
