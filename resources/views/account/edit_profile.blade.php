@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('profile') }}" class="text-info">Thiết lập tài khoản</a></li>
                            <li class="breadcrumb-item active">Cập nhật Profile</li>
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
                    <div class="col-md-4">
                        <div class="card card-info card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img id="holder" class="profile-user-img img-fluid img-circle"
                                        src=""
                                        alt="User profile picture"
                                        style="width:250px; height:250px; object-fit:cover;">
                                    <div class="mt-3">
                                        <span class="input-group-btn mr-2">
                                            <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-info">
                                                <i class="fa-solid fa-image"></i> Chọn ảnh
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card card-info">
                            <div class="card-header p-3 text-center">
                                <h4>Thông tin tài khoản</h4>
                            </div>
                            <form method="post" action="{{route("updateProfile")}}" id="quickForm">
                                @csrf
                                <div class="card-body">
                                    <input id="thumbnail" class="form-control" type="hidden" value="{{old('avatar', $user->avatar)}}" name="avatar">
                                    <div class="form-group">
                                        <label>Họ tên</label>
                                        <input type="text" name="name" value="{{old('name', $user->name)}}" class="form-control" placeholder="Nhập họ tên người dùng">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" value="{{old('email', $user->email)}}" class="form-control" placeholder="Nhập email người dùng">
                                    </div>
                                    <div class="form-group">
                                        <label>Số điện thoại</label>
                                        <input type="text" name="phone" value="{{old('phone', $user->phone)}}" class="form-control" placeholder="Nhập số điện thoại người dùng">
                                    </div>
                                    <div class="form-group">
                                        <label>Mô tả thêm</label>
                                        <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả thêm" style="height: 60px">{{ old('description', $user->description) }}</textarea>
                                    </div>  
                                </div>
                                <div class="card-footer">
                                    <a href="{{route("profile")}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
    $(function() {
        $('#quickForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
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
                avatar: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Tên người dùng không được để trống",
                    minlength: "Tên người dùng phải có ít nhất {0} ký tự!"
                },
                email: {
                    required: "Email không được để trống",
                    email: "Email không đúng định dạng!",
                    maxlength: "Email tối đa 100 ký tự!"
                },
                phone: {
                    required: "Số điện thoại không được để trống",
                    pattern: "Số điện thoại không hợp lệ!"
                },
                avatar: {
                    required: "Vui lòng chọn ảnh đại diện"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

    $(document).ready(function() {
        var initialUrl = $('#thumbnail').val();
        if (initialUrl) {
            $('#holder').attr('src', initialUrl);
        } else {
            $('#holder').attr('src', 'http://127.0.0.1:8000/storage/files/1/Avatar/avatar4.png');
        }
    });
</script>
@endsection