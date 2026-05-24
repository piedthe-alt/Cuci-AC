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
        Schema::table('orders', function (Blueprint $table) {
            // Update enum status untuk flow yang lebih detail
            $table->string('status')->default('menunggu')->change(); // Change from enum to string dengan default value
            
            // Tambah fields yang diperlukan untuk workflow (assigned_at sudah ada di migration sebelumnya)
            if (!Schema::hasColumn('orders', 'service_checked_at')) {
                $table->dateTime('service_checked_at')->nullable();
            }
            if (!Schema::hasColumn('orders', 'work_completed_at')) {
                $table->dateTime('work_completed_at')->nullable();
            }
            if (!Schema::hasColumn('orders', 'payment_completed_at')) {
                $table->dateTime('payment_completed_at')->nullable();
            }
            if (!Schema::hasColumn('orders', 'rated_at')) {
                $table->dateTime('rated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'assigned_at',
                'service_checked_at', 
                'work_completed_at',
                'payment_completed_at',
                'rated_at'
            ]);
        });
    }
};
