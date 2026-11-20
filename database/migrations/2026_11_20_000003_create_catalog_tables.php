<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->string('short_code', 16);
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->restrictOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->string('short_description', 255);
            $table->longText('description');
            $table->string('pricing_type', 16)->default('free');
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('promo_price')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('mod_version', 40);
            $table->string('compatible_game_version', 80);
            $table->string('dependencies', 255)->nullable();
            $table->text('install_guide')->nullable();
            $table->text('changelog')->nullable();
            $table->string('file_path');
            $table->string('file_original_name');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('file_checksum', 64)->nullable();
            $table->string('status', 24)->default('pending_review');
            $table->text('rejection_reason')->nullable();
            $table->unsignedInteger('downloads_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'pricing_type']);
            $table->index(['game_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index(['seller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('games');
    }
};
