@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('admin-menu.index') }}" class="text-info">Hệ thống</a></li>
                            <li class="breadcrumb-item active text-info"><a href="{{ route('admin-menu.index') }}" class="text-info">Quản lý Menu</a></li>
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <a type="button" class="btn btn-success" href="{{route('admin-menu.create')}}">
                                        <i class="fa-solid fa-plus" title="Thêm mới Menu"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example-table" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên Menu</th>
                                            <th>Cấp</th>
                                            <th>Menu cha</th>
                                            <th>Vị trí</th>
                                            <th>Trạng thái</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach ($menus as $menu)
                                            <tr id="menu-{{ $menu->id }}">
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $menu->name }}</td>
                                                <td>{{ $menu->level }}</t>
                                                    @if ($menu->level > 1 && $menu->parent > 0)
                                                <td>{{ $menu->parents->name }}</td>
                                                @else
                                                <td>Không</td>
                                                @endif
                                                <td>{{ $menu->order }}</td>
                                                <td>
                                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input IsActive" id="customSwitch{{ $menu->id }}" {{ $menu->is_active ? 'checked' : '' }} value="{{ $menu->id }}">
                                                        <label class="custom-control-label" for="customSwitch{{ $menu->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{route('admin-menu.edit', $menu->id)}}" class="btn btn-info btn-sm" title="Sửa Menu">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $menu->id }}" title="Xóa Menu">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $('body').on('change', '.IsActive', function(e) {
                e.preventDefault();
                var check = $(this);
                const id = check.val();
                $.ajax({
                    url: "/system/admin-menu/change/" + id,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra khi đổi trạng thái');
                    }
                });
            })

            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const menuId = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận xóa Menu?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/system/admin-menu/destroy/" + menuId,
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                $('#menu-' + menuId).remove();
                            },
                            error: function(xhr) {
                                toastr.error('Có lỗi khi xóa Menu');
                            }
                        });
                    }
                });
            })

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3500);
        })
    </script>
@endsection