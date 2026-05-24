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
        Schema::create('order_add_ons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('add_on_id')->constrained('add_ons')->onDelete('restrict');
            $table->integer('quantity'); // Jumlah yang digunakan
            $table->decimal('unit_price', 12, 2); // Harga per unit saat pembelian
            $table->decimal('subtotal', 12, 2); // quantity * unit_price
            $table->text('notes')->nullable(); // Catatan tentang penggunaan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_add_ons');
    }
};
