@extends('layout/web_layout')
@section('content')
<main class="main-content">
    <div class="breadcrumb-area breadcrumb-height" data-bg-image="http://127.0.0.1:8000/storage/files/1/Slide/banner_1.png">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-lg-12">
                    <div class="breadcrumb-item">
                        <h2 class="breadcrumb-heading">Đặt bàn thành công</h2>
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
    <div class="checkout-area section-space-y-axis-100 d-flex align-items-center justify-content-center text-center">
        <div>
            <img src="http://127.0.0.1:8000/storage/files/1/Blog/8183434.jpg" alt="Đặt bàn thành công" style="max-width: 350px;">
            <h3 class="mt-3 text-success"><em>Cảm ơn bạn đã đặt bàn! Chúng tôi sẽ liên hệ xác nhận sớm nhất</em></h3>
            <a href="{{ route('home.index') }}" class="btn btn-primary mt-4">Quay lại trang chủ</a>
        </div>
    </div>
</main>
@endsection