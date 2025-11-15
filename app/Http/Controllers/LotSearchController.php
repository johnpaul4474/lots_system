<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lot;

class LotSearchController extends Controller
{
    public function search(Request $request)
    {
        $lots = Lot::with([
            'barangay.municipality.province.region',
            'ldc',
            'map'
        ]);

        // Filters
        if ($request->filled('lot_number')) {
            $lots->where('lot_number', $request->lot_number);
        }

        if ($request->filled('barangay_id')) {
            $lots->where('barangay_id', $request->barangay_id);
        }

        if ($request->filled('municipality_id')) {
            $lots->whereHas('barangay.municipality', function ($q) use ($request) {
                $q->where('id', $request->municipality_id);
            });
        }

        if ($request->filled('province_id')) {
            $lots->whereHas('barangay.municipality.province', function ($q) use ($request) {
                $q->where('id', $request->province_id);
            });
        }

        if ($request->filled('region_id')) {
            $lots->whereHas('barangay.municipality.province.region', function ($q) use ($request) {
                $q->where('id', $request->region_id);
            });
        }

        $lots = $lots->get();

        // Convert file paths to URLs
        $lots->transform(function ($lot) {
            $lot->lot_ldc_url = $lot->ldc ? asset($lot->ldc->file_path) : null;
            $lot->lot_map_url = $lot->map ? asset($lot->map->file_path) : null;

            return $lot;
        });
        
        return response()->json($lots);
    }
}
