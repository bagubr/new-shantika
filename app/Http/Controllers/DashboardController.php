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
    public function index()
    {
        //agent
        $agencies = Agency::all();
        //fleet
        $fleets = Fleet::all();
        //routes
        $routes = Route::get(['id', 'name']);
        $now = Order::whereDate('created_at', date('Y-m-d'))->get();
        // dd($now);
        $users = User::all();
        $orders = Order::paginate(7);
        $count_user = User::doesntHave('agencies')->count();
        $orders_money = Order::has('route')->sum('price');
        return view('dashboard', compact('users', 'orders', 'count_user', 'orders_money', 'agencies', 'fleets', 'routes'));
    }
    public function yearly(Request $request)
    {

        $params = ["January", "February", "March", "April", "Mey", "June", "July", "August", "September", "October", "November", "December"];
        dd($params);
        if ($request->year) {
            $year     =  $request->year;
        } else {
            $year     =  date("Y");
        }
        for ($i = 0; $i < 12; $i++) {
            $start    =  Carbon::now()->year($year)->startOfYear()->addMonth($i)->format('Y-m-d');
            $end      =  Carbon::now()->year($year)->startOfYear()->endOfMonth()->addMonth($i)->format('Y-m-d');
            $statistik_product1[]  = Order::where('status', 'PAID')->whereDate("created_at", '>=', $start)->whereDate('created_at', '<=', $end)->get()->count();
        }
        $yearly[] = $statistik_product1;
        // $yearly[] = $statistik_table1;
        // $yearly[] = $statistik_event1;
        // $yearly[] = $statistik_takeaway1;
        $data = [
            'params' => $params,
            'yearly' => $yearly,
            'year'   => $year,
        ];
        return $data;
    }
}
