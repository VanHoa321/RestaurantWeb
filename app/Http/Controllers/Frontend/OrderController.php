<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    
        $discount = 0;
        $total = $subtotal - $discount;
        $branchs = Branch::where("is_active", 1)->get();
        return view('frontend.order.index', compact('cart', 'subtotal', 'discount', 'total', 'branchs'));
    }

    public function vnpay_payment(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = "F23SUP2A";
        $vnp_HashSecret = "JB7QJNX2P9Z3JXTX8X8Q4ADXIS1QHSY3";

        $vnp_TxnRef = time();
        $vnp_OrderInfo = json_encode([
            'branch_id' => $request->branch_id,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'guest_count' => $request->guest_count,
            'booking_date' => $request->booking_date,
            'time_slot' => $request->time_slot,
            'note' => $request->note
        ]);
        $vnp_OrderType = "Restaurant";
        $vnp_Amount = $request->total * 100;
        $vnp_Locale = "vn";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $query = trim($query, "&");

        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', ltrim($hashdata, '&'), $vnp_HashSecret);
        $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $responseCode = $request->get('vnp_ResponseCode');
        $orderData = json_decode(urldecode($request->get('vnp_OrderInfo')), true);
        $amount = $request->get('vnp_Amount') / 100;
        if ($responseCode == '24') {
            return redirect('/order');
        }

        if ($responseCode == '00') {
            $checkCustomer = Customer::where("phone", $orderData['phone'])->first();
            if(!$checkCustomer){
                $cusData = [
                    'full_name' => $orderData['full_name'],
                    'phone' => $orderData['phone']
                ];
                $cusNew = Customer::create($cusData);
                $cus_id = $cusNew->id;
            }
            else
            {
                $cus_id = $checkCustomer->id;
            }

            $bookingData = [
                'customer_id' => $cus_id,
                'branch_id' => $orderData['branch_id'],
                'booking_date' => $orderData['booking_date'],
                'time_slot' => $orderData['time_slot'],
                'guest_count' => $orderData['guest_count'],
                'pre_payment' => $amount,
                'note' => $orderData['note']
            ];
            $booking = Booking::create($bookingData);

            $invoiceData = [
                'customer_id' => $cus_id,
                'booking_id' => $booking->id,
                'created_date' => now(),
                'total_cost' => $amount,
            ];
            $invoice = Invoice::create($invoiceData);

            $cart = session()->get('cart', []);
            foreach ($cart as $item) {
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $item['type'] === 'item' ? $item['id'] : null, 
                    'combo_id' => $item['type'] === 'combo' ? $item['id'] : null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'amount' => $item['quantity'] * $item['price'],
                ]);
            }
            session()->forget('cart');

            return redirect('/booking-success');
        }
    }

    public function bookingSuccess()
    {
        return view("frontend.order.booking-success");
    }
}
