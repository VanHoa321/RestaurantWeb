@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('inv-present.index') }}" class="text-info">Hóa đơn</a></li>
                            <li class="breadcrumb-item active text-default">Chọn món cho hóa đơn</li>
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
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header p-3">
                                <ul class="nav nav-pills">
                                    @foreach ($menus as $menu)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" href="#tab-{{ $menu->id }}" data-toggle="tab">
                                                {{ $menu->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="nav-item"><a class="nav-link" href="#combo" data-toggle="tab">Combo</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    @foreach ($menus as $menu)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $menu->id }}">
                                            <div class="row g-2">
                                                @foreach ($menu_items->where('menu_id', $menu->id) as $item)
                                                    <div class="col-md-3 col-6 mb-3">
                                                        <a href="#" class="res-item" data-id="{{ $item->item->id }}" data-name="{{ $item->item->name }}" data-price="{{ $item->item->activePrice->sale_price }}">
                                                            <div class="border border-success rounded overflow-hidden">
                                                                <img src="{{ $item->item->avatar }}" class="w-100 img-fluid object-fit-cover" style="height: 125px;">
                                                                <div class="bg-dark bg-opacity-50 text-white text-center py-1">{{ $item->item->name }}</div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="tab-pane" id="combo">
                                        <div class="row g-2">
                                            @foreach ($combos as $combo)
                                                <div class="col-md-3 col-6 mb-3">
                                                    <a href="#" class="res-combo" data-id="{{ $combo->id }}" data-name="{{ $combo->name }}" data-price="{{ $combo->price }}">
                                                        <div class="border border-info rounded overflow-hidden">
                                                            <img src="{{ $combo->avatar }}" class="w-100 img-fluid object-fit-cover" style="height: 140px;">
                                                            <div class="bg-dark bg-opacity-50 text-white text-center py-1">{{ $combo->name }}</div>
                                                        </div>
                                                    </a>
                                                </div>   
                                            @endforeach             
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="container">
                                    <table id="example-table-5" class="table">
                                        <thead>
                                            <tr>
                                                <th>Mặt hàng</th>                                             
                                                <th>Số lượng</th>                                                                                                                                                                 
                                                <th>Thành tiền</th>                                                                                                                                                                 
                                                <th>Xóa</th>                                             
                                            </tr>
                                        </thead>
                                        <tbody></tbody>                              
                                    </table>
                                    <input type="hidden" id="details-data" value="{{ json_encode($details) }}">
                                    <div class="d-flex justify-content-between align-items-center border-top">
                                        <h5 class="mb-0 mt-2" style="font-weight: 600">Tổng tiền</h5>
                                        <h5 class="text-primary fw-bold mt-2" id="total-price" style="font-weight: 600">0 đ</h5>
                                    </div>

                                    <div class="d-flex gap-3 mt-3">
                                        <a href="{{ route('inv-present.index') }}" class="mx-1 btn bg-gradient-danger btn-lg fw-bold py-3 d-flex align-items-center justify-content-center flex-grow-1">
                                            <i class="fa-solid fa-rotate-left mr-1"></i> Quay lại
                                        </a>
                                        <a class="mx-1 btn bg-gradient-primary btn-lg fw-bold py-3 d-flex align-items-center justify-content-center flex-grow-1" id="btn-save-details" data-id="{{ $invoice->id }}">
                                            <i class="fa-solid fa-floppy-disk mr-1"></i> Lưu lại
                                        </a>
                                        <a class="pay-invoice mx-1 btn bg-gradient-success btn-lg fw-bold py-3 d-flex align-items-center justify-content-center flex-grow-1" data-id="{{ $invoice->id }}" data-toggle="modal" data-target="#modal-default">
                                            <i class="fa-solid fa-money-check-dollar mr-1"></i> Thanh toán
                                        </a>
                                    </div>
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
            var ids = {};
            var total_price = 0;
            var table = $("#example-table-5").DataTable();
            var details = JSON.parse($("#details-data").val());

            function formatCurrency(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " đ";
            }

            function updateTotalPrice() {
                total_price = Object.values(ids).reduce((sum, item) => {
                    return sum + item.total;
                }, 0);
                $("#total-price").text(formatCurrency(total_price));
            }
            details.forEach(item => {
                let id, name, price, quantity, total;
                if (item.item_id) {
                    id = item.item_id;
                    uniqueId = "item-" + id;
                    name = item.item.name;
                    price = item.price;
                    quantity = item.quantity;
                    total = price*quantity;
                } 
                else if (item.combo_id) {
                    id = item.combo_id;
                    uniqueId = "combo-" + id;
                    name = item.combo.name;
                    price = item.price;
                    quantity = item.quantity;
                    total = price*quantity;
                }
                ids[uniqueId] = {name, price, quantity, total};

                let newRow = table.row.add([
                    `<div>
                        ${name}<br>
                        <span class="text-info text-bold">${price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " đ"}</span>
                    </div>`,  
                    `<div class="text-center item-quantity">${quantity}</div>`,
                    `<div class="text-center item-total">${total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " đ"}</div>`,
                    `<button class="btn btn-sm btn-delete" data-id="${uniqueId}">
                        <i class="fa-solid fa-xmark"></i>
                    </button>`,
                ]).draw(false).node();
                $(newRow).attr("id", uniqueId);
            });
            updateTotalPrice();

            $(document).on("click", ".res-item", function (e) {
                e.preventDefault();
                let id = $(this).data("id");
                let uniqueId = "item-" + id;
                let name = $(this).data("name");
                let price = parseFloat($(this).data("price"));

                addToTable(uniqueId, name, price);
            });

            $(document).on("click", ".res-combo", function (e) {
                e.preventDefault();
                let id = $(this).data("id");
                let uniqueId = "combo-" + id;
                let name = $(this).data("name");
                let price = parseFloat($(this).data("price"));

                addToTable(uniqueId, name, price);
            });

            function addToTable(uniqueId, name, price) {
                if (ids[uniqueId]) {
                    ids[uniqueId].quantity += 1;
                    ids[uniqueId].total = ids[uniqueId].quantity * ids[uniqueId].price;
                    let row = $(`#${uniqueId}`);
                    row.find(".item-quantity").text(ids[uniqueId].quantity);
                    row.find(".item-total").text(formatCurrency(ids[uniqueId].total));
                }
                else
                {
                    ids[uniqueId] = {
                        name: name,
                        price: price,
                        quantity: 1,
                        total: price
                    };

                    let newRow = table.row.add([
                        `<div>
                            ${name}<br>
                            <span class="text-info text-bold">${price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " đ"}</span>
                        </div>`,  
                        `<div class="text-center item-quantity">${ids[uniqueId].quantity}</div>`,
                        `<div class="text-center item-total">${ids[uniqueId].total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " đ"}</div>`,
                        `<button class="btn btn-sm btn-delete" data-id="${uniqueId}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>`,
                    ]).draw(false).node();
                    $(newRow).attr("id", uniqueId);
                }
                updateTotalPrice();
            }

            $(document).on("click", ".btn-delete", function () {
                var row = $(this).closest("tr");
                var id = $(this).data("id");
                delete ids[id];
                table.row(row).remove().draw();
                updateTotalPrice();
            });

            $("#btn-save-details").on("click", function () {
                var invoice_id = $(this).data("id");
                var item_data = Object.keys(ids).map(itemId => ({
                    id: itemId,
                    quantity: ids[itemId].quantity,
                    price: ids[itemId].price,
                    total: ids[itemId].total
                }));

                if (item_data.length === 0) {
                    toastr.error("Vui lòng chọn món trước khi cập nhật");
                    return;
                }
                
                $.ajax({
                    url: "/invoice/present/update-item",
                    method: "POST",
                    data: {
                        invoice_id: invoice_id,
                        items: item_data,
                        _token: $("meta[name='csrf-token']").attr("content")
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(function () {
                                window.location.href = "/invoice/present/index/";
                            }, 1000);
                        }
                        else {
                            toastr.error("Có lỗi khi cập nhật hóa đơn");
                        }
                    }        
                });
            });

            $(".pay-invoice").click(function (e) {
                e.preventDefault();
                let invoiceId = $(this).data("id");
                $.ajax({
                    url: "/invoice/present/get-invoice/" + invoiceId,
                    type: "GET",
                    success: function (response) {
                        if (response.success) {
                            let invoice = response.invoice;
                            let changeTotal = total_price - invoice.total_cost;
                            $("#invoice-id").val(invoiceId);
                            $("#modal-title").text("Thanh toán " + invoice.table.name);
                            $("#total").text(new Intl.NumberFormat('vi-VN').format(total_price) + " đ");
                            $("#pre-total").text(new Intl.NumberFormat('vi-VN').format(invoice.total_cost) + " đ");                                
                            $("#inputMoney").val(changeTotal);
                            $("#change-total").text("0 đ");
                            $("#modal-default").modal("show");
                        }
                    },
                    error: function () {
                        toastr.error("Lỗi khi tải dữ liệu hóa đơn!");
                    }
                });
            });

            $("#inputMoney").on("input", function () {
                let total = parseFloat($("#total").text().replace(/\D/g, "")) || 0;
                let preTotal = parseFloat($("#pre-total").text().replace(/\D/g, "")) || 0;
                let paid = parseFloat($(this).val()) || 0;
                    
                let remaining = total - preTotal;
                let change = paid - remaining;

                $("#change-total").text(new Intl.NumberFormat('vi-VN').format(change) + " đ");
            });

            $('body').on('click', '#pay-invoice-confirm', function (e) {
                e.preventDefault();
                var id = $("#invoice-id").val();
                $.ajax({
                    url: "/invoice/present/pay-invoice/" + id,
                    type: "POST",
                    data: {
                        total: total_price,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        if(response.success){
                            $("#modal-default").modal("hide");
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
                                window.location.href = "/invoice/present/index/";
                            });                                                            
                        }
                        else{
                            toastr.error(response.message);
                        }                                                  
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi khi thanh toán hóa đơn');
                    }
                });
            })

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3500);
        });
    </script>
@endsection