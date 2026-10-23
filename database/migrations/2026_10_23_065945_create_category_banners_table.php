<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_banners', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            // Per-locale fields, e.g. {"ro": "...", "ru": "..."}.
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->json('slug')->nullable();

            $table->string('image');
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_banners');
    }
};
