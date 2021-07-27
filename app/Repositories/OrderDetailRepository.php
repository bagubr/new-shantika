<?php

namespace App\Repositories;

use App\Models\OrderDetail;

class OrderDetailRepository
{
    public static function findById($order)
    {
        return OrderDetail::where('order_id', $order)->get();
    }
    public static function findByCodeTicket($code_ticket)
    {
        return OrderDetail::where('code_ticket', $code_ticket)->first();
    }
}
