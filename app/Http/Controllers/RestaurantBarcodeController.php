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
            'code_order' => 'required',
        ]);
        // $code_order = explode('|', $data['code_order']);
        $data['code_order'] = $request->code_order;
        $data['layout_chair_name'] = $request->layout_chair_name;

        // $order = OrderRepository::findByCodeOrder($data['code_order']);
        $order = Order::where('code_order', $data['code_order'])->first();
        // $redeems = FoodRedeemHistory::where('order_detail_id', $order->order_detail->first()->id)->get();

        $order_detail = OrderDetail::where('order_id', $order->id)->whereHas('chair', function ($query) use ($data) {
            $query->where('name', 'ilike', '%' . $data['layout_chair_name'] . '%');
        })->first() ?? $this->sendFailedResponse([], 'Data order tidak ditemukan');

        $redeems = FoodRedeemHistory::where('order_detail_id', $order_detail->id)->first();

        $auth = Auth::user();
        $restaurant_admin = RestaurantAdmin::where('admin_id', $auth->id)->first()->restaurant_id;

        if (empty($order)) $this->sendFailedResponse([], 'Kode order tidak ditemukan');
        if (!empty($redeems) && $order_detail->id == $redeems->order_detail_id) {
            $this->sendFailedResponse([], 'Anda sudah menggunakan kupon makan ini');
        }
        if (empty($restaurant_admin)) $this->sendFailedResponse([], 'ID Anda tidak ditemukan');

        $history = FoodRedeemHistory::create([
            'order_detail_id' => $order_detail->id,
            'restaurant_id' => $restaurant_admin
        ]);


        return $this->sendSuccessResponse([
            'history' => $history,
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
