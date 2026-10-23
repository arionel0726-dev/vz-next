<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();

            // Shared across locales, same as legacy brands.title/uri.
            $table->string('title');
            $table->string('slug')->unique();

            $table->string('image')->nullable();

            // Per-locale fields stored as JSON, e.g. {"ro": "...", "ru": "..."}.
            $table->json('h1_title')->nullable();
            $table->json('breadcrumb_title')->nullable();
            $table->json('description')->nullable();
            $table->json('seo_title')->nullable();
            $table->json('seo_keywords')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('seo_text')->nullable();

            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
