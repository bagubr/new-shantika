<?php

namespace App\Http\Controllers;

use App\Models\FoodRedeemHistory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Restaurant;
use App\Models\RestaurantAdmin;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RestaurantBarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->restaurant_admin->value('restaurant_id');
        $restaurant = Restaurant::where('id', $user)->first();
        // return view('restaurant.show_user', compact('restaurant'));
        return view('restaurant_barcode.index', compact('restaurant'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getOrderId(Request $request)
    {
        $order = Order::where('code_order', $request->code_order)->get();
        return $order;
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code_order' => 'required'
        ]);
        $code_order = explode('|', $data['code_order']);
        $data['code_order'] = $code_order[0];
        $data['layout_chair_name'] = $code_order[1];

        $order = OrderRepository::findByCodeOrder($data['code_order']);
        $redeems = FoodRedeemHistory::where('order_id', $order->id)->get();
        $order_detail = OrderDetail::whereHas('chair', function ($query) use ($data) {
            $query->where('name', 'ilike', "'%" . $data['layour_chair_name'] . "%'");
        })->first() ?? $this->sendFailedResponse([], 'Data order tidak ditemukan');
        $ticket_count = count($order->order_detail);
        $auth = Auth::user();
        $restaurant_admin = @RestaurantAdmin::where('admin_id', $auth->id)->first()->restaurant_id;
        if (empty($order)) $this->sendFailedResponse([], 'Kode order tidak ditemukan');
        if ($redeems >= $ticket_count) $this->sendFailedResponse([], 'Anda sudah meredeem kode order ini sebanyak jumlah tiket anda');
        if (empty($restaurant_admin)) $this->sendFailedResponse([], 'ID Anda tidak ditemukan');

        $history = FoodRedeemHistory::create([
            'order_detail_id' => $order_detail->id,
            'restaurant_id' => $restaurant_admin->restaurant_id
        ]);

        return $this->sendSuccessResponse([
            'history' => $history
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
