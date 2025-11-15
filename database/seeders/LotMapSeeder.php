<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LotMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
               $maps = [];

        for ($i = 1; $i <= 20; $i++) {
            $maps[] = [
                'id'        => $i,
                'lot_id'=> $i,
                'file_name' => "lot_{$i}.jpg",
                'file_path' => "storage/lot_maps/lot_{$i}.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('lot_maps')->insert($maps);
    }
}
