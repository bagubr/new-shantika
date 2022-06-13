<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $order_detail = OrderDetail::find($id);
        $order_detail->update($data);
        $order_detail->refresh();
        return response(['data' => $order_detail, 'code' => 1], 200);
    }
}
