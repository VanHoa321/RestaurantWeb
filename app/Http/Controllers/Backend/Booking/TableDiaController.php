<?php

namespace App\Http\Controllers\Backend\booking;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TableDiaController extends Controller
{
    public function index() 
    {
        $areas = Area::with("branch")->where("is_active", 1)->where("branch_id", Auth::user()->branch_id)->get();
        $area_ids = $areas->pluck("id");
        $tables = Table::with("area", "type")->whereIn("area_id", $area_ids)->get();
        return view("backend.booking.table-dia.index", compact("areas", "tables"));
    }

    public function getBookingByTableId($id)
    {
        $table_name = Table::find($id)->name;
        $bookings = Booking::with("customer", "table")->where("table_id", $id)->where("status", 1)->get();
        if(!$bookings)
        {
            return response()->json(['success' => false, 'message' => 'Không có đơn đặt trước cho bàn này'], 200);
        }
        return response()->json(['success' => true, 'bookings' => $bookings, 'table_name' => $table_name ]);
    }

    public function getInvoiceByTableId($id)
    {
        $table_name = Table::find($id)->name;
        $invoice = Invoice::with("customer", "table")->where("table_id", $id)->where("status", 1)->first();
        if(!$invoice)
        {
            return response()->json(['success' => false, 'message' => 'Không có hóa đơn hiện thời cho bàn này'], 200);
        }
        return response()->json(['success' => true, 'invoice' => $invoice, 'table_name' => $table_name ]);
    }
}
