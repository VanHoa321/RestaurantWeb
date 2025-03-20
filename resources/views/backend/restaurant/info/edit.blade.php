@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{route('restaurant.index')}}" class="text-info">Thiết lập nhà hàng</a></li>
                            <li class="breadcrumb-item active">Thông tin nhà hàng</li>
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
                            <form method="post" action="{{route("info.update", $edit->id)}}" id="quickForm">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Tên nhà hàng</label>
                                        <input type="text" name="name" value="{{ old('name', $edit->name) }}" class="form-control" placeholder="Nhập tên nhà hàng">
                                    </div>                                                           
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Hotline 1</label>
                                                <input type="text" name="hotline_1" value="{{ old('hotline_1', $edit->hotline_1) }}" class="form-control" placeholder="Nhập hotline 1 nhà hàng">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Hotline 2</label>
                                                <input type="text" name="hotline_2" value="{{ old('hotline_2', $edit->hotline_2) }}" class="form-control" placeholder="Nhập hotline 2 nhà hàng">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" name="email" value="{{ old('email', $edit->email) }}" class="form-control" placeholder="Nhập email nhà hàng">
                                            </div>
                                        </div>
                                    </div>                                                               
                                    <div class="form-group">
                                        <label>Mô tả thêm</label>
                                        <textarea class="form-control mb-3" name="sort_description" placeholder="Nhập mô tả ngắn" style="height: 80px">{{ old('sort_description', $edit->sort_description) }}</textarea>
                                    </div>  
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Ngày mở cửa trong tuần</label>
                                                <input type="text" name="opening_day" value="{{ old('opening_day', $edit->opening_day) }}" class="form-control" placeholder="Nhập ngày mở cửa trong tuần">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Thời gian mở cửa</label>
                                                <input type="time" name="open_time" value="{{ old('open_time', $edit->open_time) }}" class="form-control" placeholder="Nhập thời gian mở cửa">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="time" name="close_time" value="{{ old('close_time', $edit->close_time) }}" class="form-control" placeholder="Nhập thời gian đóng cửa">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Giới thiệu</label>
                                        <textarea id="summernote" class="form-control mb-3" name="log_description" placeholder="Nhập giới thiệu nhà hàng">{{ old('log_description', $edit->log_description) }}</textarea>
                                    </div>                                                                                                                    
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('restaurant.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
                    max_seats: {
                        required: true,
                        min: 1
                    }
                },
                messages: {
                    name: {
                        required: "Tên loại bàn không được để trống",
                        minlength: "Tên loại bàn phải có ít nhất {0} ký tự!",
                        maxlength: "Tên loại bàn tối đa {0} ký tự!"
                    },
                    max_seats: {
                        required: "Số chỗ ngồi không được để trống",
                        min: "Số chỗ ngồi phải lớn hơn hoặc bằng {0}!"
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
