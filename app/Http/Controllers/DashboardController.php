<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::all();
        $orders = Order::count();
        $count_user = User::doesntHave('agencies')->count();
        $orders_money = Order::has('route')->sum('price');

        return view('dashboard', compact('users', 'orders', 'count_user', 'orders_money'));
    }
}
