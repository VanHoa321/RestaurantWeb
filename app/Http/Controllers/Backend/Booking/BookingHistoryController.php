<?php

namespace App\Http\Controllers\backend\booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use Illuminate\Http\Request;

class BookingHistoryController extends Controller
{
    public function index()
    {
        $bookings = Booking::with("customer", "branch", "table")->orderBy("booking_date", "desc")->get();
        $groupedBookings = $bookings->groupBy("status");
        return view("backend.booking.booking-history.index", compact("groupedBookings"));
    }

    public function detail($id)
    {
        $booking = Booking::with("customer", "branch", "table")->where("id", $id)->first();
        $invoice = Invoice::where("booking_id", $id)->first();
        return view("backend.booking.booking-history.detail", compact("booking", "invoice"));
    }
}
