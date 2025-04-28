@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('unit.index') }}" class="text-info">Đơn vị tính</a></li>
                            <li class="breadcrumb-item active">Chỉnh sửa đơn vị tính</li>
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
                            <form method="post" action="{{route("unit.update", $edit->id)}}" id="quickForm">
                                @csrf
                                <div class="card-body">                                
                                    <div class="form-group">
                                        <label>Tên đơn vị tính</label>
                                        <input type="text" name="name" value="{{ old('name', $edit->name) }}" class="form-control" placeholder="Nhập tên đơn vị tính">
                                    </div>
                                    <div class="form-group">
                                        <label>Mô tả thêm</label>
                                        <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả thêm" style="height: 100px">{{ old('description', $edit->description) }}</textarea>
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
                                    <a href="{{route('unit.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
                        maxlength: 50
                    }
                },
                messages: {
                    name: {
                        required: "Tên đơn vị tính không được để trống",
                        minlength: "Tên đơn vị tính phải có ít nhất {0} ký tự!",
                        maxlength: "Tên đơn vị tính tối đa {0} ký tự!"
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
