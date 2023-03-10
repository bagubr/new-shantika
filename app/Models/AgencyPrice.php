<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id', 'price', 'start_at'
    ];

    public function agency() {
        return $this->belongsTo(Agency::class);
    }
}
