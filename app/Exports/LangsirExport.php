<?php

namespace App\Exports;

use App\Models\OrderPriceDistribution;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\Order;
use App\Models\Agency;

class LangsirExport implements FromView, ShouldAutoSize
{
    public function __construct(Request $request)
    {
        $this->request  = $request;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $area_id = $this->request->area_id;
        $date = $this->request->date;
        $fleet_route_id = $this->request->fleet_route_id;
        $langsir = Order::whereIn('status', Order::STATUS_BOUGHT)
        ->with('fleet_route.fleet_detail.fleet.fleetclass', 'fleet_route.route')
        ->with('fleet_route.fleet_detail.fleet.layout')
        ->withCount(['order_detail'=>function($query) {
            $query->whereHas('order', function($subquery) {
                $subquery->whereRaw('fleet_route_id = orders.fleet_route_id');
            });
        }])
        ->when($date, function ($query) use ($date) {
            $query->whereDate('reserve_at', $date);
        })
        ->when($area_id, function($query) use ($area_id) {
            $query->whereHas('fleet_route.route.checkpoints', function($subquery) use ($area_id) {
                $subquery->whereHas('agency.city', function ($subsubquery) use ($area_id) {
                    $subsubquery->where('area_id', '!=', $area_id);
                });
            });
        })->when($fleet_route_id, function ($query) use ($fleet_route_id)
        {
            $query->where('fleet_route_id', $fleet_route_id);
        })
        ->get();
        $agencies = Agency::whereHas('city', function ($query) use ($area_id)
        {
            $query->whereAreaId($area_id);
        })->get();
        return view('excel_export.langsir', compact('langsir', 'agencies', 'date', 'fleet_route_id'));
    }
}
