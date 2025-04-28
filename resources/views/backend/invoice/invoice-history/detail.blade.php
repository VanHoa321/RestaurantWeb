@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('invoice-history.index') }}" class="text-info">Lịch sử hóa đơn</a></li>
                        <li class="breadcrumb-item active text-default">Chi tiết hóa đơn</li>
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
                                            Thông tin hóa đơn
                                        </h4>
                                    </div>
                                </a>
                                <div id="collapse1" class="collapse show" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Thời gian lập hóa đơn</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ \Carbon\Carbon::parse($invoice->created_date)->format('d/m/Y H:i:s') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Thời gian khách vào</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ \Carbon\Carbon::parse($invoice->time_in)->format('d/m/Y H:i:s') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Thời gian khách ra</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ \Carbon\Carbon::parse($invoice->time_out)->format('d/m/Y H:i:s') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Tổng tiền</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ number_format($invoice->total_amount, 0, ',', '.') }} đ
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Thời gian thanh toán</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ \Carbon\Carbon::parse($invoice->payment_time)->format('d/m/Y H:i:s') }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Ghi chú</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $invoice->note ? $invoice->note : "Không có" }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Tham chiếu</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        @if ($invoice->booking_id)
                                                            Đặt bàn <a href="{{ route('booking-history.detail', $booking->id) }}" class="text-bold">{{ $booking->id }}</a>
                                                        @else
                                                            Không có
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-1">
                                                    <div class="fw-bold col-5 col-md-4">Trạng thái đơn</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        @if ($invoice->status == 1)
                                                            <span class="text-warning">Chờ xác nhận thanh toán</span>
                                                        @elseif ($invoice->status == 2)
                                                            <span class="text-success">Đã thanh toán</span>
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
                                                        {{ $invoice->customer->full_name }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Số điện thoại</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $invoice->customer->phone }}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-4">
                                                    <div class="fw-bold col-5 col-md-4">Địa chỉ</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $invoice->customer->address ? $invoice->customer->address : "Chưa cập nhật"}}
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-1">
                                                    <div class="fw-bold col-5 col-md-4">Email</div>
                                                    <div class="col-7 col-md-8">
                                                        <span class="mr-2">:</span>
                                                        {{ $invoice->customer->email ? $invoice->customer->email : "Chưa cập nhật"}}
                                                    </div>
                                                </div>                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse4">
                                    <div class="card-header">
                                        <h4 class="card-title w-100 text-info">
                                            Chi tiết hóa đơn
                                        </h4>
                                    </div>
                                </a>
                                <div id="collapse4" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="example-table-6" class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Mặt hàng</th>
                                                            <th>Giá bán</th>
                                                            <th>Số lượng</th>
                                                            <th>Thành tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $counter = 1;
                                                        @endphp
                                                        @foreach($details as $detail)
                                                            <tr>
                                                                <td>{{ $counter++ }}</td>                                                                
                                                                <td>
                                                                    @if (!is_null($detail->item_id))
                                                                        <img src="{{ $detail->item->avatar }}" class="mr-1 mb-3" style="width:60px">
                                                                        {{ $detail->item->name }}
                                                                    @else (!is_null($detail->combo_id))
                                                                        <img src="{{ $detail->combo->avatar }}" class="mr-1 mb-3" style="width:60px">
                                                                        {{ $detail->combo->name }}
                                                                    @endif
                                                                </td>
                                                                <td>{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                                                                <td>{{ $detail->quantity }}</td>
                                                                <td>{{ number_format($detail->amount, 0, ',', '.') }} đ</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>                                                          
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                <div class="cart-total float-right p-3">                                                       
                                                    <div class="d-flex justify-content-between">
                                                        <span class="mr-1">Thành tiền:</span>
                                                        <span class="text-danger font-weight-bold"><strong id="cart-total">{{ number_format($invoice->total_amount, 0, ',', '.') }} đ</strong></span>
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('invoice-history.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
                            <a href="{{route('invoice.pdf', $invoice->id)}}" class="btn btn-secondary" title="In hóa đơn">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection