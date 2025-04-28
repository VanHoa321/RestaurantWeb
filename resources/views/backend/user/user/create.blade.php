@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('item.index') }}" class="text-info">Nhân viên</a></li>
                            <li class="breadcrumb-item active">Thêm mới nhân viên</li>
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
                            <form method="post" action="{{route("user.store")}}" id="quickForm">
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
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Họ tên</label>
                                                        <input type="text" name="name" class="form-control" placeholder="VD: Võ Văn Hòa" value="{{old('name')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tài khoản đăng nhập</label>
                                                        <input type="text" name="user_name" class="form-control" placeholder="VD: vanhoa123" value="{{old('user_name')}}">
                                                    </div>
                                                </div>
                                            </div>      
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" placeholder="VD: vanhoa12092003@gmail.com" value="{{old('email')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Số điện thoại</label>
                                                        <input type="text" name="phone" class="form-control" placeholder="VD: 0349191354" value="{{old('phone')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Mật khẩu đăng nhập</label>
                                                        <input type="password" name="password" id="password" class="form-control" placeholder="Tối thiểu 6 ký tự" value="{{old('password')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Xác nhận mật khẩu</label>
                                                        <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại chính xác mật khẩu" value="{{old('confirm_password')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Cơ sở làm việc</label>
                                                        <select name="branch_id" class="form-control select2bs4" style="width: 100%">
                                                            @foreach($branchs as $branch);
                                                                <option value="{{$branch->id}}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{$branch->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Vai trò</label>
                                                        <select name="group_id" class="form-control select2bs4" style="width: 100%">
                                                            @foreach($groups as $group);
                                                                <option value="{{$group->id}}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{$group->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> 
                                                </div>
                                            </div>                                                                         
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('user.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
                        minlength: 3,
                        maxlength: 50
                    },
                    user_name: {
                        required: true,
                        minlength: 3,
                        maxlength: 30
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 100
                    },
                    phone: {
                        required: true,
                        pattern: /^(0[1-9][0-9]{8,9})$/
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    name: {
                        required: "Họ tên không được để trống!",
                        minlength: "Họ tên phải có ít nhất {0} ký tự!",
                        maxlength: "Họ tên tối đa {0} ký tự!"
                    },
                    user_name: {
                        required: "Tên đăng nhập không được để trống!",
                        minlength: "Tên đăng nhập phải có ít nhất {0} ký tự!",
                        maxlength: "Tên đăng nhập tối đa {0} ký tự!",
                        remote: "Tên đăng nhập đã tồn tại!"
                    },
                    email: {
                        required: "Email không được để trống!",
                        email: "Email không hợp lệ!",
                        maxlength: "Email tối đa {0} ký tự!",
                        remote: "Email đã tồn tại!"
                    },
                    phone: {
                        required: "Số điện thoại không được để trống!",
                        pattern: "Số điện thoại không hợp lệ!"
                    },
                    password: {
                        required: "Mật khẩu đăng nhập không được để trống",
                        minlength: "Mật khẩu phải có ít nhất {0} ký tự!"
                    },
                    confirm_password: {
                        required: "Xác nhận mật khẩu không được để trống!",
                        equalTo: "Mật khẩu xác nhận không khớp!"
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
