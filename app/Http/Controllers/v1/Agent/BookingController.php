<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiBookingRequest;
use App\Interfaces\BookingInterface;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Route;
use App\Models\ScheduleUnavailableBooking;
use App\Models\Setting;
use App\Models\TimeClassification;
use App\Repositories\BookingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Services\BookingService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function booking(ApiBookingRequest $request) {

        $booking = [];
        $user = UserRepository::findByToken($request->bearerToken());
        // dd($user);
        $is_exist = BookingRepository::isBooked($request->fleet_route_id, $user->id, $request->layout_chair_id, $request->booking_at, $request->time_classification_id);
        if($is_exist) {
            return $this->sendFailedResponse([], 'Maaf kursi ini sudah dibooking');
        }
        $is_exist = OrderRepository::isOrderUnavailable($request->fleet_route_id, $request->booking_at, $request->layout_chair_id, $request->time_classification_id);
        if($is_exist) {
            return $this->sendFailedResponse([], "Maaf kursi ini sudah dipesan");
        }
        
        DB::beginTransaction();
        try {
            $code_booking = 'BO-'.date('Ymdhis').'-'.strtoupper(uniqid());
            foreach($request->layout_chair_id as $layout_chair_id) {
                $_booking = new Booking([
                    'code_booking'=>$code_booking,
                    'fleet_route_id'=>$request->fleet_route_id,
                    'time_classification_id'=>$request->time_classification_id,
                    'destination_agency_id'=>$request->destination_agency_id,
                    'layout_chair_id'=>$layout_chair_id,
                    'booking_at'=>$request->booking_at,
                    'user_id'=>$user->id
                ]);
                $booking[] = BookingService::create($_booking);
            }
            DB::commit();
        } catch (\Throwable $th) {
            return $this->sendSuccessResponse([], 'Gagal melakukan booking, coba lagi nanti');
            DB::rollBack();
        }
    
        return $this->sendSuccessResponse([
            'booking'=>$booking
        ], 'Berhasil membuat booking');
    }

    public function bookingExpiredDuration() {
        $duration = Setting::first()->booking_expired_duration;
        return $this->sendSuccessResponse([
            'booking_expired_duration'=>$duration.' menit'
        ]);
    }
}
