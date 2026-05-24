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
        Schema::create('add_ons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama add-on (Pipa AC, Freon, dll)
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2); // Harga per unit/item
            $table->string('unit')->default('pcs'); // Satuan (pcs, liter, meter, dll)
            $table->integer('stock')->default(0)->nullable(); // Stok yang tersedia
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_ons');
    }
};
