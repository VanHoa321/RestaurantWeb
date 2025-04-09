@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('present.index') }}" class="text-info">Đặt bàn</a></li>
                            <li class="breadcrumb-item active text-default">Sơ đồ bàn</li>
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
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Sơ đồ bàn <span class="text-info">{{ $areas->first()->branch->name }} - {{ $areas->first()->branch->address }}</span>
                                </h3>
                            </div>
                            <div class="card-body mt-3">
                                <div class="row">
                                    <div class="col-5 col-sm-2">
                                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                            @foreach($areas as $area)
                                                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tabs-area-{{ $area->id }}" data-toggle="pill" href="#vert-{{ $area->id }}" role="tab" aria-controls="vert-{{ $area->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                    {{ $area->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-7 col-sm-10">
                                        <div class="tab-content" id="vert-tabs-tabContent">
                                            @foreach($areas as $area)
                                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="vert-{{ $area->id }}" role="tabpanel" aria-labelledby="tabs-area-{{ $area->id }}">
                                                    <div class="row">
                                                        @foreach($tables->where("area_id", $area->id) as $table)
                                                            <div class="col-md-4 col-sm-6 col-12 mb-1">
                                                                <div class="card">
                                                                    <div class="card-header text-center fw-bold bg-light">
                                                                        {{ $table->type->name }} - {{ $table->type->max_seats }} người
                                                                    </div>
                                                                    <div class="row g-0">
                                                                        <div class="col-4 d-flex align-items-center justify-content-center border-right">
                                                                            <h4 class="fw-bold text-info" style="font-weight: 600">{{ $table->name }}</h4>
                                                                        </div>
                                                                        <div class="col-8">                              
                                                                            <div class="row g-0 w-100">
                                                                                <div class="col-12 d-flex align-items-center justify-content-center py-3">
                                                                                @if ($table->status == 1)
                                                                                    <span class="badge bg-success p-2">
                                                                                        <i class="bi bi-check-circle"></i> Còn trống
                                                                                    </span>                                                                              
                                                                                @elseif ($table->status == 2)
                                                                                    <a href="#" class="booking-res" data-id="{{ $table->id }}">
                                                                                        <span class="badge bg-primary p-2">
                                                                                            <i class="bi bi-question-circle"></i> Đã đặt trước
                                                                                        </span>
                                                                                    </a>
                                                                                @else
                                                                                    <a href="#" class="invoice-res" data-id="{{ $table->id }}">
                                                                                        <span class="badge bg-danger p-2">
                                                                                            <i class="bi bi-x-circle"></i> Đang phục vụ
                                                                                        </span>
                                                                                    </a>
                                                                                @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                                
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>                                            
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="card-footer">
                                <a href="{{route('present.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modal-default" style="overflow-y: auto">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-info" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="rounded p-3 bg-white" id="booking-list"></div>                                           
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

            $(".booking-res").click(function (e) {
                e.preventDefault();
                let id = $(this).data("id");
                $.ajax({
                    url: "/booking/table-dia/getBooking/" + id,
                    type: "GET",
                    success: function (response) {
                        if (response.success) {       
                            let html = "";
                            $("#modal-title").text("Lịch đặt bàn của "+ response.table_name);
                            response.bookings.forEach(function (booking) {
                                html += `
                                    <div class="row align-items-center border rounded mb-3">
                                        <div class="col-4 bg-light p-3 fw-bold">${booking.time_slot}</div>
                                        <div class="col-8 p-3 text-end fw-bold text-dark">${booking.customer.full_name} - ${booking.customer.phone}</div>
                                    </div>`;
                            });
                            $("#booking-list").html(html);     
                            $("#modal-default").modal("show");
                        }
                        else
                        {
                            toastr.error(response.message);
                        }
                    },
                    error: function () {
                        toastr.error("Lỗi khi tải dữ liệu hóa đơn!");
                    }
                });
            });

            $(".invoice-res").click(function (e) {
                e.preventDefault();
                let id = $(this).data("id");
                $.ajax({
                    url: "/booking/table-dia/getInvoice/" + id,
                    type: "GET",
                    success: function (response) {
                        if (response.success) {       
                            let html = "";
                            $("#modal-title").text("Hóa đơn hiện thời của "+ response.table_name);
                            html += `
                            <div class="row align-items-center border rounded mb-3">
                                <div class="col-5 bg-light p-3 fw-bold">${response.invoice.time_in}</div>
                                <div class="col-7 p-3 text-end fw-bold text-dark">${response.invoice.customer.full_name} - ${response.invoice.customer.phone}</div>
                            </div>`;
                            $("#booking-list").html(html);     
                            $("#modal-default").modal("show");
                        }
                        else
                        {
                            toastr.error(response.message);
                        }
                    },
                    error: function () {
                        toastr.error("Lỗi khi tải dữ liệu hóa đơn!");
                    }
                });
            });

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            },3500);
        })
    </script>                              
@endsection
