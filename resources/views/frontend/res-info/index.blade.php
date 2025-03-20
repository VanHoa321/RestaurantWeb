@extends('layout/web_layout')
@section('content')
    <main class="main-content">
        <div class="breadcrumb-area breadcrumb-height" data-bg-image="http://127.0.0.1:8000/storage/files/1/Slide/banner_1.png">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-lg-12">
                        <div class="breadcrumb-item">
                            <h2 class="breadcrumb-heading">Liên hệ với nhà hàng</h2>
                            <ul>
                                <li>
                                    <a href="index.html">Trang chủ <i class="pe-7s-angle-right"></i></a>
                                </li>
                                <li>Liên hệ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="blog-area section-space-y-axis-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="blog-detail-item">
                            <div class="blog-content text-start pb-0">
                                <h5 class="title mb-2 text-center">
                                    <a href="#">Chào mừng đến với {{ $info->name }}</a>
                                </h5>                               
                                <p class="short-desc">{!! $info->log_description !!}</p>
                                <p>{{ $info->sort_description }}</p>
                                <p>Hotline 1: {{ $info->hotline_1 }}</p>
                                <p>Hotline 2: {{ $info->hotline_2 }}</p>
                                <p>Email: {{ $info->email }}</p>
                                <p>Ngày mở cửa trong tuần: {{ $info->opening_day }}</p>
                                <p>Mở bán: {{ $info->open_time }}h - {{ $info->close_time }}h</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection