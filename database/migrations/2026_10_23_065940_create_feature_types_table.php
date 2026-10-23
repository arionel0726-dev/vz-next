<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_types', function (Blueprint $table) {
            $table->id();

            // Plain string: legacy shop_type.name has no per-locale columns.
            $table->string('name');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_types');
    }
};
