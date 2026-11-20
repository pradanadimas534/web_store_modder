<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('order_admin_fee_percent', 5, 2)->default(5.00);
            $table->unsignedBigInteger('order_admin_fee_fixed')->default(2000);
            $table->unsignedBigInteger('ad_price_catalog_banner')->default(50000);
            $table->unsignedBigInteger('ad_price_sidebar_promo')->default(35000);
            $table->unsignedBigInteger('ad_price_footer_strip')->default(25000);
            $table->unsignedBigInteger('ad_admin_fee_fixed')->default(5000);
            $table->string('admin_payment_provider', 80)->default('BCA');
            $table->string('admin_payment_account_number', 100)->default('0011223344');
            $table->string('admin_payment_account_holder', 120)->default('Admin ModGame.id');
            $table->text('admin_payment_instructions')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_fee')->default(0)->after('subtotal');
            $table->unsignedBigInteger('seller_net_amount')->default(0)->after('total');
        });

        Schema::table('ads', function (Blueprint $table) {
            $table->unsignedBigInteger('slot_price')->default(0)->after('placement');
            $table->unsignedBigInteger('admin_fee')->default(0)->after('slot_price');
            $table->unsignedBigInteger('total_price')->default(0)->after('admin_fee');
            $table->string('payment_status', 32)->default('unpaid')->after('total_price');
            $table->string('payment_proof_path')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_proof_path');
            $table->timestamp('verified_at')->nullable()->after('paid_at');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn([
                'slot_price',
                'admin_fee',
                'total_price',
                'payment_status',
                'payment_proof_path',
                'paid_at',
                'verified_at',
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['admin_fee', 'seller_net_amount']);
        });

        Schema::dropIfExists('platform_settings');
    }
};
