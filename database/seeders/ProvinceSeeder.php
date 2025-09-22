<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = [
            ['id' => 1, 'region_id' => 16, 'code' => 'ABR', 'name' => 'Abra'],
            ['id' => 2, 'region_id' => 16, 'code' => 'APA', 'name' => 'Apayao'],
            ['id' => 3, 'region_id' => 16, 'code' => 'BEN', 'name' => 'Benguet'],
            ['id' => 4, 'region_id' => 16, 'code' => 'IFU', 'name' => 'Ifugao'],
            ['id' => 5, 'region_id' => 16, 'code' => 'KAL', 'name' => 'Kalinga'],
            ['id' => 6, 'region_id' => 16, 'code' => 'MOU', 'name' => 'Mountain Province'],
        ];

        DB::table('provinces')->insert($provinces);
    }
}
