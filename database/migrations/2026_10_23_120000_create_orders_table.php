<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('order_number')->unique();

            // pending -> processing -> completed, or canceled at any point.
            $table->string('status')->default('pending');

            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);

            // Snapshot of the delivery/contact details at order time —
            // independent of whatever the user later edits on their account.
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('delivery_address');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
