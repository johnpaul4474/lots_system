<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LotLdcSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
               $ldcs = [];

        for ($i = 1; $i <= 20; $i++) {
            $ldcs[] = [
                'id'        => $i,
                'lot_id'=> $i,
                'file_name' => "lot_{$i}.pdf",
                'file_path' => "storage/lot_ldcs/lot_{$i}.pdf",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('lot_ldcs')->insert($ldcs);
    }
}
