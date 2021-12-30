<?php

namespace App\Services;

use App\Http\Resources\Agency\AgencyWithAddressTelpResource;
use App\Http\Resources\Agency\AgencyWithCityResource;
use App\Models\BlockedChair;
use App\Models\Booking;
use App\Models\FleetRoute;
use App\Models\Layout;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Route;
use App\Repositories\BookingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;

class LayoutService
{
    public static function getAvailibilityChairs(Layout $layout, FleetRoute $fleet_route, $date = null, $time_classification_id = null)
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }
        $user_id = UserRepository::findByToken(request()->bearerToken())?->id;
        $booking = BookingRepository::getTodayByRoute($fleet_route->id, $time_classification_id);
        $unavailable = OrderRepository::getAtDateByFleetRouteId($date, $fleet_route->id, $time_classification_id);
        $unavailable_customer = OrderRepository::getAtDateByFleetRouteIdCustomer($date, $fleet_route->id, $time_classification_id);

        $layout->chairs = $layout->chairs->map(function ($item) use ($fleet_route, $unavailable, $booking, $user_id, $unavailable_customer) {
            $item->is_booking = $booking->where('layout_chair_id', $item->id)->isNotEmpty();
            $item->is_unavailable_customer = $unavailable_customer->filter(function ($e) use ($item) {
                return $e->order_detail->where('layout_chair_id', $item->id)->first();
            })->isNotEmpty() || BlockedChair::where('fleet_route_id', $fleet_route->id)->where('layout_chair_id', $item->id)->exists();
            $item->is_unavailable = $unavailable->filter(function ($e) use ($item) {
                return $e->order_detail->where('layout_chair_id', $item->id)->first();
            })->isNotEmpty() || BlockedChair::where('fleet_route_id', $fleet_route->id)->where('layout_chair_id', $item->id)->exists();
            $item->is_mine = $unavailable->filter(function ($e) use ($item, $user_id) {
                return $e->order_detail->where('layout_chair_id', $item->id)->isNotEmpty() && $e->user_id == $user_id && $e->user_id != null;
            })->isNotEmpty();
            return $item;
        });

        return $layout;
    }

    public static function getAvailibilityChairsDetail(Layout $layout, FleetRoute $fleet_route, $date = null, $time_classification_id = null)
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }
        $booking = BookingRepository::getTodayByRoute($fleet_route->id, $time_classification_id);
        $unavailable = OrderRepository::getAtDateByFleetRouteId($date, $fleet_route->id, $time_classification_id);
        $unavailable_customer = OrderRepository::getAtDateByFleetRouteIdCustomer($date, $fleet_route->id, $time_classification_id);

        $layout->chairs = $layout->chairs->map(function ($item) use ($fleet_route, $date, $layout, $unavailable, $booking, $unavailable_customer) {
            $item->is_booking = $booking->where('layout_chair_id', $item->id)->isNotEmpty();
            $item->is_unavailable = $unavailable->filter(function ($e) use ($item) {
                return $e->order_detail->where('layout_chair_id', $item->id)->first();
            })->isNotEmpty() || BlockedChair::where('fleet_route_id', $fleet_route->id)->where('layout_chair_id', $item->id)->exists();

            $item->is_unavailable_customer = $unavailable_customer->filter(function ($e) use ($item) {
                return $e->order_detail->where('layout_chair_id', $item->id)->first();
            })->isNotEmpty() || BlockedChair::where('fleet_route_id', $fleet_route->id)->where('layout_chair_id', $item->id)->exists();

            if ($item->is_booking) {
                $item->booking_detail = $booking->filter(function ($value) use ($item) {
                    return $value->layout_chair_id == $item->id;
                })->first();
                $item->code = $booking->where('layout_chair_id', $item->id)->first()?->user?->agencies?->agent?->code;
            }
            if ($item->is_unavailable) {
                $item->order_detail = $unavailable->filter(function ($value) use ($item) {
                    return $value->order_detail->where('layout_chair_id', $item->id)->isNotEmpty();
                })->first();
                $item->code = $unavailable->filter(function ($value) use ($item) {
                    return $value->order_detail->where('layout_chair_id', $item->id)->isNotEmpty();
                })->first()?->agency?->code;
            }
            if ($item->is_unavailable_customer) {
                $item->order_detail = $unavailable_customer->filter(function ($value) use ($item) {
                    return $value->order_detail->where('layout_chair_id', $item->id)->isNotEmpty();
                })->first();
                $item->code = $unavailable_customer->filter(function ($value) use ($item) {
                    return $value->order_detail->where('layout_chair_id', $item->id)->isNotEmpty();
                })->first()?->agency?->code;
            }
            return $item;
        });

        return $layout;
    }
}
