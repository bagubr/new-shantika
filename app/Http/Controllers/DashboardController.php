<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::all();
        $orders = Order::all();
        $agencies = Agency::get(['id', 'name']);
        $count_user = User::doesntHave('agencies')->count();
        $orders_money = Order::has('route')->sum('price');
        return view('dashboard', compact('users', 'orders', 'count_user', 'orders_money', 'agencies'));
    }
}
