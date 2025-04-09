<?php

namespace App\Http\Controllers\backend\invoice;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Booking;
use App\Models\Combo;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceCancel;
use App\Models\InvoiceDetail;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

use function Laravel\Prompts\table;

class InvoiceController extends Controller
{
    public function index()
    {
        $items = Invoice::with("customer", "booking", "table", "user")
            ->whereDate("time_in", Carbon::today()) 
            ->where("status", 1)
            ->orderBy("time_in", "desc")
            ->get();

        return view("backend.invoice.present.index", compact("items"));
    }

    public function create()
    {
        $branch_id = Auth::user()->branch_id;
        $customers = Customer::where("is_active", 1)->where("id", ">", 1)->get();
        $tables = Table::with(["area", "type"])->where("is_active", 1)->where("status", 1)
        ->whereHas("area", function ($query) use ($branch_id) {
            $query->where("branch_id", $branch_id);
        })->get();
        $menus = Menu::where("is_active", 1)->orderBy("order_menu", "asc")->get();
        $menu_items = MenuItem::orderBy("id", "asc")->get();
        $combos = Combo::where("is_active", 1)->get();
        return view("backend.invoice.present.create", compact("customers", "tables", "menus", "menu_items", "combos"));
    }

    public function store(Request $request)
    {
        $items = $request->items;
        $customer_id = $request->customer_id;
        $table_id = $request->table_id == "null" ? null : $request->table_id;

        $invoice = Invoice::create([
            "customer_id" => $customer_id,
            "table_id" => $table_id,
            "user_id" => Auth::user()->id,
            "created_date" => now(),
            "time_in" => now(),
            "total_cost" => 0
        ]);

        foreach ($items as $item) {
            $id = $item["id"];
            $quantity = $item["quantity"];
            $price = $item["price"];
            $amount = $item["total"];
    
            if (Str::startsWith($id, "combo-")) {
                $comboId = str_replace("combo-", "", $id);
                $itemId = null;
            } else {
                $comboId = null;
                $itemId = str_replace("item-", "", $id);
            }
    
            InvoiceDetail::create([
                "invoice_id" => $invoice->id,
                "item_id" => $itemId,
                "combo_id" => $comboId,
                "quantity" => $quantity,
                "price" => $price,
                "amount" => $amount
            ]);
        }
    
        return response()->json(["success" => true, "message" => "Tạo hóa đơn thành công"]);
    }

    public function tableArrangement($id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return redirect()->back()->with("error", "Không tìm thấy hóa đơn");
        }

        $booking = $invoice->booking_id ? Booking::with("customer")->find($invoice->booking_id) : null;
        $time_slot = $booking ? $booking->time_slot : null;

        $areas = Area::with("branch")
            ->where("is_active", 1)
            ->where("branch_id", Auth::user()->branch_id)
            ->get();

        $area_ids = $areas->pluck("id");

        $tables = Table::with("area", "type")
            ->whereIn("area_id", $area_ids)
            ->whereIn("status", [1, 2]);

        if ($time_slot) {
            $tables->whereDoesntHave("bookings", function ($query) use ($time_slot) {
                $query->where("time_slot", "=", $time_slot);
            });
        }

