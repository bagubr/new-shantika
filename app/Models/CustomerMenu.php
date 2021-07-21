<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomerMenu extends Model
{
    use HasFactory;

    protected $table = "customer_menus";
    protected $fillable = ['name', 'icon'];

    public static function boot()
    {
        parent::boot();
        static::orderBy('id');
    }


    public function deleteImage()
    {
        Storage::disk('public')->delete($this->icon);
    }

    public function getAvatarAttribute($value)
    {
        return url('storage/' . $value);
    }
}
