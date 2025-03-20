@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('menu.index') }}" class="text-info">Quản lý thực đơn</a></li>
                            <li class="breadcrumb-item active">Thêm mới thực đơn</li>
                        </ol>               
                    </div>
                </div>
            </div>
            <div id="myAlert2" style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 99999"></div>
            @if ($errors->any())
                <div style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999" id="myAlert">
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-check2 text-danger"></i> {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Điền các trường dữ liệu</h3>                               
                            </div>
                            <div class="card-body">                           
                                <div class="row">
                                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                                        <div class="form-group text-center mt-2">
                                            <img id="holder" src="" style="width:250px; height:250px; border-radius:50%; object-fit:cover;" class="mx-auto d-block mb-4" />
                                            <span class="input-group-btn mr-2">
                                                <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-info">
                                                    <i class="fa-solid fa-image"></i> Chọn ảnh
                                                </a>
                                                <a type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-lg">
                                                    <i class="fa-solid fa-cheese" title="Thêm mặt hàng"></i> Mặt hàng
                                                </a>
                                            </span>
                                            <input id="thumbnail" class="form-control" type="hidden" name="avatar" value="{{ old('avatar') }}">                                                                             
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tên thực đơn</label>
                                                    <input type="text" name="name" class="form-control" placeholder="Nhập tên thực đơn" value="{{old('name')}}">
                                                </div> 
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Vị trí</label>
                                                    <input type="number" name="order_menu" class="form-control" placeholder="Nhập vị trí hiển thị" value="{{old('order_menu')}}">
                                                </div> 
                                            </div>
                                            <div class="col-md-12">
                                                <label>Mặt hàng</label>
                                                <table id="example-table-3" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Hình ảnh</th>
                                                            <th>Tên mặt hàng</th>                                             
                                                            <th>Giá bán</th>                                                                                                                           
                                                            <th>Danh mục</th>                                             
                                                            <th>Xóa</th>                                             
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>                              
                                                </table>
                                            </div>
                                        </div>                                                                  
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{route('menu.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
                                <button type="button" class="btn btn-primary" id="btn-save"><i class="fa-solid fa-floppy-disk" title="Lưu"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modal-lg" style="overflow-y: auto">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-info">Chọn mặt hàng thực đơn</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="example-table-4" class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hình ảnh</th>
                                <th>Tên mặt hàng</th>                                             
                                <th>Giá bán</th>                                                                                                                           
                                <th>Danh mục</th>                                             
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td><input type="checkbox"></td>
                                    <td><img src="{{ $item->avatar }}" alt="" style="width: 50px; height: 50px"></td>
                                    <td>{{ $item->name }}</td>                                                                  
                                    <td>{{ number_format($item->activePrice->sale_price, 0, ',', '.') }} đ</td>                                                                 
                                    <td>{{ $item->category->name }}</td>                                                                 
                                </tr>
                            @endforeach
                        </tbody>                              
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success" id="btn-add-item">Thêm</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset("assets/plugins/jquery-validation/jquery.validate.min.js")}}"></script>
    <script src="{{asset("assets/plugins/jquery-validation/additional-methods.min.js")}}"></script>
    <script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
    <script src="{{asset("assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js")}}"></script>

    <script>
        $(document).ready(function () {
            var table = $("#example-table-3").DataTable();
            var ids = {};

            $('#modal-lg').on('show.bs.modal', function () {
                $("#example-table-4 tbody input[type='checkbox']").each(function () {
                    var row = $(this).closest("tr");
                    var itemId = row.attr("data-id");
                    $(this).prop("checked", ids.hasOwnProperty(itemId));
                });
            });

            $("#example-table-4 tbody").on("change", "input[type='checkbox']", function () {
                var row = $(this).closest("tr");
                var itemId = row.attr("data-id");

                if ($(this).is(":checked")) {
                    ids[itemId] = {
                        image: row.find("td:eq(1) img").attr("src"),
                        name: row.find("td:eq(2)").text().trim(),
                        salePrice: row.find("td:eq(3)").text().trim(),
                        category: row.find("td:eq(4)").text().trim()
                    };
                } else {
                    delete ids[itemId];
                }
            });

            $("#example-table-4 tbody").on("click", "tr", function (e) {
                if ($(e.target).is("input[type='checkbox']")) return;
                var checkbox = $(this).find("input[type='checkbox']");
                checkbox.prop("checked", !checkbox.prop("checked")).trigger("change");
            });

            $("#btn-add-item").on("click", function () {
                var newItems = [];
                table.clear().draw();

                Object.keys(ids).forEach(itemId => {
                    var item = ids[itemId];
                    newItems.push([
                        `<img src="${item.image}" style="width: 50px; height: 50px">`,
                        item.name,
                        item.salePrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."),
                        item.category,
                        `<button class="btn btn-sm btn-remove-item" data-id="${itemId}"><i class="fa-solid fa-xmark"></i></button>`,
                        itemId
                    ]);
                });

                newItems.forEach(function (item) {
                    var newRow = table.row.add(item).draw().node();
                    $(newRow).attr("data-id", item[5]);
                });

                $("#modal-lg").modal("hide");
            });

            $("#example-table-3 tbody").on("click", ".btn-remove-item", function (e) {
                e.preventDefault();
                var row = $(this).closest("tr");
                var itemId = row.attr("data-id");
                delete ids[itemId];
                table.row(row).remove().draw();
            });

            $("#btn-save").on("click", function () {
                var name = $("input[name='name']").val();
                var order_menu = $("input[name='order_menu']").val();
                var avatar = $("input[name='avatar']").val();
                var item_ids = Object.keys(ids);

                if(item_ids.length === 0){
                    toastr.error("Vui lòng thêm mặt hàng thực đơn");
                    return;
                }
                
                $.ajax({
                    url: "/catalog/menu/store/",
                    method: "POST",
                    data: {
                        name: name,
                        order_menu: order_menu,
                        avatar: avatar,
                        item_ids: item_ids,
                        _token: $("meta[name='csrf-token']").attr("content")
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(function () {
                                window.location.href = "/catalog/menu/index/";
                            }, 1000);
                        }
                        else {
                            toastr.error("Có lỗi khi thêm mới thực đơn");
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                            console.log(errors);
                            let errorHtml = '<div class="alert alert-danger" role="alert">';

                            $.each(errors, function (key, value) {
                                errorHtml += '<i class="bi bi-check2 text-danger"></i> ' + value[0] + '<br>';
                            });

                            errorHtml += '</div>';
                            $("#myAlert2").html(errorHtml).fadeIn();
                            setTimeout(function () {
                                $("#myAlert2").fadeOut();
                            }, 5000);
                        }
                    }               
                });
            });
        });

        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });
    </script>
@endsection