        $tables = $tables->get();
        return view("backend.invoice.present.table-arrangement", compact("invoice", "areas", "tables"));
    }

    public function postArrangement($id, $table_id)
    {
        $invoice = Invoice::find($id);
        if ($invoice) {
            if ($invoice->table_id) {
                $oldTable = Table::find($invoice->table_id);
                if ($oldTable) {
                    $hasOtherInvoices = Invoice::where("table_id", $oldTable->id)
                        ->where("id", "!=", $id)
                        ->where("status", "!=", 0)
                        ->exists();
    
                    if (!$hasOtherInvoices) {
                        $oldTable->status = 1;
                        $oldTable->save();
                    }
                }
            }
            $invoice->table_id = $table_id;
            $invoice->save();
            $table = Table::find($table_id);
            if ($table) {
                $table->status = 3;
                $table->save();
            }
            return redirect()->route("inv-present.index");
        }
    }

    public function selectItem($id)
    {
        $invoice = Invoice::find($id);
        $details = InvoiceDetail::with("item", "combo")->where("invoice_id", $id)->get();
        $menus = Menu::where("is_active", 1)->orderBy("order_menu", "asc")->get();
        $menu_items = MenuItem::orderBy("id", "asc")->get();
        $combos = Combo::where("is_active", 1)->get();
        return view("backend.invoice.present.select-item", compact("invoice", "details", "menus", "menu_items", "combos"));
    }

    public function updateItem(Request $request)
    {
        $invoiceId = $request->invoice_id;
        $items = $request->items;

        if (!$invoiceId || empty($items)) {
            return response()->json(["success" => false, "message" => "Dữ liệu không hợp lệ"]);
        }

        // Lấy danh sách item_id và combo_id từ request
        $existingIds = collect($items)->map(function ($item) {
            if (Str::startsWith($item["id"], "combo-")) {
                return ["combo_id" => str_replace("combo-", "", $item["id"]), "item_id" => null];
            } else {
                return ["combo_id" => null, "item_id" => str_replace("item-", "", $item["id"])];
            }
        });

        $invoiceDetails = InvoiceDetail::where("invoice_id", $invoiceId)->get();

        foreach ($items as $item) {
            $id = $item["id"];
            $quantity = $item["quantity"];
            $price = $item["price"];
            $amount = $item["total"];

            if (Str::startsWith($id, "combo-")) {
                $comboId = str_replace("combo-", "", $id);
                $itemId = null;
            } else {
                $comboId = null;
                $itemId = str_replace("item-", "", $id);
            }

            // Kiểm tra xem đã tồn tại chưa
            $invoiceDetail = $invoiceDetails->where("invoice_id", $invoiceId)
                ->where("item_id", $itemId)
                ->where("combo_id", $comboId)
                ->first();

            if ($invoiceDetail) {
                $invoiceDetail->update([
                    "quantity" => $quantity,
                    "price" => $price,
                    "amount" => $amount
                ]);
            } else {
                InvoiceDetail::create([
                    "invoice_id" => $invoiceId,
                    "item_id" => $itemId,
                    "combo_id" => $comboId,
                    "quantity" => $quantity,
                    "price" => $price,
                    "amount" => $amount
                ]);
            }
        }

        $existingItemIds = $existingIds->pluck("item_id")->filter()->all();
        $existingComboIds = $existingIds->pluck("combo_id")->filter()->all();
        // Xóa các mục không có trong danh sách chỉnh sửa
        InvoiceDetail::where("invoice_id", $invoiceId)
        ->where(function ($query) use ($existingItemIds, $existingComboIds) {
            if (!empty($existingItemIds)) {
                $query->whereNotNull("item_id")->whereNotIn("item_id", $existingItemIds);
            } else {
                $query->whereNotNull("item_id"); // Nếu danh sách item rỗng => xóa tất cả item
            }

            if (!empty($existingComboIds)) {
                $query->orWhere(function ($q) use ($existingComboIds) {
                    $q->whereNotNull("combo_id")->whereNotIn("combo_id", $existingComboIds);
                });
            } else {
                $query->orWhereNotNull("combo_id");
            }
        })
        ->delete();

        return response()->json(["success" => true, "message" => "Cập nhật thành công!"]);
    }

    public function getInvoice($id)
    {
        $invoice = Invoice::with("customer", "table")->where("id", $id)->first();
        if($invoice)
        {
            return response()->json(["success" => true, "invoice" => $invoice]);
        }
        return response()->json(["success" => false]);
    }

    public function paymentInvoice(Request $request, $id)
    {
        $invoice = Invoice::find($id);
    
        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Hóa đơn không tồn tại'], 404);
        }
        $invoice->update([
            'time_out' => now(),
            'total_amount' => $request->total,
            'payment_time' => now(),
            'status' => 2
        ]);

        if ($invoice->booking_id) {
            Booking::where('id', $invoice->booking_id)->update(['status' => 3]);
        }

        $table_id = $invoice->table_id;
        if ($table_id) {
            $query  = Booking::where('table_id', $table_id)->where('status', '=', 1);

            if ($invoice->booking_id) {
                $query = $query->where('id', '!=', $invoice->booking_id);
            }
            if ($query->count() == 0) {
                Table::where('id', $table_id)->update(['status' => 1]);
            }
            else{
                Table::where('id', $table_id)->update(['status' => 2]);
            }
        }
        return response()->json(['success' => true, 'message' => 'Thanh toán thành công']);
    }

    public function generatePDF($id)
    {
        $invoice = Invoice::with("customer", "booking", "user", "table")->where("id", $id)->first();
        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy hóa đơn'], 404);
        }
        $time = now();
        $details = InvoiceDetail::with("item", "combo")->where("invoice_id", $id)->get();
        $pdf = Pdf::loadView('backend.invoice.present.invoice-pdf', compact('invoice', 'time', 'details'))
                ->setPaper('A4')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans'
                ]);
        return $pdf->stream('hoa_don_' . $invoice->id . '.pdf');
    }


    public function cancelInvoice(Request $request, $id)
    {
        $cancel = Invoice::find($id);
        if (!$cancel) {
            return response()->json(['success' => false, 'message' => 'Có lỗi khi hủy hóa đơn']);
        }

        $cancel->status = 0;
        $cancel->save();

        if (!empty($cancel->booking_id)) {
            $booking = Booking::find($cancel->booking_id);
            if ($booking) {
                $booking->status = 0;
                $booking->save();
            }
        }

        InvoiceCancel::create([
            'invoice_id' => $id,
            'cancel_reason' => $request->reason,
            'cancel_date' => Carbon::now(),
            'cancel_by' => Auth::user()->name
        ]);

        $table_id = $cancel->table_id;
        if ($table_id) {
            $existingBookings = Booking::where('table_id', $table_id)->where('status', '=', 1);

            if (!empty($cancel->booking_id)) {
                $existingBookings->where('id', '!=', $cancel->booking_id);
            }

            if ($existingBookings->count() == 0) {
                $table = Table::find($table_id);
                if ($table) {
                    $table->status = 1;
                    $table->save();
                }
            }
            else{
                $table = Table::find($table_id);
                if ($table) {
                    $table->status = 2;
                    $table->save();
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Hủy hóa đơn thành công']);
    }
}
