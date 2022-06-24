<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles, SoftDeletes;
    protected $fillable = [
        'name', 'email', 'password', 'area_id'
    ];
    
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function restaurant_admin()
    {
        return $this->belongsTo(RestaurantAdmin::class, 'id', 'admin_id');
    }
}
