@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('inv-present.index') }}" class="text-info">Hóa đơn</a></li>
                            <li class="breadcrumb-item active text-default">Thêm mới hóa đơn</li>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                                </div>
                                                <select name="customer_id" class="form-control select2bs4">
                                                    <option value="1" {{ old('customer_id') == 1 ? 'selected' : '' }}>Khách lẻ</option>
                                                    @foreach($customers as $customer);
                                                        <option value="{{$customer->id}}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{$customer->full_name}} - {{ $customer->phone }}</option>
                                                    @endforeach
                                                </select>
                                            </div>       
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                                                </div>
                                                <select name="table_id" class="form-control select2bs4">
                                                    <option value="null">Mua về</option>
                                                    @foreach($tables as $table);
                                                        <option value="{{$table->id}}" {{ old('table_id') == $table->id ? 'selected' : '' }}>{{$table->name}} - {{$table->area->name  }} - {{ $table->type->max_seats }} người</option>
                                                    @endforeach
                                                </select>
                                            </div>       
                                        </div>
                                    </div>
                                </div>
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
                                    <div class="d-flex justify-content-between align-items-center border-top">
                                        <h5 class="mb-0 mt-2" style="font-weight: 600">Tổng tiền</h5>
                                        <h5 class="text-primary fw-bold mt-2" id="total-price" style="font-weight: 600">0 đ</h5>
                                    </div>

                                    <div class="d-flex gap-3 mt-3">
                                        <a href="{{ route('inv-present.index') }}" class="mx-1 btn bg-gradient-danger btn-lg fw-bold py-3 d-flex align-items-center justify-content-center flex-grow-1">
                                            <i class="fa-solid fa-rotate-left mr-1"></i> Quay lại
                                        </a>
                                        <a class="mx-1 btn bg-gradient-success btn-lg fw-bold py-3 d-flex align-items-center justify-content-center flex-grow-1" id="btn-create-inv">
                                            <i class="fa-solid fa-floppy-disk mr-1"></i> Tạo mới
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
@endsection
@section('scripts')
    <script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
    <script>
        $(document).ready(function() {
            var ids = {};
            var total_price = 0;
            var table = $("#example-table-5").DataTable();

            function formatCurrency(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " đ";
            }

            function updateTotalPrice() {
                total_price = Object.values(ids).reduce((sum, item) => {
                    return sum + item.total;
                }, 0);
                $("#total-price").text(formatCurrency(total_price));
            }

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

            $("#btn-create-inv").on("click", function () {
                let customer_id = $("select[name='customer_id']").val();
                let table_id = $("select[name='table_id']").val();
                var item_data = Object.keys(ids).map(itemId => ({
                    id: itemId,
                    quantity: ids[itemId].quantity,
                    price: ids[itemId].price,
                    total: ids[itemId].total
                }));

                if (item_data.length === 0) {
                    toastr.error("Vui lòng chọn món trước khi tạo hóa đơn");
                    return;
                }
                
                $.ajax({
                    url: "/invoice/present/store",
                    method: "POST",
                    data: {
                        items: item_data,
                        customer_id: customer_id,
                        table_id: table_id,
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
                            toastr.error("Có lỗi khi tạo mới hóa đơn");
                        }
                    }      
                });
            });

            $('.select2').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3500);
        });
    </script>
@endsection