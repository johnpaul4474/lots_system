<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangay;

class BarangayController extends Controller
{
    public function index(Request $request)
    {
        $query = Barangay::query();

        if ($request->municipality_id) {
            $query->where('municipality_id', $request->municipality_id);
        }

        return response()->json($query->get());
    }
}
