<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceType;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Cuci Rutin',
                'description' => 'Pembersihan AC berkala untuk performa optimal',
                'price' => 250000,
            ],
            [
                'name' => 'Service Lengkap',
                'description' => 'Service menyeluruh termasuk pembersihan filter, penggantian oli, dan pengecekan teknis',
                'price' => 450000,
            ],
            [
                'name' => 'Service Berkala',
                'description' => 'Perawatan bulanan untuk menjaga kesehatan AC',
                'price' => 150000,
            ],
            [
                'name' => 'Pembersihan Mendalam',
                'description' => 'Pembersihan menyeluruh termasuk kondensor dan evaporator',
                'price' => 350000,
            ],
            [
                'name' => 'Service + Penggantian Freon',
                'description' => 'Service lengkap dengan penambahan freon jika diperlukan',
                'price' => 550000,
            ],
        ];

        foreach ($services as $service) {
            ServiceType::create($service);
        }
    }
}
