<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayoutChair extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'layout_chairs';

    protected $fillable = [
        'name', 'index', 'layout_id'
    ];

    protected $appends = [
        'is_space',
        'is_door',
        'is_toilet',
    ];

    public function getIsSpaceAttribute()
    {
        $space  = $this->layout()->first()->space_indexes;
        return in_array($this->index, $space);
    }
    
    public function getIsDoorAttribute()
    {
        $door   = $this->layout()->first()->door_indexes;
        return in_array($this->index, $door);
    }
    
    public function getIsToiletAttribute()
    {
        $toilet = $this->layout()->first()->toilet_indexes;
        return in_array($this->index, $toilet);
    }

    public function layout()
    {
        return $this->belongsTo(Layout::class, 'layout_id');
    }

    protected $hidden = [
        'created_at', 'updated_at', 'layout_id'
    ];
}
