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
        $booking = new BookingInterface(
            $request->route_id,
            $request->layout_chair_id,
            BookingService::getCurrentExpiredAt(),
            UserRepository::findByToken($request->bearerToken())?->id
        );
        $booking = BookingService::create($booking);
    
        return $this->sendSuccessResponse([
            'booking'=>$booking
        ], 'Berhasil membuat booking');
    }
}
