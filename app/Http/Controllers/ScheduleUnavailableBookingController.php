<?php

namespace App\Http\Controllers;

use App\Models\ScheduleUnavailableBooking;
use Illuminate\Http\Request;

class ScheduleUnavailableBookingController extends Controller
{
    public function index(Request $request) {
        return view('schedule_unavailable_booking.index', [
            'schedule_unavailable_bookings'=>ScheduleUnavailableBooking::all()
        ]);
    }

    public function store(Request $request) {
        $schedule = ScheduleUnavailableBooking::create($request->only(['start_at', 'end_at', 'note']));

        return back()->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy($id) {
        $schedule = ScheduleUnavailableBooking::find($id);
        $schedule->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
