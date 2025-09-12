<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $query = Province::query();

        if ($request->region_id) {
            $query->where('region_id', $request->region_id);
        }

        return response()->json($query->get());
    }
}
