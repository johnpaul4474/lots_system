<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipality;

class MunicipalityController extends Controller
{
    public function index(Request $request)
    {
        $query = Municipality::query();

        if ($request->province_id) {
            $query->where('province_id', $request->province_id);
        }

        return response()->json($query->get());
    }
}
