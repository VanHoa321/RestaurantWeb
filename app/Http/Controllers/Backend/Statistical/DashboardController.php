<?php

namespace App\Http\Controllers\backend\statistical;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $timeType = $request->get('timeType', 'all');
            $startDate = $request->get('startDate', null);
            $endDate = $request->get('endDate', null);
            $year = $request->get('year', Carbon::now()->year);

            $topItems = $this->getTopItems($timeType, $startDate, $endDate);
            $topCombos = $this->getTopCombos($timeType, $startDate, $endDate);
            $monthlyRevenue = $this->getMonthlyRevenue($year);
            $invoice = $this->getInvoices($timeType, $startDate, $endDate);
            $topCustomers = $this->getTopCustomers($timeType, $startDate, $endDate);

            return response()->json([
                'topItems' => $topItems,
                'topCombos' => $topCombos,
                'monthlyRevenue' => $monthlyRevenue,
                'invoice' => $invoice,
                'topCustomers' => $topCustomers,
            ]);
        }

        $itemCount = Item::count();
        $customerCount = Customer::where("id", ">", 1)->count();
        $bookingCount = Booking::where("status", 1)->count();
        $invoiceCount = Invoice::where("status", 1)->count();
        return view("backend.statistical.index", compact("itemCount", "customerCount", "bookingCount", "invoiceCount"));
    }

    public function getTopItems($timeType, $startDate, $endDate)
    {
        $topItems = DB::table('invoice_details')
        ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
        ->join('items', 'invoice_details.item_id', '=', 'items.id')
        ->select('items.name', DB::raw('SUM(invoice_details.quantity) as total'))
        ->where('invoices.status', 2)
        ->whereNotNull('invoice_details.item_id');
        
        // Lọc theo khoảng thời gian nếu có
        if ($startDate && $endDate) {
            $topItems->whereBetween('invoices.created_date', [$startDate, $endDate]);
        } 
        elseif ($timeType !== 'all') {
            $now = Carbon::now();
            if ($timeType == 'filterDay') {
                $topItems->whereDate('invoices.created_date', $now->toDateString());
            } 
            elseif ($timeType == 'filterWeek') {
                $topItems->whereBetween('invoices.created_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString(),
                ]);
            } 
            elseif ($timeType == 'filterMonth') {
                $topItems->whereMonth('invoices.created_date', $now->month);
            } 
            elseif ($timeType == 'filterYear') {
                $topItems->whereYear('invoices.created_date', $now->year);
            }
        }

        // Top 5 món ăn bán chạy
        return $topItems->groupBy('invoice_details.item_id', 'items.name')
        ->orderByDesc('total')
        ->limit(5)
        ->get();
    }

    public function getTopCombos($timeType, $startDate, $endDate)
    {
        $topCombos = DB::table('invoice_details')
        ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
        ->join('combos', 'invoice_details.combo_id', '=', 'combos.id')
        ->select('combos.name', DB::raw('SUM(invoice_details.quantity) as total'))
        ->where('invoices.status', 2)
        ->whereNotNull('invoice_details.combo_id');

        // Lọc theo khoảng thời gian nếu có
        if ($startDate && $endDate) {
            $topCombos->whereBetween('invoices.created_date', [$startDate, $endDate]);
        } 
        elseif ($timeType !== 'all') {
            $now = Carbon::now();
            if ($timeType == 'filterDay') {
                $topCombos->whereDate('invoices.created_date', $now->toDateString());
            } 
            elseif ($timeType == 'filterWeek') {
                $topCombos->whereBetween('invoices.created_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString(),
                ]);
            } 
            elseif ($timeType == 'filterMonth') {
                $topCombos->whereMonth('invoices.created_date', $now->month);
            } 
            elseif ($timeType == 'filterYear') {
                $topCombos->whereYear('invoices.created_date', $now->year);
            }
        }

        // Top 5 combo bán chạy
        return $topCombos->groupBy('invoice_details.combo_id', 'combos.name')
        ->orderByDesc('total')
        ->limit(5)
        ->get();
    }

    public function getMonthlyRevenue($year)
    {
        return DB::table('invoices')
        ->selectRaw('MONTH(created_date) as month, SUM(total_amount) as total')
        ->whereYear('created_date', $year)
        ->where('status', 2)
        ->groupBy(DB::raw('MONTH(created_date)'))
        ->orderBy('month')
        ->get();
    }

    public function getInvoices($timeType, $startDate, $endDate)
    {
        $invoices = DB::table('invoices')
        ->join('customers', 'invoices.customer_id', '=', 'customers.id')
        ->select(
            'invoices.id as id',
            'customers.avatar as avatar',
            'customers.full_name as name',
            'customers.phone as phone',
            'invoices.time_in as time_in',
            'invoices.time_out as time_out',
            'invoices.total_amount as total_amount'
        )
        ->where('invoices.status', 2);

        // Lọc theo khoảng thời gian
        if ($startDate && $endDate) {
            $invoices->whereBetween('invoices.created_date', [$startDate, $endDate]);
        } 
        elseif ($timeType !== 'all') {
            $now = Carbon::now();
            if ($timeType == 'filterDay') {
                $invoices->whereDate('invoices.created_date', $now->toDateString());
            } 
            elseif ($timeType == 'filterWeek') {
                $invoices->whereBetween('invoices.created_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString(),
                ]);
            } 
            elseif ($timeType == 'filterMonth') {
                $invoices->whereMonth('invoices.created_date', $now->month);
            } 
            elseif ($timeType == 'filterYear') {
                $invoices->whereYear('invoices.created_date', $now->year);
            }
        }
        return $invoices->orderByDesc('invoices.created_date')->get();
    }

    public function getTopCustomers($timeType, $startDate, $endDate)
    {
        $query = DB::table('invoices')
        ->join('customers', 'invoices.customer_id', '=', 'customers.id')
        ->select(
            'customers.id as id',
            'customers.full_name as name',
            'customers.phone as phone',
            DB::raw('COUNT(invoices.id) as total'),
            DB::raw('SUM(invoices.total_amount) as total_amount')
        )
        ->where('invoices.status', 2);

        // Lọc theo thời gian nếu có
        if ($startDate && $endDate) {
            $query->whereBetween('invoices.created_date', [$startDate, $endDate]);
        } 
        elseif ($timeType !== 'all') {
            $now = Carbon::now();
            if ($timeType == 'filterDay') {
                $query->whereDate('invoices.created_date', $now->toDateString());
            } 
            elseif ($timeType == 'filterWeek') {
                $query->whereBetween('invoices.created_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString(),
                ]);
            } 
            elseif ($timeType == 'filterMonth') {
                $query->whereMonth('invoices.created_date', $now->month);
            } 
            elseif ($timeType == 'filterYear') {
                $query->whereYear('invoices.created_date', $now->year);
            }
        }

        $topCustomers = $query->groupBy('customers.id')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

        return $topCustomers;
    }
}
