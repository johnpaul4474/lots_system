<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LotMap;
use App\Models\Lot;

class LotMapSeeder extends Seeder
{
    public function run(): void
    {
        // Get all lots
        $lots = Lot::all();
        $counter = 1;

        foreach ($lots as $lot) {
            LotMap::create([
                'lot_id'    => $lot->id,
                'file_path' => 'sample/LOT ' . $counter . '.png', // e.g. sample/LOT 1.png
                'file_name' => 'LOT ' . $counter . '.png',
            ]);

            $counter++;
        }
    }
}
