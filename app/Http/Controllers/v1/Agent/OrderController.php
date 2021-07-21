<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiBookingRequest;
use App\Models\Booking;
use App\Models\Route;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function booking(ApiBookingRequest $request) {
        $data = (object) $request->data;    
        $route_id = $data->route_id;
        $route = Route::find($route_id) 
            ?? $this->sendFailedResponse([], 'Wah, tiket yang anda inginkan tidak ditemukan');
        foreach($data->form as $form) {
            response($form)->send();
        }
    }
}
