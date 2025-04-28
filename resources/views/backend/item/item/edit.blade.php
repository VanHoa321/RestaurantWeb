@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('item.index') }}" class="text-info">Danh sách mặt hàng</a></li>
                            <li class="breadcrumb-item active">Chỉnh sửa mặt hàng</li>
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
                            <form method="post" action="{{route("item.update", $edit->id)}}" id="quickForm">
                                @csrf
                                <div class="card-body">                           
                                    <div class="row">
                                        <div class="col-md-4 d-flex justify-content-center align-items-center">
                                            <div class="form-group text-center mt-2">
                                                <img id="holder" src="" style="width:250px; height:250px; border-radius:50%; object-fit:cover;" class="mx-auto d-block mb-4" />
                                                <span class="input-group-btn mr-2">
                                                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-info">
                                                        <i class="fa-solid fa-image"></i> Chọn ảnh
                                                    </a>
                                                    <a class="btn btn-info" data-toggle="modal" data-target="#modal-xl">
                                                        <i class="fa-solid fa-money-bill-1"></i> Giá bán
                                                    </a>
                                                </span>
                                                <input id="thumbnail" class="form-control" type="hidden" name="avatar" value="{{ old('avatar', $edit->avatar) }}">                                                                             
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Tên mặt hàng</label>
                                                <input type="text" name="name" class="form-control" placeholder="Nhập tên mặt hàng" value="{{old('name', $edit->name)}}">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Đơn vị tính</label>
                                                        <select name="unit_id" class="form-control select2bs4" style="width: 100%">
                                                            <option value="0" {{ old('unit_id') == 0 ? 'selected' : '' }}>---Chọn đơn vị tính---</option>
                                                            @foreach($units as $unit);
                                                                <option value="{{$unit->id}}" {{ old('unit_id', $edit->unit_id) == $unit->id ? 'selected' : '' }}>{{$unit->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Danh mục</label>
                                                        <select name="category_id" class="form-control select2bs4" style="width: 100%">
                                                            <option value="0" {{ old('category_id') == 0 ? 'selected' : '' }}>---Chọn danh mục---</option>
                                                            @foreach($categories as $cate);
                                                                <option value="{{$cate->id}}" {{ old('category_id', $edit->category_id) == $cate->id ? 'selected' : '' }}>{{$cate->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Mô tả thêm</label>
                                                <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả thêm" style="height: 115px">{{ old('description', $edit->description) }}</textarea>
                                            </div>                                                                              
                                        </div>                       
                                    </div>                                   
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('item.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk" title="Lưu"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>                   
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Quản lý giá bán</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a type="button" class="btn btn-success" id="btn-add" data-toggle="modal" data-target="#modal-lg">
                        <i class="fa-solid fa-plus" title="Thêm mới giá mặt hàng"></i>
                    </a>
                    <table id="example-table-2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên giá</th>
                                <th>Giá vốn</th>
                                <th>Giá bán</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($prices as $price)
                                <tr id="price-{{ $price->id }}">
                                    <td>{{ $counter++ }}</td>
                                    <td>{{ $price->name }}</td>              
                                    <td>{{ number_format($price->code_price, 0, ',', '.') }} đ</td>     
                                    <td>{{ number_format($price->sale_price, 0, ',', '.') }} đ</td>          
                                    <td>{{ $price->description }}</td>        
                                    <td>                                                       
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input isActiveSwitch" id="customSwitch{{ $price->id }}" {{ $price->is_active ? 'checked' : '' }} value="{{ $price->id }}">
                                            <label class="custom-control-label" for="customSwitch{{ $price->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm btn-edit" data-id="{{ $price->id }}" title="Sửa thông tin giá bán">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $price->id }}" title="Xóa giá bán">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>                              
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="price_id" id="price_id">
                    <input type="hidden" name="item_id" value="{{ $edit->id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tên giá</label>
                                <input type="text" name="price_name" class="form-control" placeholder="Nhập tên giá">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Giá vốn</label>
                                <input type="number" name="price_cod" class="form-control" placeholder="Nhập giá vốn">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Giá bán</label>
                                <input type="number" name="price_sale" class="form-control" placeholder="Nhập giá bán">
                            </div>
                        </div>                                                                  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Mô tả thêm</label>
                                <textarea class="form-control mb-3" name="description_price" placeholder="Nhập mô tả thêm" style="height: 100px"></textarea>
                            </div>
                        </div>                   
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="btn-save">Lưu</button>
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
            $(".isActiveSwitch").change(function () {
                let selectedId = $(this).val();
                if (!$(this).is(":checked") && $(".isActiveSwitch:checked").length === 0) {
                    $(this).prop("checked", true);
                    return;
                }
                $(".isActiveSwitch").not(this).prop("checked", false);

                $.ajax({
                    url: "/item/item-price/change/" + selectedId,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    error: function () {
                        toastr.danger("Có lỗi xảy ra!");
                    }
                });
            });

            $('body').on('click', '.btn-delete', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận xóa giá mặt hàng?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/item/item-price/destroy/" + id,
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if(response.success){
                                    toastr.success(response.message);
                                    $('#price-'+id).remove();
                                }
                                else{
                                    toastr.error(response.message);
                                }                        
                            },
                            error: function(xhr) {
                                toastr.error('Có lỗi khi xóa giá mặt hàng');
                            }
                        });
                    }
                });
            })

            $(document).on("click", "#btn-add", function () {
                $("#modal-title").text("Thêm mới giá bán");
                $("#price_id").val("");
                $("input[name='price_name']").val("");
                $("input[name='price_cod']").val("");
                $("input[name='price_sale']").val("");
                $("textarea[name='description_price']").val("");
                $("#modal-lg").modal("show");
            });

            $(document).on("click", ".btn-edit", function (e) {
                e.preventDefault();
                let id = $(this).data("id");
                $.ajax({
                    url: "/item/item-price/edit/" + id,
                    type: "GET",
                    success: function (data) {
                        $("#modal-title").text("Chỉnh sửa giá bán");
                        $("#price_id").val(data.id);
                        $("input[name='price_name']").val(data.name);
                        $("input[name='price_cod']").val(data.code_price);
                        $("input[name='price_sale']").val(data.sale_price);
                        $("textarea[name='description_price']").val(data.description);
                        $("#modal-lg").modal("show");
                    },
                    error: function () {
                        toastr.error("Lỗi khi tải dữ liệu!");
                    }
                });
            });

            $(document).on("click", "#btn-save", function () {
                const id = $("#price_id").val();
                let item_id = $("input[name='item_id']").val();
                let name = $("input[name='price_name']").val();
                let price_cod = $("input[name='price_cod']").val();
                let price_sale = $("input[name='price_sale']").val();
                let description = $("textarea[name='description_price']").val();

                if (id) {
                    // Nếu có ID -> Cập nhật (Sửa)
                    $.ajax({
                        url: "/item/item-price/update/" + id,
                        type: "POST",
                        data: {
                            item_id: item_id,
                            name: name,
                            price_cod: price_cod,
                            price_sale: price_sale,
                            description: description,
                            _token: $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function (response) {
                            if (response.success) {
                                $("#modal-lg").modal("hide");
                                toastr.success(response.message);
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            } else {
                                toastr.error(response.message);
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
                } else {
                    // Nếu không có ID -> Thêm mới
                    $.ajax({
                        url: "/item/item-price/store",
                        type: "POST",
                        data: {
                            item_id: item_id,
                            name: name,
                            price_cod: price_cod,
                            price_sale: price_sale,
                            description: description,
                            _token: $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function (response) {
                            if (response.success) {
                                $("#modal-lg").modal("hide");
                                toastr.success(response.message);
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            } else {
                                toastr.error(response.message);
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
                }
            });
        });
    </script>

    <script>
        $(function () {
            $('#quickForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 5,
                        maxlength: 50
                    },
                    unit_id: {
                        min: 1
                    },
                    category_id: {
                        min: 1
                    }
                },
                messages: {
                    name: {
                        required: "Tên mặt hàng không được để trống!",
                        minlength: "Tên mặt hàng phải có ít nhất {0} ký tự!",
                        maxlength: "Tên mặt hàng tối đa {0} ký tự!"
                    },
                    unit_id: {
                        min: "Vui lòng chọn đơn vị tính!"
                    },
                    category_id: {
                        min: "Vui lòng chọn danh mục sản phẩm!"
                    }      
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('.select2').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });
    </script>
@endsection
