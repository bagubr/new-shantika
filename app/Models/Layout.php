<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
    use HasFactory;

    protected $table = 'layouts';

    protected $casts = [
        'space_indexes'=>'array',
        'toilet_indexes'=>'array',
        'door_indexes'=>'array'
    ];

    protected $fillable = [
        'name', 'col', 'row', 'space_indexes', 'toilet_indexes', 'door_indexes', 'note'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $appends = [
        'total_indexes'
    ];

    public function chairs() {
        return $this->hasMany(LayoutChair::class, 'layout_id', 'id');
    }

    public function fleet() {
        return $this->belongsTo(Fleet::class, 'id', 'layout_id');
    }

    public function getTotalIndexesAttribute($value) {
        return $this->row * $this->col;
    }
}
