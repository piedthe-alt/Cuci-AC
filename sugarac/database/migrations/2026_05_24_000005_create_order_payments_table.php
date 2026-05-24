<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->decimal('total_amount', 12, 2); // Total yang harus dibayar
            $table->decimal('amount_paid', 12, 2)->default(0); // Jumlah yang sudah dibayar
            $table->enum('payment_method', ['cash', 'transfer'])->nullable(); // Metode pembayaran
            $table->enum('status', ['pending', 'confirmed', 'completed'])->default('pending'); // Status pembayaran
            $table->string('bank_name')->nullable(); // Nama bank untuk transfer
            $table->string('account_number')->nullable(); // Nomor rekening untuk transfer
            $table->string('account_holder')->nullable(); // Nama pemilik rekening
            $table->text('payment_notes')->nullable(); // Catatan pembayaran
            $table->dateTime('paid_at')->nullable(); // Waktu pembayaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
