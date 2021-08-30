<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyDepartureTime extends Model
{
    use HasFactory;

    protected $table = 'agency_departure_times';

    protected $fillable = [
        'agency_id', 'departure_at'
    ];

    protected $appends = [
        'time_classification_id'
    ];

    public function agency() {
        return $this->belongsTo(Agency::class);
    }

    public function getTimeClassificationIdAttribute() {
        return TimeClassification::where('time_start', '<=', $this->attributes['departure_at'])->where('time_end', '>=', $this->attributes['departure_at'])->first()?->id;
    }
}
