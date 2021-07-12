<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Fleet extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'fleets';
    protected $fillable = ['name', 'description', 'fleet_class_id', 'image', 'layout_id'];

    public function layout() {
        return $this->hasOne(Layout::class, 'id', 'layout_id');
    }
    
    public function fleetclass()
    {
        return $this->belongsTo(FleetClass::class, 'fleet_class_id', 'id');
    }

    public function deleteImage()
    {
        Storage::disk('public')->delete($this->image);
    }
}
