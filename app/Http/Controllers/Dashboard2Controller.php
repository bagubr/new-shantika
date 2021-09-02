<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Fleet;
use App\Models\FleetDetail;
use App\Models\Order;
use App\Models\OrderPriceDistribution;
use App\Models\Route;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class Dashboard2Controller extends Controller
{
    protected $params;

    public function __construct()
    {
        $this->params = ['weekly' => 'Harian', 'monthly' => 'Bulan'];
    }

    public function index(Request $request)
    {

        $total_order = Order::all()->count();
        $count_user = User::doesntHave('agencies')->count();
        $orders_money = Order::whereIn('status', Order::STATUS_BOUGHT)->sum('price');
        
        $data = $this->statistic($request);
        $data['digit'] = 0;
        $data['agencies'] = Agency::orderBy('name', 'asc')->get();
        $data['fleets'] = Fleet::orderBy('name', 'asc')->get();
        $data['digit_previous'] = -7;
        return view('dashboard.dashboard', compact('total_order', 'count_user', 'orders_money', 'data'));
    }
    
    public function statistic(Request $request)
    {
        $digit = $request->digit??0;
        $data['digit'] = $digit;
        $data['params'] = $this->params;
        $data['now'] = $this->pendapatan($request, $digit);
        $params = $request->params??'weekly';
        if ($params == 'monthly') {
            $data['digit_previous'] = $digit - 12;
            $data['previous'] = $this->pendapatan($request, $data['digit_previous']);
        } elseif($params == 'weekly') {
            $data['digit_previous'] = $digit - 7;
            $data['previous'] = $this->pendapatan($request, $data['digit_previous']);
        }
        return $data;
    }

    public function pendapatan(Request $request, $digit = 0)
    {
        // start pendapatan
        $params = $request->params??'weekly';
        if ($params == 'monthly') {
            $data['data'] = $this->pendapatan_monthly($request, $digit);
            $data['labels'] = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "August", "September", "October", "November", "Desember"];
        } elseif($params == 'weekly') {
            $data['data'] = $this->pendapatan_weekly($request, $digit);
            $data['labels'] = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
        }
        return $data;
        // end of pendapatan
    }

    // START OF PENDAPATAN TIKET
    public function pendapatan_weekly(Request $request, $day = 0)
    {
        $data['label']  = Carbon::now()->startOfWeek()->addDay($day)->format('Y-m-d').' '.Carbon::now()->endOfWeek()->addDay($day)->format('Y-m-d');
        $data['agency_id'] = $request->agency_id??'';
        $data['fleet_id'] = $request->fleet_id??'';
        $max = $day + 7;
        for ($i = $day; $i < $max; $i++) {
            $startOfWeek  = Carbon::now()->startOfWeek()->addDay($i);
            $data[0][] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfWeek, $request) {
                $q->when(($request->agency_id), function ($sqa) use ($request)
                {
                    $sqa->where('departure_agency_id', $request->agency_id);
                });
                $q->whereHas('fleet_route.fleet_detail', function ($sqf) use ($request)
                {
                    $sqf->when(($request->fleet_id), function ($sqqf) use ($request)
                    {
                        $sqqf->where('fleet_id', $request->fleet_id);
                    });
                });
                $q->whereIn('status', Order::STATUS_BOUGHT)->where('reserve_at', '=', $startOfWeek)->whereHas('fleet_route.route', function ($sq) use ($request) {
                    $sq->whereHas('checkpoints.agency', function ($sqq) use ($request) {
                        $sqq->whereHas('city', function ($sqqq) {
                            $sqqq->where('area_id', 2);
                        });
                    });
                });
            })->get()->pluck('for_owner')->sum();
            $data[1][] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfWeek, $request){
                $q->when(($request->agency_id), function ($sqa) use ($request)
                {
                    $sqa->where('departure_agency_id', $request->agency_id);
                });
                $q->whereHas('fleet_route.fleet_detail', function ($sqf) use ($request)
                {
                    $sqf->when(($request->fleet_id), function ($sqqf) use ($request)
                    {
                        $sqqf->where('fleet_id', $request->fleet_id);
                    });
                });
                $q->whereIn('status', Order::STATUS_BOUGHT)->where('reserve_at', '=', $startOfWeek)->whereHas('fleet_route.route', function ($sq) use ($request) {
                    $sq->whereHas('checkpoints.agency', function ($sqq) use ($request) {
                        $sqq->whereHas('city', function ($sqqq) {
                            $sqqq->where('area_id', 1);
                        });
                    });
                });
            })->get()->pluck('for_owner')->sum();
        }
        return $data;
    }
    public function pendapatan_monthly(Request $request, $day = 0)
    {
        $data['label'] = Carbon::now()->startOfMonth()->addMonth($day)->format('Y');
        $data['agency_id'] = $request->agency_id??'';
        $data['fleet_id'] = $request->fleet_id??'';
        $max = $day + 12;
        for ($i = $day; $i < $max; $i++) {
            $start          =  Carbon::now()->startOfYear()->addMonth($i)->format('Y-m-d');
            $end            =  Carbon::now()->startOfYear()->endOfMonth()->addMonth($i)->format('Y-m-d');
            $data[0][]       = OrderPriceDistribution::whereHas('order', function ($q) use ($start, $end, $request){
                $q->when(($request->agency_id), function ($sqa) use ($request)
                {
                    $sqa->where('departure_agency_id', $request->agency_id);
                });
                $q->whereHas('fleet_route.fleet_detail', function ($sqf) use ($request)
                {
                    $sqf->when(($request->fleet_id), function ($sqqf) use ($request)
                    {
                        $sqqf->where('fleet_id', $request->fleet_id);
                    });
                });
                $q->whereIn('status', Order::STATUS_BOUGHT)->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->whereHas('fleet_route.route', function ($sq) use ($request) {
                    $sq->whereHas('checkpoints.agency', function ($sqq) use ($request) {
                        $sqq->whereHas('city', function ($sqqq) {
                            $sqqq->where('area_id', 2);
                        });
                    });
                });
            })->get()->pluck('for_owner')->sum();
            $data[1][]       = OrderPriceDistribution::whereHas('order', function ($q) use ($start, $end, $request){
                $q->when(($request->agency_id), function ($sqa) use ($request)
                {
                    $sqa->where('departure_agency_id', $request->agency_id);
                });
                $q->whereHas('fleet_route.fleet_detail', function ($sqf) use ($request)
                {
                    $sqf->when(($request->fleet_id), function ($sqqf) use ($request)
                    {
                        $sqqf->where('fleet_id', $request->fleet_id);
                    });
                });
                $q->whereIn('status', Order::STATUS_BOUGHT)->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->whereHas('fleet_route.route', function ($sq) use ($request) {
                    $sq->whereHas('checkpoints.agency', function ($sqq) use ($request) {
                        $sqq->whereHas('city', function ($sqqq) {
                            $sqqq->where('area_id', 1);
                        });
                    });
                });
            })->get()->pluck('for_owner')->sum();
        }

        return $data;
    }
}
