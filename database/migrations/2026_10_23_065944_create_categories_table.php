<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->foreignId('feature_type_id')
                ->nullable()
                ->constrained('feature_types')
                ->nullOnDelete();

            // Per-locale fields, e.g. {"ro": "...", "ru": "..."}.
            $table->json('title');
            $table->json('h1_title')->nullable();
            $table->json('breadcrumb_title')->nullable();
            $table->json('description')->nullable();
            $table->json('slug');
            $table->json('seo_title')->nullable();
            $table->json('seo_keywords')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('seo_text')->nullable();

            $table->string('image')->nullable();
            $table->string('nav_image')->nullable();
            $table->string('nav_image_hover')->nullable();
            $table->string('wallpaper')->nullable();

            // Legacy categories.step (nesting depth: 1/2/3).
            $table->unsignedTinyInteger('depth_level')->default(1);

            $table->boolean('is_top_menu')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
