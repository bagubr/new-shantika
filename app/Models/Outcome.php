<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'reported_at',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model)
        {
            $model->created_by = \Auth::user()?->id??'';
        });
        static::updating(function ($model)
        {
            $model->updated_by = \Auth::user()?->id??'';
        });
    }

    public function getCreatedByAttribute($value)
    {
        return Admin::find($value)->name;
    }

    public function getUpdatedByAttribute($value)
    {
        return Admin::find($value)->name;
    }

    public function createdBy()
    {
        $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        $this->belongsTo(Admin::class, 'updated_by');
    }
   
}
