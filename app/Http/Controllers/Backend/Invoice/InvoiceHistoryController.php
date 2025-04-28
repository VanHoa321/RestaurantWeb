<?php

namespace App\Http\Controllers\backend\invoice;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;

class InvoiceHistoryController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with("customer", "booking", "table", "user")->orderBy("created_date", "desc")->get();
        $groupedInvoices = $invoices->groupBy("status");
        return view("backend.invoice.invoice-history.index", compact("groupedInvoices"));
    }

    public function detail($id)
    {
        $invoice = Invoice::with("customer", "booking", "table", "user")->where("id", $id)->first();
        $booking = Booking::where("id", $invoice->booking_id)->first();
        $details = InvoiceDetail::with("item", "combo")->where("invoice_id", $id)->get();
        return view("backend.invoice.invoice-history.detail", compact("invoice", "booking", "details"));
    }
}
