<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['id' => 1,  'code' => 'Region I',    'name' => 'Ilocos Region'],
            ['id' => 2,  'code' => 'Region II',   'name' => 'Cagayan Valley'],
            ['id' => 3,  'code' => 'Region III',  'name' => 'Central Luzon'],
            ['id' => 4,  'code' => 'Region IV-A', 'name' => 'CALABARZON'],
            ['id' => 5,  'code' => 'Region IV-B', 'name' => 'MIMAROPA'],
            ['id' => 6,  'code' => 'Region V',    'name' => 'Bicol Region'],
            ['id' => 7,  'code' => 'Region VI',   'name' => 'Western Visayas'],
            ['id' => 8,  'code' => 'Region VII',  'name' => 'Central Visayas'],
            ['id' => 9,  'code' => 'Region VIII', 'name' => 'Eastern Visayas'],
            ['id' => 10, 'code' => 'Region IX',   'name' => 'Zamboanga Peninsula'],
            ['id' => 11, 'code' => 'Region X',    'name' => 'Northern Mindanao'],
            ['id' => 12, 'code' => 'Region XI',   'name' => 'Davao Region'],
            ['id' => 13, 'code' => 'Region XII',  'name' => 'SOCCSKSARGEN'],
            ['id' => 14, 'code' => 'Region XIII', 'name' => 'Caraga'],
            ['id' => 15, 'code' => 'BARMM','name' => 'Bangsamoro Autonomous Region in Muslim Mindanao'],
            ['id' => 16, 'code' => 'CAR',  'name' => 'Cordillera Administrative Region'],
            ['id' => 17, 'code' => 'NCR',  'name' => 'National Capital Region'],
        ];

        DB::table('regions')->insert($regions);
    }
}
