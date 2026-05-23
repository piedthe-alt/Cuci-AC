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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('google_id')->unique()->nullable()->after('phone');
            $table->string('google_token')->nullable()->after('google_id');
            $table->string('role')->default('user')->after('google_token'); // 'user', 'admin', 'staff'
            $table->string('address')->nullable()->after('role');
            $table->string('city')->nullable()->after('address');
            $table->string('profile_picture')->nullable()->after('city');
            $table->boolean('is_active')->default(true)->after('profile_picture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'google_id', 'google_token', 'role', 'address', 'city', 'profile_picture', 'is_active']);
        });
    }
};
