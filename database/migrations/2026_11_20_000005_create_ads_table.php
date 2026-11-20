<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertiser_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('advertiser_name', 120);
            $table->string('advertiser_email', 150);
            $table->string('title', 100);
            $table->string('description', 200);
            $table->string('cta_text', 40)->default('Kunjungi');
            $table->string('target_url', 500);
            $table->string('banner_path')->nullable();
            $table->string('placement', 32)->default('catalog_banner');
            $table->string('status', 24)->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedBigInteger('impressions_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->timestamps();

            $table->index(['placement', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
