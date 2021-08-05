<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiBookingRequest;
use App\Interfaces\BookingInterface;
use App\Models\Booking;
use App\Models\Route;
use App\Repositories\UserRepository;
use App\Services\BookingService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function booking(ApiBookingRequest $request) {
        $booking = [];
        DB::beginTransaction();
        $code_booking = 'BO-'.date('Ymdhis').'-'.strtoupper(uniqid());
        $max_date = date('Y-m-d', strtotime("+30 days"));
        if($request->booking_at > $max_date) {
            $this->sendFailedResponse([], 'Kamu tidak bisa membooking lebih dari '.$max_date);
        }
        foreach($request->layout_chair_id as $layout_chair_id) {
            $_booking = new Booking([
                'code_booking'=>$code_booking,
                'route_id'=>$request->route_id,
                'layout_chair_id'=>$layout_chair_id,
                'booking_at'=>$request->booking_at,
                'expired_at'=>BookingService::getCurrentExpiredAt(),
                'user_id'=>UserRepository::findByToken($request->bearerToken())?->id
            ]);
            $booking[] = BookingService::create($_booking);
        }
        DB::commit();
    
        return $this->sendSuccessResponse([
            'booking'=>$booking
        ], 'Berhasil membuat booking');
    }
}
