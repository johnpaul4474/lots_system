<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LotLdc;
use App\Models\Lot;

class LotLdcSeeder extends Seeder
{
    public function run(): void
    {
        // Get all lots
        $lots = Lot::all();
        $counter = 1;

        foreach ($lots as $lot) {
            LotLdc::create([
                'lot_id'    => $lot->id,
                'file_path' => 'sample/LOT ' . $counter . '.pdf', // e.g. sample/LOT 1.pdf
                'file_name' => 'LOT ' . $counter . '.pdf',
            ]);

            $counter++;
        }
    }
}
