@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('user.index') }}" class="text-info">Nhân viên</a></li>
                            <li class="breadcrumb-item active">Thông tin nhân viên</li>
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
                                    <img class="profile-user-img img-fluid img-circle"
                                        src="{{$user->avatar}}"
                                        alt="User profile picture"
                                        style="width:150px; height:150px; object-fit:cover;">
                                </div>
                                <h3 class="profile-username text-center mt-2">{{$user->user_name}}</h3>
                                <p class="text-muted text-center">{{$user->group->name}}</p>
                                <p class="text-muted text-center">{{$user->branch->name}}</p>
                                <p class="text-muted text-center">{{$user->email}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card card-info">
                            <div class="card-header p-3 text-center">
                                <h4>Thông tin tài khoản</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><strong>Họ tên</strong></div>
                                    <div class="col-lg-9 col-md-8">{{$user->name}}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><strong>Số điện thoại</strong></div>
                                    <div class="col-lg-9 col-md-8">{{$user->phone ? "$user->phone" : "Chưa cập nhật"}}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><strong>Ngày tạo tài khoản</strong></div>
                                    <div class="col-lg-9 col-md-8">{{$user->created_at->format('d/m/Y')}}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><strong>Trạng thái</strong></div>
                                    <div class="col-lg-9 col-md-8">{{$user->is_active? "Hoạt động" : "Bị khóa"}}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><strong>Đăng nhập gần nhất</strong></div>
                                    <div class="col-lg-9 col-md-8">{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d/m/Y H:i:s') : 'Chưa đăng nhập' }}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><strong>Mô tả thêm</strong></div>
                                    <div class="col-lg-9 col-md-8">{{$user->description ? $user->description : "Chưa cập nhật"}}</div>
                                </div>
                                <hr>
                                <div class="text-center p-1">
                                    <a href="{{ route("user.index") }}" class="btn btn-info btn-sm" title="Quay lại">
                                        <i class="fa-solid fa-rotate-left"></i> Quay lại
                                    </a>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
