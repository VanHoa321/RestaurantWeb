@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('branch.index') }}" class="text-info">Thiết lập cơ sở</a></li>
                            <li class="breadcrumb-item active">Thêm mới cơ sở</li>
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
                            <form method="post" action="{{route("branch.store")}}" id="quickForm">
                                @csrf
                                <div class="card-body">                           
                                    <div class="form-group">
                                        <label>Tên cơ sở nhà hàng</label>
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Nhập tên cơ sở nhà hàng">
                                    </div>
                                    <div class="form-group">
                                        <label>Địa chỉ</label>
                                        <input type="text" name="address" value="{{ old('address') }}" class="form-control" placeholder="Nhập địa chỉ cơ sở nhà hàng">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Số điện thoại</label>
                                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Nhập số điện thoại cơ sở nhà hàng">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" name="email" value="{{ old('email') }}" class="form-control" placeholder="Nhập email cơ sở nhà hàng">
                                            </div>
                                        </div>
                                    </div>                                                               
                                    <div class="form-group">
                                        <label>Mô tả thêm</label>
                                        <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả thêm" style="height: 100px">{{ old('description') }}</textarea>
                                    </div>                                                                                                             
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('branch.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
                    address: {
                        required: true,
                        maxlength: 255
                    },
                    phone: {
                        required: true,
                        pattern: /^(0[1-9][0-9]{8,9})$/
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 100
                    }
                },
                messages: {
                    name: {
                        required: "Tên cơ sở không được để trống",
                        minlength: "Tên cơ sở phải có ít nhất {0} ký tự!",
                        maxlength: "Tên cơ sở tối đa {0} ký tự!"
                    },
                    address: {
                        required: "Địa chỉ không được để trống",
                        maxlength: "Địa chỉ tối đa 255 ký tự!"
                    },
                    phone: {
                        required: "Số điện thoại không được để trống",
                        pattern: "Số điện thoại không hợp lệ!"
                    },
                    email: {
                        required: "Email không được để trống",
                        email: "Email không đúng định dạng!",
                        maxlength: "Email tối đa 100 ký tự!"
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
