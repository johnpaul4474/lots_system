<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotLdc extends Model
{
    use HasFactory;

    
       protected $fillable = ['file_name', 'file_path'];

    public function lots()
    {
        return $this->hasMany(Lot::class, 'lot_map', 'id');
    }
}

