<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcModel;

class AcModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            [
                'name' => 'Samsung',
                'description' => 'AC Samsung dengan teknologi inverter terbaru',
            ],
            [
                'name' => 'LG',
                'description' => 'AC LG dengan sistem pendingin yang efisien',
            ],
            [
                'name' => 'Daikin',
                'description' => 'AC Daikin premium dengan filter anti bakteri',
            ],
            [
                'name' => 'Panasonic',
                'description' => 'AC Panasonic dengan hemat energi',
            ],
            [
                'name' => 'Toshiba',
                'description' => 'AC Toshiba dengan performa stabil',
            ],
            [
                'name' => 'Sharp',
                'description' => 'AC Sharp dengan teknologi ion plasmacluster',
            ],
            [
                'name' => 'Mitsubishi Electric',
                'description' => 'AC Mitsubishi dengan remote wifi',
            ],
            [
                'name' => 'Fujitsu',
                'description' => 'AC Fujitsu dengan desain modern',
            ],
        ];

        foreach ($models as $model) {
            AcModel::create($model);
        }
    }
}
