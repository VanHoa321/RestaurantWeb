@extends('layout/admin_layout')
    @section('title', 'Phân quyền người dùng')
    @section('content')
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">         
                            <ol class="breadcrumb float-sm-left">
                                <li class="breadcrumb-item"><a href="{{ route('group.index') }}" class="text-info">Phân quyền người dùng</a></li>
                                <li class="breadcrumb-item active">Chi tiết phân quyền</li>
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
                            <div class="card card-info">
                                <div class="card-header">
                                    <div>
                                        <h3 class="card-title">Phân quyền cho tác nhân {{ $group->name }}</h3>    
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12" id="accordion">
                                            <div class="row">
                                                @foreach ($menuParents as $parent)
                                                    @php
                                                        $count = collect($menus)->where('parent', $parent->id)->count();
                                                        $isChecked = in_array($parent->id, $roles);
                                                        $isDisabled = ($group->id == 1 && $parent->id == 6);
                                                    @endphp
                                                    <div class="col-md-4">
                                                        <div class="card card-info card-outline">
                                                            <a class="d-block w-100" data-toggle="collapse" href="#collapse{{ $parent->id }}">
                                                                <div class="card-header d-flex align-items-center justify-content-between">
                                                                    <div class="d-flex align-items-center">       
                                                                        <i class="{{ $parent->icon }} nav-icon" style="margin-right:5px; color: gray"></i>                                                          
                                                                        <h4 class="card-title m-0" style="color: gray;">{{ $parent->name }}</h4>
                                                                        @if ($count > 0)
                                                                            <span class="badge badge-info ml-2">{{ $count }}</span>
                                                                        @endif
                                                                    </div>                    
                                                                    <a href="#">
                                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                            <input type="checkbox" class="custom-control-input role-checkbox" id="customSwitch{{ $parent->id }}" {{ $isChecked ? 'checked' : '' }} {{ $isDisabled ? 'checked disabled' : '' }} value="{{ $parent->id }}">
                                                                            <label class="custom-control-label" for="customSwitch{{ $parent->id }}"></label>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </a>
                                                            @if ($count > 0)
                                                                <div id="collapse{{ $parent->id }}" class="collapse" data-parent="#accordion">
                                                                    <div class="card-body">
                                                                        @foreach ($menus as $menu)
                                                                            @if ($menu->parent == $parent->id)
                                                                                <p>{{ $menu->name }}</p>                                                                               
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>    
                                                @endforeach                                         
                                            </div>                                       
                                        </div>
                                    </div>                                  
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('group.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
                                    <button type="button" id="saveRoles" class="btn btn-primary"><i class="fa-solid fa-floppy-disk" title="Lưu"></i></button>
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
            $(function () {
                bsCustomFileInput.init();
            });
        </script>
        <script>
            $(document).ready(function () {

                $('body').on('click', '#saveRoles', function (e) {
                    e.preventDefault();
                    let selectedRoles = [];
                    $(".role-checkbox:checked").each(function () {
                        selectedRoles.push($(this).val());
                    });
                    console.log(selectedRoles);
                    $.ajax({
                        url: "{{ route('group.updateRoles', $group->id) }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            roles: selectedRoles
                        },
                        success: function (response) {
                            if (response.success) {                               
                                toastr.success(response.message);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Có lỗi xảy ra khi đổi trạng thái');
                        }
                    });
                })

                setTimeout(function() {
                    $("#myAlert").fadeOut(500);
                },3500);
            })
        </script>                           
@endsection
