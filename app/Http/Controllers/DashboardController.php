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

        $params = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "August", "September", "October", "November", "December"];
        $currentYear = Carbon::now();

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
        //agent
        $agencies = Agency::all();
        //fleet
        $fleets = Fleet::all();
        //routes
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
        // $params = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
        // $currentDate = Carbon::now()->startOfWeek();

        // for ($i = 0; $i < 7; $i++) {
        //     $startOfLastWeek  = $currentDate->copy()->subDay($i);
        //     $endOfLastWeek = $currentDate->copy()->subDay($i);
        //     $order_jawa[] = Order::whereHas('route', function ($q) {
        //         $q->where('area_id', '1');
        //     })->whereDate('reserve_at', '>=', $startOfLastWeek)->whereDate('reserve_at', '<=', $endOfLastWeek)->get()->count();
        //     $order_jabodetabek[] = Order::whereHas('route', function ($q) {
        //         $q->where('area_id', '2');
        //     })->whereDate('reserve_at', '>=', $startOfLastWeek)->whereDate('reserve_at', '<=', $endOfLastWeek)->get()->count();
        // }
        // $weekly[] = $order_jawa;
        // $weekly2[] = $order_jabodetabek;

        // $data = [
        //     'params' => $params,
        //     'weekly' => $weekly,
        //     'weekly2' => $weekly2
        // ];
    }
    public function monthly()
    {
        // $params = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "August", "September", "October", "November", "December"];
        // $currentYear = Carbon::now();

        // for ($i = 0; $i < 12; $i++) {
        //     $start    =  Carbon::now()->startOfYear()->addMonth($i)->format('Y-m-d');
        //     $end      =  Carbon::now()->startOfYear()->endOfMonth()->addMonth($i)->format('Y-m-d');
        //     $order_jawa[] = Order::whereHas('route', function ($q) {
        //         $q->where('area_id', '1');
        //     })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->get()->count();
        //     $order_jabodetabek[] = Order::whereHas('route', function ($q) {
        //         $q->where('area_id', '2');
        //     })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->get()->count();
        // }
        // $weekly[] = $order_jawa;
        // $weekly2[] = $order_jabodetabek;

        // $data = [
        //     'params' => $params,
        //     'weekly' => $weekly,
        //     'weekly2' => $weekly2
        // ];
    }
    public function yearly()
    {
        // $params = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "August", "September", "October", "November", "December"];
        // $currentYear = Carbon::now();

        // for ($i = 0; $i < 12; $i++) {
        //     $start    =  Carbon::now()->startOfYear()->addMonth($i)->format('Y-m-d');
        //     $end      =  Carbon::now()->startOfYear()->endOfMonth()->addMonth($i)->format('Y-m-d');
        //     $order_jawa[] = Order::whereHas('route', function ($q) {
        //         $q->where('area_id', '1');
        //     })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->get()->count();
        //     $order_jabodetabek[] = Order::whereHas('route', function ($q) {
        //         $q->where('area_id', '2');
        //     })->whereDate('reserve_at', '>=', $start)->whereDate('reserve_at', '<=', $end)->get()->count();
        // }
        // $weekly[] = $order_jawa;
        // $weekly2[] = $order_jabodetabek;

        // $data = [
        //     'params' => $params,
        //     'weekly' => $weekly,
        //     'weekly2' => $weekly2
        // ];
    }
}
