<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('sku')->unique();

            // Per-locale variant title/color label, e.g. {"ro": "...", "ru": "..."}.
            $table->json('title')->nullable();

            // Legacy products_variable.VolumeVar.
            $table->string('volume')->nullable();

            // Legacy products_variable.color_data — list of swatch HEX colors.
            $table->json('color_hex_list')->nullable();

            // Retail price/stock.
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('stock')->default(0);

            // Wholesale (B2B) price/stock.
            $table->decimal('wholesale_price', 10, 2)->default(0);
            $table->unsignedInteger('wholesale_stock')->default(0);

            $table->decimal('discount_price', 10, 2)->nullable();

            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
