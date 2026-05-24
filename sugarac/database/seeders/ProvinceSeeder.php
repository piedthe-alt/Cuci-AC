<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['name' => 'Aceh', 'code' => 'AC'],
            ['name' => 'Sumatera Utara', 'code' => 'SU'],
            ['name' => 'Sumatera Barat', 'code' => 'SB'],
            ['name' => 'Riau', 'code' => 'RI'],
            ['name' => 'Jambi', 'code' => 'JA'],
            ['name' => 'Sumatera Selatan', 'code' => 'SS'],
            ['name' => 'Bangka Belitung', 'code' => 'BB'],
            ['name' => 'Bengkulu', 'code' => 'BE'],
            ['name' => 'Lampung', 'code' => 'LA'],
            ['name' => 'Kepulauan Riau', 'code' => 'KR'],
            ['name' => 'DKI Jakarta', 'code' => 'DKI'],
            ['name' => 'Jawa Barat', 'code' => 'JB'],
            ['name' => 'Jawa Tengah', 'code' => 'JT'],
            ['name' => 'DI Yogyakarta', 'code' => 'DIY'],
            ['name' => 'Jawa Timur', 'code' => 'JE'],
            ['name' => 'Banten', 'code' => 'BN'],
            ['name' => 'Bali', 'code' => 'BA'],
            ['name' => 'Nusa Tenggara Barat', 'code' => 'NTB'],
            ['name' => 'Nusa Tenggara Timur', 'code' => 'NTT'],
            ['name' => 'Kalimantan Barat', 'code' => 'KB'],
            ['name' => 'Kalimantan Tengah', 'code' => 'KT'],
            ['name' => 'Kalimantan Selatan', 'code' => 'KS'],
            ['name' => 'Kalimantan Timur', 'code' => 'KE'],
            ['name' => 'Kalimantan Utara', 'code' => 'KU'],
            ['name' => 'Sulawesi Utara', 'code' => 'SLU'],
            ['name' => 'Sulawesi Tengah', 'code' => 'SLT'],
            ['name' => 'Sulawesi Selatan', 'code' => 'SLS'],
            ['name' => 'Sulawesi Tenggara', 'code' => 'SLTE'],
            ['name' => 'Gorontalo', 'code' => 'GO'],
            ['name' => 'Sulawesi Barat', 'code' => 'SLB'],
            ['name' => 'Maluku', 'code' => 'MA'],
            ['name' => 'Maluku Utara', 'code' => 'MU'],
            ['name' => 'Papua', 'code' => 'PA'],
            ['name' => 'Papua Barat', 'code' => 'PB'],
            ['name' => 'Papua Barat Daya', 'code' => 'PBD'],
            ['name' => 'Papua Tengah', 'code' => 'PT'],
            ['name' => 'Papua Pegunungan', 'code' => 'PPG'],
            ['name' => 'Papua Selatan', 'code' => 'PS'],
        ];

        foreach ($provinces as $province) {
            Province::updateOrCreate(
                ['code' => $province['code']],
                ['name' => $province['name']]
            );
        }
    }
}
