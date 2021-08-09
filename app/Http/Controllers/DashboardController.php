<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Fleet;
use App\Models\Order;
use App\Models\Route;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->statistic) {
            if ($request->statistic == 'weekly') {
                $data = $this->weekly();
            } elseif ($request->statistic == 'monthly') {
                $data = $this->monthly();
            } else {
                $data = $this->yearly();
            }
        } else {
            $data = $this->yearly();
        }
        $agencies = Agency::all();
        $fleets = Fleet::all();
        $routes = Route::get(['id', 'name']);
        $now = Order::whereDate('created_at', date('Y-m-d'))->get();
        $users = User::all();
        $orders = Order::paginate(7);
        $count_user = User::doesntHave('agencies')->count();
        $orders_money = Order::has('route')->sum('price');
        return view('dashboard', compact('users', 'orders', 'count_user', 'orders_money', 'agencies', 'fleets', 'routes', 'data'));
    }
    public function weekly()
    {
        $params = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
        // $currentDate = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $startOfLastWeek  = Carbon::now()->startOfWeek()->addDay($i);
            $order_jawa[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '1');
            })->whereDate('reserve_at', '=', $startOfLastWeek)->get()->count();
            $order_jabodetabek[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '2');
            })->whereDate('reserve_at', '=', $startOfLastWeek)->get()->count();
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
            })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->get()->count();
            $order_jabodetabek[] = Order::whereHas('route', function ($q) {
                $q->where('area_id', '2');
            })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->get()->count();
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
            })->whereYear('reserve_at', '=', $start)->get()->count();
            $order_jabodetabek[]   = Order::whereHas('route', function ($q) {
                $q->where('area_id', '2');
            })->whereYear('reserve_at', '=', $start)->get()->count();
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
