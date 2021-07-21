<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class CustomerMenu extends Model
{
    use HasFactory;

    protected $table = "customer_menus";
    protected $fillable = ['name', 'icon'];

    public static function booted()
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }


    public function deleteImage()
    {
        Storage::disk('public')->delete($this->icon);
    }

    public function getIconAttribute($value)
    {
        return url('storage/' . $value);
    }
}
