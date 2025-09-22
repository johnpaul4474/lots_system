<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LotsTableSeeder extends Seeder
{
public function run(): void
{
    $lots = [];

    for ($i = 1; $i <= 20; $i++) {
        $lots[] = [
            'lot_number'  => $i,
            'lot_ldc'    => $i,
            'lot_map'    => $i,
            'barangay_id' => 87, // always 87
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }

    DB::table('lots')->insert($lots);
}

}
