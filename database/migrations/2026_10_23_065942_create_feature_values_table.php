<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('feature_id')
                ->constrained('features')
                ->cascadeOnDelete();

            // Per-locale name, e.g. {"ro": "...", "ru": "..."}.
            $table->json('name');

            // Legacy shop_feature_values.color.
            $table->string('color_hex')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_values');
    }
};
