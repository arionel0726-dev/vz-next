<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Card badges beyond is_new/is_best_selling: a time-limited
            // deal flag, "comes with a free gift" flag, and a
            // store-exclusive flag.
            $table->boolean('is_flash')->default(false)->after('is_best_selling');
            $table->boolean('is_gift')->default(false)->after('is_flash');
            $table->boolean('is_exclusive')->default(false)->after('is_gift');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_flash', 'is_gift', 'is_exclusive']);
        });
    }
};
