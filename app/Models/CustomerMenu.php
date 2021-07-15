<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMenu extends Model
{
    use HasFactory;

    protected $table = "customer_menus";
    protected $fillable = ['name', 'icon'];
}
