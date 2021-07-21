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

    protected static function booted()
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id');
        });
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
