<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Layout extends Model
{
    use SoftDeletes;
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
        'total_indexes',
        'total_chairs',
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

    public function getTotalChairsAttribute($value) {
        $total_chairs = ($this->row * $this->col) - count($this->space_indexes) - count($this->toilet_indexes) - count($this->door_indexes);
        return $total_chairs;
    }
}
