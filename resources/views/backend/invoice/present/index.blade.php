@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('present.index') }}" class="text-info">Hóa đơn</a></li>
                            <li class="breadcrumb-item active text-default">Hóa đơn hiện thời</li>
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
                            <div class="card-header">
                                <div>
                                    <a type="button" class="btn btn-success" href="{{route('inv-present.create')}}">
                                        <i class="fa-solid fa-plus" title="Tạo hóa đơn"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row" id="invoice-container">
                                    @if ($items->isNotEmpty())
                                        @foreach ($items as $item)
                                            <div class="col-md-4 col-sm-6 col-12 invoice-item" id="content-{{ $item->id }}">
                                                <div class="card">
                                                    <div class="card-header text-center fw-bold bg-light">
                                                        {{ $item->customer->full_name }} {{ $item->customer->phone ? "-":"" }} {{ $item->customer->phone }}
                                                    </div>
                                                    <div class="row g-0">
                                                        <div class="col-4 d-flex flex-column align-items-center justify-content-center border-right">
                                                            @if ($item->table_id)
                                                            <h4 class="fw-bold text-info" style="font-weight: 600">{{ $item->table->name }}</h4>
                                                            <span class="text-muted" style="font-size: 14px">{{ $item->table->area->name }}</span>
                                                            @else
                                                            <h4 class="fw-bold text-info" style="font-weight: 600">Mua về</h4>
                                                            @endif
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="row g-0">
                                                                <div class="col-6 d-flex align-items-center justify-content-center border-end py-3">
                                                                    <i class="bi bi-alarm mr-1" title="Thời gian vào ăn"></i> {{ \Carbon\Carbon::parse($item->time_in)->format('H:i:s') }}
                                                                </div>
                                                                <div class="col-6 d-flex align-items-center justify-content-center py-3">
                                                                    <i class="bi bi-coin mr-1" title="Số tiền đã trả trước"></i>{{ number_format($item->total_cost, 0, ',', '.') }} đ
                                                                </div>
                                                            </div>
                                                            <div class="row g-0 border-top w-100">
                                                                <div class="col-12 d-flex align-items-center justify-content-center py-3">
                                                                    <i class="bi bi-wallet2 mr-1" title="Số tiền cần thanh toán thêm"></i> <span class="text-danger text-bold">{{ number_format(($item->invoiceDetails->sum(fn($detail) => $detail->price * $detail->quantity) - $item->total_cost), 0, ',', '.') }} đ</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer d-flex">
                                                        <div class="col-4 align-items-center">
                                                            <div class="btn-group">
                                                                <i class="bi bi-three-dots text-info" style="margin-left:22px; cursor:pointer" data-toggle="dropdown"></i>
                                                                <div class="dropdown-menu" role="menu">
                                                                    <a class="dropdown-item text-success my-1" href="{{ route("inv-present.select-item", $item->id) }}"><i class="bi bi-eye"></i> Chi tiết</a>
                                                                    @if ($item->table_id)
                                                                    <a class="dropdown-item text-secondary my-1" href="{{ route("inv-present.table", $item->id) }}"><i class="bi bi-geo"></i> Đổi bàn</a>
                                                                    @endif
                                                                    <a class="dropdown-item text-danger my-1 cancelInvoice" data-id="{{ $item->id }}" href="#"><i class="bi bi-x-circle"></i> Hủy đơn</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8 d-flex justify-content-center align-items-center">
                                                            <a href="#" class="text-decoration-none text-info pay-invoice" data-id="{{ $item->id }}" data-toggle="modal" data-target="#modal-default">Thanh toán</a>
                                                            <input type="hidden" id="total-inv-{{ $item->id }}" value="{{ $item->invoiceDetails->sum(fn($detail) => $detail->price * $detail->quantity) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="d-flex flex-column align-items-center justify-content-center text-center w-100">
                                            <img src="/storage/files/1/Blog/8183434.jpg" alt="Hóa đơn" style="max-width: 350px;">
                                            <h5 class="mt-3 text-success">
                                                <em>Chưa có hóa đơn cho hôm nay!</em>
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
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-info" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="rounded p-3 bg-white">
                        <input type="hidden" id="invoice-id">
                        <div class="row align-items-center border rounded mb-3">
                            <div class="col-4 bg-light p-3 fw-bold">Tổng tiền</div>
                            <div class="col-8 p-3 text-end fw-bold text-bold text-dark" id="total"></div>
                        </div>
                        <div class="row align-items-center border rounded mb-3">
                            <div class="col-4 bg-light p-3 fw-bold">Thanh toán trước</div>
                            <div class="col-8 p-3 text-end fw-bold text-dark" id="pre-total"></div>
                        </div>
                        <div class="row align-items-center border rounded mb-3">
                            <div class="col-4 bg-light p-3 fw-bold">Khách trả</div>
                            <div class="col-8 text-end">
                                <input type="number" class="form-control text-end fw-bold text-primary border-0" placeholder="Nhập số tiền" id="inputMoney">
                            </div>
                        </div>
                        <div class="row align-items-center border rounded">
                            <div class="col-4 bg-light p-3 fw-bold">Tiền thừa</div>
                            <div class="col-8 p-3 text-end text-success fw-bold text-bold" id="change-total">0 đ</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="pay-invoice-confirm">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $(".pay-invoice").click(function(e) {
                e.preventDefault();
                let invoiceId = $(this).data("id");
                $.ajax({
                    url: "/invoice/present/get-invoice/" + invoiceId,
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            let invoice = response.invoice;
                            let total = $("#total-inv-" + invoiceId).val();
                            let changeTotal = total - invoice.total_cost;
                            $("#invoice-id").val(invoiceId);
                            $("#modal-title").text("Thanh toán " + (invoice.table_id ? invoice.table.name : "Mang về"));
                            $("#total").text(new Intl.NumberFormat('vi-VN').format(total) + " đ");
                            $("#pre-total").text(new Intl.NumberFormat('vi-VN').format(invoice.total_cost) + " đ");
                            $("#inputMoney").val(changeTotal);
                            $("#change-total").text("0 đ");
                            $("#modal-default").modal("show");
                        }
                    },
                    error: function() {
                        toastr.error("Lỗi khi tải dữ liệu hóa đơn!");
                    }
                });
            });

            $("#inputMoney").on("input", function() {
                let total = parseFloat($("#total").text().replace(/\D/g, "")) || 0;
                let preTotal = parseFloat($("#pre-total").text().replace(/\D/g, "")) || 0;
                let paid = parseFloat($(this).val()) || 0;

                let remaining = total - preTotal;
                let change = paid - remaining;

                $("#change-total").text(new Intl.NumberFormat('vi-VN').format(change) + " đ");
            });

            $('body').on('click', '#pay-invoice-confirm', function(e) {
                e.preventDefault();
                var id = $("#invoice-id").val();
                let total = $("#total-inv-" + id).val();
                $.ajax({
                    url: "/invoice/present/pay-invoice/" + id,
                    type: "POST",
                    data: {
                        total: total,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#modal-default").modal("hide");
                            $("#content-" + id).remove();
                            if ($(".invoice-item").length === 0) {
                                $("#invoice-container").html(`
                                            <div class="d-flex flex-column align-items-center justify-content-center text-center w-100">
                                                <img src="/storage/files/1/Blog/8183434.jpg" alt="Hóa đơn" style="max-width: 350px;">
                                                <h5 class="mt-3 text-success">
                                                    <em>Chưa có hóa đơn cho hôm nay!</em>
                                                </h5>
                                            </div>
                                        `);
                            }
                            toastr.success(response.message);
                            Swal.fire({
                                title: "In hóa đơn?",
                                showCancelButton: true,
                                cancelButtonColor: "#d33",
                                confirmButtonColor: "#28a745",
                                cancelButtonText: "Đóng",
                                confirmButtonText: "In hóa đơn",
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.open("/invoice/present/print/" + id, "_blank");
                                }
                            });
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi khi thanh toán hóa đơn');
                    }
                });
            })

            $('body').on('click', '.cancelInvoice', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận hủy hóa đơn này?",
                    input: "text",
                    inputPlaceholder: "Nhập lý do hủy...",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hủy hóa đơn",
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
                            url: "/invoice/present/cancel/" + id,
                            type: "POST",
                            data: {
                                _token: '{{ csrf_token() }}',
                                reason: reason
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.href = "/invoice/present/index/";
                                }, 1000);
                            },
                            error: function(xhr) {
                                toastr.error('Có lỗi khi hủy hóa đơn');
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