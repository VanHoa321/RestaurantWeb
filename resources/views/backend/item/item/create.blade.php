@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('item.index') }}" class="text-info">Danh sách mặt hàng</a></li>
                            <li class="breadcrumb-item active">Thêm mới mặt hàng</li>
                        </ol>               
                    </div>
                </div>
            </div>
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
                            <form method="post" action="{{route("item.store")}}" id="quickForm">
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
                                                </span>
                                                <input id="thumbnail" class="form-control" type="hidden" name="avatar" value="{{ old('avatar') }}">                                                                             
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Tên mặt hàng</label>
                                                <input type="text" name="name" class="form-control" placeholder="Nhập tên mặt hàng" value="{{old('name')}}">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Đơn vị tính</label>
                                                        <select name="unit_id" class="form-control select2bs4" style="width: 100%">
                                                            <option value="0" {{ old('unit_id') == 0 ? 'selected' : '' }}>---Chọn đơn vị tính---</option>
                                                            @foreach($units as $unit);
                                                                <option value="{{$unit->id}}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{$unit->name}}</option>
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
                                                                <option value="{{$cate->id}}" {{ old('category_id') == $cate->id ? 'selected' : '' }}>{{$cate->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Mô tả thêm</label>
                                                <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả thêm" style="height: 70px">{{ old('description') }}</textarea>
                                            </div>   
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Tên giá bán</label>
                                                        <input type="text" name="price_name" class="form-control" placeholder="Nhập tên giá bán" value="{{old('price_name', "Giá thường")}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Giá vốn</label>
                                                        <input type="number" name="cod_price" class="form-control" placeholder="Nhập giá vốn mặt hàng" value="{{old('cod_price')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Giá bán</label>
                                                        <input type="number" name="sale_price" class="form-control" placeholder="Nhập giá bán mặt hàng" value="{{old('sale_price')}}">
                                                    </div>
                                                </div>
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
@endsection
@section('scripts')
    <script src="{{asset("assets/plugins/jquery-validation/jquery.validate.min.js")}}"></script>
    <script src="{{asset("assets/plugins/jquery-validation/additional-methods.min.js")}}"></script>
    <script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
    <script src="{{asset("assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js")}}"></script>

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

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            },3500);
        });
    </script>
@endsection
