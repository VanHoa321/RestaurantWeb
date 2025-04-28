@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('booking-history.index') }}" class="text-info">Lịch sử đặt bàn</a></li>
                        <li class="breadcrumb-item active text-default">Chi tiết đơn đặt bàn</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12 border" id="accordion">
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse1">
                                    <div class="card-header">
                                        <h4 class="card-title w-100 text-info">
                                            Thông tin đặt bàn
                                        </h4>
                                    </div>
                                </a>
                                <div id="collapse1" class="collapse show" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Ngày dự kiến nhận bàn</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Giờ dự kiến nhận bàn</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $booking->time_slot }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Số lượng khách</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $booking->guest_count }} người
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Ghi chú</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $booking->note ? $booking->note : "Không có" }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Tham chiếu</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        Hóa đơn <a href="{{ route("invoice-history.detail", $invoice->id) }}" class="text-bold">{{ $invoice->id }}</a>
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-1">
                                                    <div class="fw-bold col-5 col-md-4">Trạng thái đơn</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        @if ($booking->status == 1)
                                                            <span class="text-warning">Chưa nhận bàn</span>
                                                        @elseif ($booking->status == 2)
                                                            <span class="text-info">Nhận bàn</span>
                                                        @elseif ($booking->status == 3)
                                                            <span class="text-success">Hoàn thành</span>
                                                        @else
                                                            <span class="text-danger">Đã hủy</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse2">
                                    <div class="card-header">
                                        <h4 class="card-title w-100 text-info">
                                            Bàn và khu vực
                                        </h4>
                                    </div>
                                </a>
                                <div id="collapse2" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Khu vực</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $booking->table ? $booking->table->area->name : "Không có" }}, {{ $booking->table ? $booking->table->area->branch->name : "Không có" }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Bàn</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $booking->table ? $booking->table->name : "Không có" }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-1">
                                                    <div class="fw-bold col-5 col-md-4">Loại bàn</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        @if ($booking->table)
                                                            {{ $booking->table->type->name }} - Tối đa {{ $booking->table->type->max_seats }} người
                                                        @else
                                                            Không có
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse3">
                                    <div class="card-header">
                                        <h4 class="card-title w-100 text-info">
                                            Thông tin khách hàng
                                        </h4>
                                    </div>
                                </a>
                                <div id="collapse3" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Họ tên</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $booking->customer->full_name }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Số điện thoại</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $booking->customer->phone }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Địa chỉ</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $booking->customer->address ? $booking->customer->address : "Chưa cập nhật"}}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-1">
                                                    <div class="fw-bold col-5 col-md-4">Email</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $booking->customer->email ? $booking->customer->email : "Chưa cập nhật"}}
                                                    </div>
                                                </div>                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('booking-history.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection