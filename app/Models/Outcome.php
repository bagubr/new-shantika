<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
    use HasFactory;

    protected $fillable = [
        'fleet_route_id',
        'order_price_distribution_id',
        'outcome_type_id',
        'reported_at',
        'created_by',
        'updated_by',
    ];

    protected $appends = [
        'sum_total_pendapatan',
        'sum_pengeluaran',
        'sum_food',
        'sum_travel',
        'sum_member',
        'sum_commition',
    ];

    public function getSumTotalPendapatanAttribute()
    {
        return OrderPriceDistribution::whereIn('order_id', json_decode($this->order_price_distribution_id))->sum('for_owner');
    }

    public function getSumPengeluaranAttribute()
    {
        return $this->outcome_detail->sum('amount');
    }

    public function getSumFoodAttribute()
    {
        return OrderPriceDistribution::whereIn('order_id', json_decode($this->order_price_distribution_id))->sum('for_food');
    }

    public function getSumTravelAttribute()
    {
        return OrderPriceDistribution::whereIn('order_id', json_decode($this->order_price_distribution_id))->sum('for_travel');
    }

    public function getSumCommitionAttribute()
    {
        return OrderPriceDistribution::whereIn('order_id', json_decode($this->order_price_distribution_id))->sum('for_agent');
    }

    public function getSumMemberAttribute()
    {
        return OrderPriceDistribution::whereIn('order_id', json_decode($this->order_price_distribution_id))->sum('for_member');
    }

    public function fleet_route()
    {
        return $this->belongsTo(FleetRoute::class, 'fleet_route_id');
    }

    public function outcome_type()
    {
        return $this->belongsTo(OutcomeType::class, 'outcome_type_id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model)
        {
            $model->created_by = \Auth::user()?->id??'';
            $model->updated_by = \Auth::user()?->id??'';
        });
        static::updating(function ($model)
        {
            $model->updated_by = \Auth::user()?->id??'';
        });
        static::deleting(function($model) {
            $model->outcome_detail()->delete();
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
   
    public function outcome_detail()
    {
        return $this->hasMany(OutcomeDetail::class, 'outcome_id');
    }
}
