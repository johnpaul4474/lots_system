<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lot;
use App\Models\LotLdc;
use App\Models\LotMap;

class LotFileSeeder extends Seeder
{
    public function run(): void
    {
        $lots = Lot::all();
        $counter = 1;

        foreach ($lots as $lot) {
            // Seed LotLdc (PDF)
            LotLdc::create([
                'lot_id'    => $lot->id,
                'file_path' => 'sample/LOT ' . $counter . '.pdf',
                'file_name' => 'LOT ' . $counter . '.pdf',
            ]);

            // Seed LotMap (PNG)
            LotMap::create([
                'lot_id'    => $lot->id,
                'file_path' => 'sample/LOT ' . $counter . '.png',
                'file_name' => 'LOT ' . $counter . '.png',
            ]);

            $counter++;
        }
    }
}
