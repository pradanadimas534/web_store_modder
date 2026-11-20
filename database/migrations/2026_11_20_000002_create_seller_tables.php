<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('store_name', 120);
            $table->string('slug', 140)->unique();
            $table->text('bio')->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });

        Schema::create('seller_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider_name', 80);
            $table->string('account_number', 100);
            $table->string('account_holder', 120);
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_payment_methods');
        Schema::dropIfExists('seller_profiles');
    }
};
