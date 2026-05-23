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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ac_model_id')->constrained('ac_models')->onDelete('restrict');
            $table->foreignId('service_type_id')->constrained('service_types')->onDelete('restrict');
            $table->integer('units'); // Jumlah unit AC
            $table->string('phone'); // Nomor telepon aktif
            $table->string('address'); // Alamat lengkap
            $table->decimal('latitude', 10, 8)->nullable(); // Latitude untuk geolocation
            $table->decimal('longitude', 11, 8)->nullable(); // Longitude untuk geolocation
            $table->dateTime('visit_date'); // Tanggal dan waktu kunjungan
            $table->text('notes')->nullable(); // Catatan opsional
            $table->decimal('total_price', 12, 2); // Total harga
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
