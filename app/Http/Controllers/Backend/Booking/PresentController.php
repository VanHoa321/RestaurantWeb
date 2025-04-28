<?php

namespace App\Http\Controllers\Backend\booking;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Booking;
use App\Models\Combo;
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

use function Laravel\Prompts\table;

class PresentController extends Controller
{
    public function index()
    {
        $items = Booking::where("status", 1)->where("booking_date", Carbon::today())->where("branch_id", Auth::user()->branch_id)->orderBy("booking_date", "desc")->get();
        return view("backend.booking.present.index", compact("items"));
    }

    public function tableArrangement($id)
    {
        $booking = Booking::with("customer")->find($id);
        $time_slot = $booking->time_slot;
        $areas = Area::with("branch")->where("is_active", 1)->where("branch_id", Auth::user()->branch_id)->get();
        $area_ids = $areas->pluck("id");
        $tables = Table::with("area", "type")->whereIn("area_id", $area_ids)
        ->whereIn("status", [1, 2])
        ->whereDoesntHave("bookings", function ($query) use ($time_slot) {
            $query->where("time_slot", "=", $time_slot)
                  ->where("status", 1);
        })
        ->get();
        return view("backend.booking.present.table-arrangement", compact("booking", "areas", "tables"));
    }

    public function postArrangement($id, $table_id)
    {
        $booking = Booking::find($id);
        if ($booking) {
            if ($booking->table_id) {
                $oldTable = Table::find($booking->table_id);
                if ($oldTable) {
                    $hasOtherBookings = Booking::where("table_id", $oldTable->id)
                        ->where("id", "!=", $booking->id)
                        ->where("status", "!=", 0)
                        ->exists();
    
                    if (!$hasOtherBookings) {
                        $oldTable->status = 1;
                        $oldTable->save();
                    }
                }
            }
            $booking->table_id = $table_id;
            $booking->save();
            $table = Table::find($table_id);
            if ($table) {
                $table->status = 2;
                $table->save();
            }
            $invoice = Invoice::where("booking_id", $booking->id)->first();
            if ($invoice) {
                $invoice->table_id = $table_id;
                $invoice->user_id = Auth::user()->id;
                $invoice->save();
            }
            return redirect()->route("present.index");
        }
    }

    public function selectItem($id)
    {
        $booking = Booking::find($id);
        $invoice = Invoice::where("booking_id", $id)->first();
        $details = InvoiceDetail::with("item", "combo")->where("invoice_id", $invoice->id)->get();
        $menus = Menu::where("is_active", 1)->orderBy("order_menu", "asc")->get();
        $menu_items = MenuItem::orderBy("id", "asc")->get();
        $combos = Combo::where("is_active", 1)->get();
        return view("backend.booking.present.select-item", compact("booking", "invoice", "details", "menus", "menu_items", "combos"));
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

        // Lấy danh sách hiện có trong database
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

    public function customerTable(Request $request, $id)
    {
        $booking = Booking::find($id);
        if(!$booking->table_id){
            $request->session()->put("messenge", ["style"=>"danger","msg"=>"Vui lòng xếp bàn cho đơn này"]);
            return redirect()->route("present.index");
        }
        $table = Table::where("id", $booking->table_id)->first();
        $table->status = 3;
        $booking->status = 2;
        $invoice = Invoice::where("booking_id", $id)->first();
        $invoice->time_in = now();
        $table->save();
        $booking->save();
        $invoice->save();
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Khách nhận bàn thành công"]);
        return redirect()->route("present.index");
    }

    public function cancelBooking(Request $request, $id)
    {
        $cancel = Booking::find($id);
        if (!$cancel) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn đặt bàn']);
        }

        $cancel->status = 0;
        $cancel->save();

        $invoice = Invoice::where("booking_id", $id)->first();
        if ($invoice) {
            $invoice->status = 0;
            $invoice->save();

            InvoiceCancel::create([
                'invoice_id' => $invoice->id,
                'cancel_reason' => $request->reason,
                'cancel_date' => Carbon::now(),
                'cancel_by' => Auth::user()->name
            ]);
        }

        if ($cancel->table_id) {
            $existingBookings = Booking::where('table_id', $cancel->table_id)
                ->whereIn('status', [1, 2])
                ->where('id', '!=', $cancel->id)
                ->count();

            if ($existingBookings == 0) {
                $table = Table::find($cancel->table_id);
                if ($table) {
                    $table->status = 1;
                    $table->save();
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Hủy đơn đặt bàn thành công']);
    }

}
