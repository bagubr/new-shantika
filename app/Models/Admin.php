<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles;
    protected $fillable = [
        'name', 'email', 'password'
    ];
    public function restaurant_admin()
    {
        return $this->belongsTo(RestaurantAdmin::class, 'id', 'admin_id');
    }
}
