<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // A region has many provinces
    public function provinces()
    {
        return $this->hasMany(Province::class);
    }
}
