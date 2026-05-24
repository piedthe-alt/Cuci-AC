<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AcModel;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\AddOn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users
        User::create([
            'name' => 'User Pelanggan',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'phone' => '08123456789',
            'address' => 'Jl. Merdeka No. 1, Jakarta',
            'city' => 'Jakarta',
            'role' => 'user',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Staff Technician',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'phone' => '08198765432',
            'address' => 'Jl. Sudirman No. 5, Jakarta',
            'city' => 'Jakarta',
            'role' => 'staff',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Manager',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '08111111111',
            'address' => 'Jl. Gatot Subroto No. 10, Jakarta',
            'city' => 'Jakarta',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Owner Business',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
            'phone' => '08122222222',
            'address' => 'Jl. Ahmad Yani No. 20, Jakarta',
            'city' => 'Jakarta',
            'role' => 'owner',
            'is_active' => true,
        ]);

        // Create AC Models
        $models = [
            ['name' => 'Split AC 1 PK', 'description' => 'AC split dengan kapasitas 1 PK'],
            ['name' => 'Split AC 1.5 PK', 'description' => 'AC split dengan kapasitas 1.5 PK'],
            ['name' => 'Split AC 2 PK', 'description' => 'AC split dengan kapasitas 2 PK'],
            ['name' => 'Window AC 1 PK', 'description' => 'AC jendela dengan kapasitas 1 PK'],
        ];

        foreach ($models as $model) {
            AcModel::create($model);
        }

        // Create Services
        $services = [
            ['name' => 'Pembersihan AC', 'description' => 'Layanan pembersihan dan perawatan AC'],
            ['name' => 'Perbaikan AC', 'description' => 'Layanan perbaikan AC yang rusak'],
            ['name' => 'Pengisian Freon', 'description' => 'Layanan pengisian freon AC'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Create Service Types
        $serviceTypes = [
            ['service_id' => 1, 'name' => 'Cuci Kompressor + Condensor', 'price' => 150000, 'description' => 'Pembersihan kompressor dan condensor AC'],
            ['service_id' => 1, 'name' => 'Cuci Filter + Service Ringan', 'price' => 75000, 'description' => 'Pembersihan filter dan service ringan'],
            ['service_id' => 2, 'name' => 'Ganti Kapasitor', 'price' => 200000, 'description' => 'Penggantian kapasitor yang rusak'],
            ['service_id' => 2, 'name' => 'Ganti Kompressor', 'price' => 2000000, 'description' => 'Penggantian kompressor baru'],
            ['service_id' => 3, 'name' => 'Isi Freon (500 gram)', 'price' => 250000, 'description' => 'Pengisian freon 500 gram'],
            ['service_id' => 3, 'name' => 'Isi Freon (1 kg)', 'price' => 450000, 'description' => 'Pengisian freon 1 kg'],
        ];

        foreach ($serviceTypes as $type) {
            ServiceType::create($type);
        }

        // Create Add-ons
        $addOns = [
            ['name' => 'Pipa AC', 'description' => 'Pipa AC 1/4 meter', 'price' => 50000, 'unit' => 'meter', 'stock' => 50, 'is_active' => true],
            ['name' => 'Freon R22', 'description' => 'Freon R22 untuk pengisian', 'price' => 350000, 'unit' => 'liter', 'stock' => 20, 'is_active' => true],
            ['name' => 'Kapasitor AC', 'description' => 'Kapasitor AC pengganti', 'price' => 150000, 'unit' => 'pcs', 'stock' => 30, 'is_active' => true],
            ['name' => 'Filter AC', 'description' => 'Filter AC pengganti', 'price' => 80000, 'unit' => 'pcs', 'stock' => 40, 'is_active' => true],
            ['name' => 'Oli Kompresor', 'description' => 'Oli kompresor AC', 'price' => 120000, 'unit' => 'liter', 'stock' => 15, 'is_active' => true],
            ['name' => 'Pipa Freon 3/8', 'description' => 'Pipa freon ukuran 3/8', 'price' => 75000, 'unit' => 'meter', 'stock' => 30, 'is_active' => true],
        ];

        foreach ($addOns as $addOn) {
            AddOn::create($addOn);
        }

        $this->command->info('Database seeded successfully!');
    }
}
