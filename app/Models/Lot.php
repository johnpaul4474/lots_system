<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = ['lot_number', 'barangay_id'];

    // Lot belongs to a Barangay
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    // Lot has one LDC (PDF)
    public function lot_ldc()
    {
        return $this->hasOne(LotLdc::class, 'lot_id', 'id');
    }

    // Lot has one Map (PNG)
    public function lot_map()
    {
        return $this->hasOne(LotMap::class, 'lot_id', 'id');
    }
}

