@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('admin-menu.index') }}" class="text-info">Quản lý Menu</a></li>
                            <li class="breadcrumb-item active">Chỉnh sửa Menu</li>
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
                            <form method="post" action="{{route("admin-menu.update", $edit->id)}}" id="quickForm">
                                @csrf
                                <div class="card-body">                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tên Menu</label>
                                                <input type="text" name="name" value="{{ old('name', $edit->name) }}" class="form-control" placeholder="Nhập tên Menu">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Cấp Menu</label>
                                                <select name="level" class="form-control select2bs4" style="width: 100%">
                                                    <option value="1" {{ old('level', $edit->level) == 1 ? 'selected' : '' }}>1</option>
                                                    <option value="2" {{ old('level', $edit->level) == 2 ? 'selected' : '' }}>2</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Route</label>
                                                <input type="text" name="route" value="{{ old('route', $edit->route) }}" class="form-control" placeholder="Nhập Route Menu">
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Menu cha</label>
                                                <select name="parent" class="form-control select2bs4" style="width: 100%">
                                                    <option value="0" {{ old('parent') == 0 ? 'selected' : '' }}>---Chọn Menu cha---</option>
                                                    @foreach($lists as $item)
                                                        <option value="{{$item->id}}" {{ old('parent', $item->id) == $edit->parent ? 'selected' : '' }}>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>                                     
                                    </div>                                   
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Vị trí hiển thị</label>
                                                <input type="number" name="order" value="{{ old('order', $edit->order) }}" class="form-control" placeholder="Nhập vị trí hiển thị">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Icon</label>
                                                <input type="text" name="icon" value="{{ old('icon', $edit->icon) }}" class="form-control" placeholder="Nhập Icon Menu">
                                            </div>
                                        </div>
                                    </div>                                                                                                                  
                                    <div class="form-group pt-2">
                                        <label for="is_active">Trạng thái</label>
                                        <div class="icheck-success d-inline" style="margin-left:10px">
                                            <input type="checkbox" name="is_active" id="checkboxSuccess1" value="1" {{ old('is_active', $edit->is_active) ? 'checked' : '' }}>
                                            <label for="checkboxSuccess1"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('admin-menu.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
                        minlength: 2,
                    },
                    order: {
                        required: true,
                        min: 1
                    },
                    icon: {
                        required: true,
                        minlength: 2,
                    },                  
                    route: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Tên Menu không được để trống",
                        minlength: "Tên Menu phải có ít nhất {0} ký tự!"
                    },
                    order: {
                        required: "Vị trí hiển thị không được để trống",
                        min: "Vị trí hiển thị phải lớn hơn {0}!"
                    },
                    icon: {
                        required: "Icon Menu không được để trống",
                        minlength: "Icon Menu phải có ít nhất {0} ký tự!"
                    },                
                    route: {
                        required: "Tên Route không được để trống"
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
