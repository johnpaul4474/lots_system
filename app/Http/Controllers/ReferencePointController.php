<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ReferencePointController extends Controller
{
    public function index()
    {
        return Inertia::render('Reference/Index');
    }

    public function search(Request $request)
    {
        $region = $request->input('region');
        $province = $request->input('province');
        $municipality = $request->input('municipality');
        $barangay = $request->input('barangay');
        $keyword = $request->input('keyword');

        Log::info('Reference search called', [
            'region' => $region,
            'province' => $province,
            'municipality' => $municipality,
            'barangay' => $barangay,
            'keyword' => $keyword
        ]);

        $folder = public_path('references');

        if (!is_dir($folder)) {
            Log::warning('References folder does not exist', ['folder' => $folder]);
            return response()->json([]);
        }

        // Get all files in public/references
        $allFiles = collect(scandir($folder))
            ->filter(fn($f) => !in_array($f, ['.', '..']))
            ->values();

        Log::info('All files in public/references', ['files' => $allFiles->toArray()]);

        // Filter files
        $filtered = $allFiles->filter(function ($file) use ($region, $province, $municipality, $barangay, $keyword) {
            $matches = true;

            // Split filename by dash
            $parts = explode('-', strtolower(pathinfo($file, PATHINFO_FILENAME)));

            // Match each filter against the filename parts if provided
            if ($region) {
                $matches = $matches && in_array(strtolower($region), $parts);
            }
            if ($province) {
                $matches = $matches && in_array(strtolower($province), $parts);
            }
            if ($municipality) {
                $matches = $matches && in_array(strtolower($municipality), $parts);
            }
            if ($barangay) {
                $matches = $matches && in_array(strtolower($barangay), $parts);
            }
            if ($keyword) {
                $matches = $matches && str_contains(strtolower($file), strtolower($keyword));
            }

            return $matches;
        });

        Log::info('Filtered files', ['filtered' => $filtered->values()->toArray()]);

        return response()->json($filtered->values());
    }
}
