<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('max_unpaid_admin_fee')->default(50000)->after('order_admin_fee_fixed');
        });

        Schema::create('seller_fee_settlements', function (Blueprint $table) {
            $table->id();
            $table->string('settlement_number', 40)->unique();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->unsignedInteger('orders_count');
            $table->string('status', 24)->default('pending_verification');
            $table->string('payment_proof_path');
            $table->text('payment_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['seller_id', 'status']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('fee_settlement_id')
                ->nullable()
                ->after('seller_net_amount')
                ->constrained('seller_fee_settlements')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('fee_settlement_id');
        });

        Schema::dropIfExists('seller_fee_settlements');

        Schema::table('platform_settings', function (Blueprint $table) {
            $table->dropColumn('max_unpaid_admin_fee');
        });
    }
};
