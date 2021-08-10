<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Fleet;
use App\Models\Order;
use App\Models\OrderPriceDistribution;
use App\Models\Route;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $params = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
        $startOfThisWeek  = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfThisWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');
        for ($i = 0; $i < 7; $i++) {
            $startOfWeek  = Carbon::now()->startOfWeek()->addDay($i);
            $startOfLastWeeks = Carbon::now()->subWeek()->startOfWeek()->addDay($i);
            $order_jawa[] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfWeek) {
                $q->where('reserve_at', '=', $startOfWeek)->whereHas('route', function ($y) {
                    $y->where('area_id', 1);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jawa_last[] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfLastWeeks) {
                $q->where('reserve_at', '=', $startOfLastWeeks)->whereHas('route', function ($y) {
                    $y->where('area_id', 1);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jabodetabek[] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfWeek) {
                $q->where('reserve_at', '=', $startOfWeek)->whereHas('route', function ($y) {
                    $y->where('area_id', 2);
                });
            })->get()->pluck('for_owner')->sum();
            $order_jabodetabek_last[] = OrderPriceDistribution::whereHas('order', function ($q) use ($startOfLastWeeks) {
                $q->where('reserve_at', '=', $startOfLastWeeks)->whereHas('route', function ($y) {
                    $y->where('area_id', 2);
                });
            })->get()->pluck('for_owner')->sum();
        }
        $weekly[] = $order_jawa;
        $weekly_last[] = $order_jawa_last;
        $weekly2[] = $order_jabodetabek;
        $weekly_last2[] = $order_jabodetabek_last;

        $data_week = [
            'params' => $params,
            'last_week' => "$startOfLastWeek - $endOfLastWeek",
            'this_week' => "$startOfThisWeek - $endOfThisWeek",
            'weekly' => $weekly,
            'weekly_last' => $weekly_last,
            'weekly2' => $weekly2,
            'weekly_last2' => $weekly_last2,
        ];
        // dd($data_week['weekly_last'][0]);

        $data_statistic = ['weekly' => 'Mingguan', 'monthly' => 'Bulan', 'yearly' => 'Tahun'];
        if ($request->statistic) {
            if ($request->statistic == 'yearly') {
                $data = $this->yearly();
            } elseif ($request->statistic == 'monthly') {
                $data = $this->monthly();
            } else {
                $data = $this->weekly();
            }
        } else {
            $data = $this->weekly();
        }
        // AGENCY
        $agencies = Agency::all();
        $fleets = Fleet::get(['id', 'name']);
        $routes = Route::get(['id', 'name']);
        $orders = Order::query();
        $fleet = $request->fleet;
        if (!empty($request->agency)) {
            $orders = $orders->where('user_id', $request->agency);
        }
        if (!empty($request->route)) {
            $orders = $orders->where('route_id', $request->route);
        }
        if (!empty($request->fleet)) {
            $orders = $orders->whereHas('route', function ($q) use ($fleet) {
                $q->where('fleet_id', $fleet);
            });
        }
        $orders = $orders->orderBy('id', 'desc')->paginate(7);



        $test = $request->flash();
        $users = User::all();
        $count_user = User::doesntHave('agencies')->count();
        $orders_money = Order::has('route')->sum('price');
        session()->flash('Success', 'Berhasil Memuat Halaman');
        return view('dashboard', compact('users', 'orders', 'count_user', 'orders_money', 'agencies', 'fleets', 'routes', 'data', 'data_statistic', 'data_week'));
    }
    public function weekly()
    {
        $params = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
        // $currentDate = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $startOfLastWeek  = Carbon::now()->startOfWeek()->addDay($i);
            $order_jawa[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '1');
            })->whereDate('reserve_at', '=', $startOfLastWeek)->where('status', 'PAID')->get()->count();
            $order_jabodetabek[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '2');
            })->whereDate('reserve_at', '=', $startOfLastWeek)->where('status', 'PAID')->get()->count();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;

        $data = [
            'params' => $params,
            'weekly' => $weekly,
            'weekly2' => $weekly2
        ];
        return $data;
    }
    public function monthly()
    {
        $params = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "August", "September", "October", "November", "December"];

        for ($i = 0; $i < 12; $i++) {
            $start    =  Carbon::now()->startOfYear()->addMonth($i)->format('Y-m-d');
            $end      =  Carbon::now()->startOfYear()->endOfMonth()->addMonth($i)->format('Y-m-d');
            $order_jawa[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '1');
            })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->where('status', 'PAID')->get()->count();
            $order_jabodetabek[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '2');
            })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->where('status', 'PAID')->get()->count();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;

        $data = [
            'params' => $params,
            'weekly' => $weekly,
            'weekly2' => $weekly2
        ];
        return $data;
    }
    public function yearly()
    {
        for ($i = 0; $i < 10; $i++) {
            $year[] = Carbon::now()->startOfDecade()->addYear($i)->format('Y');
        }

        for ($i = 0; $i < 10; $i++) {
            $start          = Carbon::now()->startOfDecade()->addYear($i)->format('Y');
            $order_jawa[]   = Order::whereHas('route', function ($q) {
                $q->where('area_id', '1');
            })->whereYear('reserve_at', '=', $start)->where('status', 'PAID')->get()->count();
            $order_jabodetabek[]   = Order::whereHas('route', function ($q) {
                $q->where('area_id', '2');
            })->whereYear('reserve_at', '=', $start)->where('status', 'PAID')->get()->count();
        }
        $weekly[] = $order_jawa;
        $weekly2[] = $order_jabodetabek;

        $data = [
            'params' => $year,
            'weekly' => $weekly,
            'weekly2' => $weekly2
        ];
        return $data;
    }
}
