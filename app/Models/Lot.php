<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

 protected $fillable = [
        'lot_number',
        'barangay_id',
        'lot_ldc',
        'lot_map',
    ];

    public function ldc()
    {
        return $this->belongsTo(LotLdc::class, 'lot_ldc');
    }

    public function map()
    {
        return $this->belongsTo(LotMap::class, 'lot_map');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }
}

