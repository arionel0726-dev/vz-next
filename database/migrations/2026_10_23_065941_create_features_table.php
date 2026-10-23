<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();

            $table->foreignId('feature_type_id')
                ->constrained('feature_types')
                ->cascadeOnDelete();

            // Per-locale name, e.g. {"ro": "...", "ru": "..."}.
            $table->json('name');

            // Legacy shop_feature.type (input type, e.g. select/checkbox).
            $table->string('input_type')->nullable();

            // Legacy shop_feature.free_values.
            $table->boolean('has_free_values')->default(false);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
