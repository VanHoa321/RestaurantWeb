@extends('layout/web_layout')
@section('content')
    <main class="main-content">
        <div class="breadcrumb-area breadcrumb-height" data-bg-image="http://127.0.0.1:8000/storage/files/1/Slide/banner_1.png">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-lg-12">
                        <div class="breadcrumb-item">
                            <h2 class="breadcrumb-heading">Thông tin đặt bàn</h2>
                            <ul>
                                <li>
                                    <a href="{{ route("home.index") }}">Trang chủ <i class="pe-7s-angle-right"></i></a>
                                </li>
                                <li>Đặt bàn</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="checkout-area section-space-y-axis-100">
            <div class="container">
                @if (!Auth::guard("customer")->user())
                    <div class="row">
                        <div class="col-12">
                            <div class="coupon-accordion">
                                <h3>Đăng nhập để lưu thông tin lịch sử đặt bàn? <a href="{{ route("google.redirect") }}">Nhấn đây để đăng nhập</a></h3>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-12">
                            <div class="coupon-accordion">
                                <h3>Cập nhật thông tin? <a href="{{ route("google.redirect") }}">Nhấn đây để cập nhật thông tin khách hàng</a></h3>
                            </div>
                        </div>
                    </div>
                @endif
                @php
                    $customer = Auth::guard('customer')->user();
                @endphp
                <form action="{{ route("vnpay_payment") }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="checkbox-form">
                                <h3>Thông tin đặt bàn</h3>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="country-select clearfix">
                                            <label>Chọn cơ sở</label>
                                            <select name="branch_id" class="myniceselect nice-select wide">
                                                @foreach ($branchs as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }} - {{ $branch->address }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label>Họ tên <span class="required">*</span></label>
                                            <input name="full_name" placeholder="Nhập họ tên" type="text" value="{{ $customer ? $customer->full_name : '' }}" {{ $customer && $customer->full_name ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label>Số điện thoại <span class="required">*</span></label>
                                            <input name="phone" class="w-100" style="padding: 7px" placeholder="Nhập số điện thoại" type="number" value="{{ $customer ? $customer->phone : '' }}" {{ $customer && $customer->phone ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label>Số lượng khách <span class="required">*</span></label>
                                            <input name="guest_count" class="w-100" style="padding: 7px" placeholder="Nhập số lượng khách" type="number" min="1" max="16">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="checkout-form-list">
                                            <label>Chọn ngày <span class="required">*</span></label>
                                            <input name="booking_date" type="date" class="w-100" style="padding: 7px" id="dateInput">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="country-select clearfix">
                                            <label>Khung giờ</label>
                                            <select name="time_slot" class="myniceselect nice-select wide">
                                                <option value="8 giờ - 10 giờ">8 giờ - 10 giờ</option>
                                                <option value="10 giờ - 12 giờ">10 giờ - 12 giờ</option>
                                                <option value="13 giờ - 15 giờ">13 giờ - 15 giờ</option>
                                                <option value="15 giờ - 17 giờ">15 giờ - 17 giờ</option>
                                                <option value="17 giờ - 19 giờ">17 giờ - 19 giờ</option>
                                                <option value="19 giờ - 21 giờ">19 giờ - 21 giờ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="different-address">
                                    <div class="order-notes">
                                        <div class="checkout-form-list checkout-form-list-2">
                                            <label>Ghi chú</label>
                                            <textarea name="note" id="checkout-mess" cols="30" rows="10" placeholder="Lời nhắn với nhà hàng"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="your-order">
                                <h3>Thông tin món ăn</h3>
                                <div class="your-order-table table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="cart-product-name">Món ăn</th>
                                                <th class="cart-product-total">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (session('cart') as $item)
                                                <tr class="cart_item">
                                                    <td class="cart-product-name">
                                                        {{ $item['name'] }}
                                                        <strong class="product-quantity">x {{ $item['quantity'] }}</strong>
                                                    </td>
                                                    <td class="cart-product-total"><span class="amount">{{ number_format($item['price']*$item['quantity'], 0, ',', '.') }} đ</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="cart-subtotal">
                                                <th>Thành tiền</th>
                                                <td><span class="amount">{{ number_format($subtotal, 0, ',', '.') }} đ</span></td>
                                            </tr>
                                            <tr class="cart-subtotal">
                                                <th>Khuyến mãi</th>
                                                <td><span class="amount">{{ number_format($discount, 0, ',', '.') }} đ</span></td>
                                            </tr>
                                            <tr class="order-total">
                                                <th>Tổng tiền</th>
                                                <input type="hidden" name="total" value="{{ $total }}">
                                                <td><strong><span class="amount">{{ number_format($total, 0, ',', '.') }} đ</span></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="payment-method">
                                    <div class="payment-accordion">
                                        <div class="order-button-payment">
                                            <input value="Thanh toán" type="submit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
@section("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let dateInput = document.getElementById("dateInput");
            let today = new Date();
            let maxDate = new Date();

            maxDate.setDate(today.getDate() + 30);

            let formattedToday = today.toISOString().split("T")[0];
            let formattedMaxDate = maxDate.toISOString().split("T")[0];

            dateInput.setAttribute("min", formattedToday);
            dateInput.setAttribute("max", formattedMaxDate);
            dateInput.value = formattedToday;
        });
    </script>
@endsection