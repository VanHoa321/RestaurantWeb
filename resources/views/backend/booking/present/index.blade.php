@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('present.index') }}" class="text-info">Đặt bàn</a></li>
                            <li class="breadcrumb-item active text-default">Đơn đặt bàn hiện thời</li>
                        </ol>
                    </div>
                </div>
            </div>
            @if(Session::has('messenge') && is_array(Session::get('messenge')))
                @php
                    $messenge = Session::get('messenge');
                @endphp
                @if(isset($messenge['style']) && isset($messenge['msg']))
                    <div class="alert alert-{{ $messenge['style'] }}" role="alert" style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999" id="myAlert">
                        <i class="bi bi-check2 text-{{ $messenge['style'] }}"></i>{{ $messenge['msg'] }}
                    </div>
                    @php
                        Session::forget('messenge');
                    @endphp
                @endif
            @endif
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    @if ($items->isNotEmpty())
                                        @foreach ($items as $item)
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="card">
                                                    <div class="card-header text-center fw-bold bg-light">
                                                        {{ $item->customer->full_name }} - {{ $item->customer->phone }}
                                                    </div>
                                                    <div class="row g-0">
                                                        <div class="col-4 d-flex flex-column align-items-center justify-content-center border-right">
                                                            @if ($item->table_id)
                                                            <h4 class="fw-bold text-info" style="font-weight: 600">{{ $item->table->name }}</h4>
                                                            <span class="text-muted" style="font-size: 14px">{{ $item->table->area->name }}</span>
                                                            @else
                                                            <h2 class="fw-bold text-info" style="font-weight: 600" title="Chưa sắp xếp bàn">?</h2>
                                                            @endif
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="row g-0">
                                                                <div class="col-6 d-flex align-items-center justify-content-center border-end py-3">
                                                                    <i class="bi bi-alarm mr-1" title="Thời gian dùng bữa dự kiến"></i> {{ $item->time_slot }}
                                                                </div>
                                                                <div class="col-6 d-flex align-items-center justify-content-center py-3">
                                                                    <i class="bi bi-people mr-1" title="Số lượng khách"></i> {{ $item->guest_count }}
                                                                </div>
                                                            </div>
                                                            <div class="row g-0 border-top w-100">
                                                                <div class="col-12 d-flex align-items-center justify-content-center py-3">
                                                                    <i class="bi bi-coin mr-1" title="Số tiền đã thanh toán"></i> {{ number_format($item->pre_payment, 0, ',', '.') }} đ
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer d-flex">
                                                        <div class="col-4 align-items-center">
                                                            <div class="btn-group">
                                                                <i class="bi bi-three-dots text-info" style="margin-left:22px; cursor:pointer" data-toggle="dropdown"></i>
                                                                <div class="dropdown-menu" role="menu">
                                                                    <a class="dropdown-item text-secondary my-1" href="{{ route("present.table", $item->id) }}"><i class="bi bi-geo"></i> {{ $item->table ? "Đổi bàn" : "Xếp bàn" }}</a>
                                                                    <a class="dropdown-item text-primary my-1" href="{{ route("present.select-item", $item->id) }}"><i class="bi bi-cup-hot"></i> Chọn món</a>
                                                                    <a class="dropdown-item text-danger my-1 cancelBooking" data-id="{{ $item->id }}" href="#"><i class="bi bi-x-circle"></i> Hủy đặt bàn</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8 d-flex justify-content-center align-items-center">
                                                            <a href="{{ route("present.customerTable", $item->id) }}" class="text-decoration-none text-info">Khách nhận bàn</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="d-flex flex-column align-items-center justify-content-center text-center w-100">
                                            <img src="/storage/files/1/Blog/8183434.jpg" alt="Đặt bàn thành công" style="max-width: 350px;">
                                            <h5 class="mt-3 text-success">
                                                <em>Chưa có đơn đặt bàn trước cho hôm nay!</em>
                                            </h5>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $('body').on('click', '.cancelBooking', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận hủy đặt bàn?",
                    input: "text",
                    inputPlaceholder: "Nhập lý do hủy...",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hủy đặt bàn",
                    cancelButtonText: "Đóng",
                    inputValidator: (value) => {
                        if (!value) {
                            return "Bạn phải nhập lý do hủy!";
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        $.ajax({
                            url: "/booking/present/cancel/" + id,
                            type: "POST",
                            data: {
                                _token: '{{ csrf_token() }}',
                                reason: reason
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.href = "/booking/present/index/";
                                }, 1000);
                            },
                            error: function(xhr) {
                                toastr.error('Có lỗi khi hủy đặt bàn');
                            }
                        });
                    }
                });
            })

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3500);
        })
    </script>
@endsection