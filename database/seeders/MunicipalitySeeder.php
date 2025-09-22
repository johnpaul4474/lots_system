<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipalitySeeder extends Seeder
{
    public function run(): void
    {
        $municipalities = [
            ['id' => 1, 'province_id' => 3, 'code' => 'BAG', 'name' => 'Baguio City'],
            ['id' => 2, 'province_id' => 3, 'code' => 'ATK', 'name' => 'Atok'],
            ['id' => 3, 'province_id' => 3, 'code' => 'BKO', 'name' => 'Bakun'],
            ['id' => 4, 'province_id' => 3, 'code' => 'BKO2', 'name' => 'Bokod'],
            ['id' => 5, 'province_id' => 3, 'code' => 'BUG', 'name' => 'Buguias'],
            ['id' => 6, 'province_id' => 3, 'code' => 'ITG', 'name' => 'Itogon'],
            ['id' => 7, 'province_id' => 3, 'code' => 'KAB', 'name' => 'Kabayan'],
            ['id' => 8, 'province_id' => 3, 'code' => 'KAP', 'name' => 'Kapangan'],
            ['id' => 9, 'province_id' => 3, 'code' => 'KIB', 'name' => 'Kibungan'],
            ['id' => 10, 'province_id' => 3, 'code' => 'LAU', 'name' => 'La Trinidad'],
            ['id' => 11, 'province_id' => 3, 'code' => 'MKB', 'name' => 'Mankayan'],
            ['id' => 12, 'province_id' => 3, 'code' => 'SAB', 'name' => 'Sablan'],
            ['id' => 13, 'province_id' => 3, 'code' => 'TBL', 'name' => 'Tuba'],
            ['id' => 14, 'province_id' => 3, 'code' => 'TBL2', 'name' => 'Tublay'],
            // Note: Baguio City is an independent chartered city, not part of Benguet province,
            // but sometimes included in regional stats. If you want it inside Benguet, uncomment:
            
        ];

        DB::table('municipalities')->insert($municipalities);
    }
}
