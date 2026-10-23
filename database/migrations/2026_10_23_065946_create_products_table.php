<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->nullOnDelete();

            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->string('search_tag')->nullable();

            // Per-locale fields, e.g. {"ro": "...", "ru": "..."}.
            $table->json('title');
            $table->json('h1_title')->nullable();
            $table->json('breadcrumb_title')->nullable();
            $table->json('description')->nullable();
            $table->json('text')->nullable();
            $table->json('slug');
            $table->json('instruction')->nullable();
            $table->json('components')->nullable();
            $table->json('seo_title')->nullable();
            $table->json('seo_keywords')->nullable();
            $table->json('seo_description')->nullable();

            // Retail price/stock.
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('stock')->default(0);

            // Wholesale (B2B) price/stock, kept alongside retail as in legacy.
            $table->decimal('wholesale_price', 10, 2)->default(0);
            $table->unsignedInteger('wholesale_stock')->default(0);

            $table->decimal('discount_price', 10, 2)->nullable();
            $table->unsignedTinyInteger('sale_percent')->nullable();

            $table->unsignedTinyInteger('sex')->nullable();
            $table->string('volume')->nullable();

            $table->boolean('is_new')->default(false);
            $table->boolean('is_best_selling')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
