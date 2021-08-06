<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Fleet;
use App\Models\Order;
use App\Models\Route;
use App\Models\User;

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
        $users = User::all();
        $orders = Order::paginate(7);
        $count_user = User::doesntHave('agencies')->count();
        $orders_money = Order::has('route')->sum('price');
        return view('dashboard', compact('users', 'orders', 'count_user', 'orders_money', 'agencies', 'fleets', 'routes'));
    }
}
