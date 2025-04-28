@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Thiết lập nhà hàng</h4>
                    </div>
                    <div class="col-sm-6">
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
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                                        <div class="text-center p-3 border rounded">
                                            <div class="mb-2">
                                                <a class="btn bg-info mb-2" href="{{ route("info.edit", 1) }}">
                                                    <i class="fas fa-store"></i>
                                                </a>
                                            </div>
                                            <h6 class="text-primary text-bold">Thông tin nhà hàng</h6>
                                            <p class="text-muted"><em>Xem và điều chỉnh thông tin nhà hàng của bạn</em></p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                                        <div class="text-center p-3 border rounded">
                                            <div class="mb-2">
                                                <a class="btn bg-info mb-2" href="{{ route("branch.index") }}">
                                                    <i class="fa-solid fa-shop"></i>
                                                </a>
                                            </div>
                                            <h6 class="text-primary text-bold">Thiết lập cơ sở</h6>
                                            <p class="text-muted"><em>Xem và điều các cơ sở nhà hàng của bạn</em></p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                                        <div class="text-center p-3 border rounded">
                                            <div class="mb-2">
                                                <a class="btn bg-info mb-2" href="{{ route("area.index") }}">
                                                    <i class="fa-solid fa-map-location-dot"></i>
                                                </a>
                                            </div>
                                            <h6 class="text-primary text-bold">Thiết lập khu vực</h6>
                                            <p class="text-muted"><em>Xem và điều chỉnh các khu vực nhà hàng của bạn</em></p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                                        <div class="text-center p-3 border rounded">
                                            <div class="mb-2">
                                                <a class="btn bg-info mb-2" href="{{ route("table.index") }}">
                                                    <i class="fas fa-utensils"></i>
                                                </a>
                                            </div>
                                            <h6 class="text-primary text-bold">Thiết lập bàn ăn</h6>
                                            <p class="text-muted"><em>Xem và điều chỉnh bàn ăn nhà hàng của bạn</em></p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                                        <div class="text-center p-3 border rounded">
                                            <div class="mb-2">
                                                <a class="btn bg-info mb-2" href="{{ route("table-type.index") }}">
                                                    <i class="fa-solid fa-table"></i>
                                                </a>
                                            </div>
                                            <h6 class="text-primary text-bold">Thiết lập loại bàn</h6>
                                            <p class="text-muted"><em>Xem và điều chỉnh loại bàn nhà hàng của bạn</em></p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                                        <div class="text-center p-3 border rounded">
                                            <div class="mb-2">
                                                <a class="btn bg-info mb-2" href="{{ route("blog-category.index") }}">
                                                    <i class="fa-solid fa-list"></i>
                                                </a>
                                            </div>
                                            <h6 class="text-primary text-bold">Thiết lập phân loại bài viết</h6>
                                            <p class="text-muted"><em>Quản lý phân loại bài viết của nhà hàng</em></p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                                        <div class="text-center p-3 border rounded">
                                            <div class="mb-2">
                                                <a class="btn bg-info mb-2" href="{{ route("blog.index") }}">
                                                    <i class="fa-solid fa-newspaper"></i>
                                                </a>
                                            </div>
                                            <h6 class="text-primary text-bold">Thiết lập bài viết</h6>
                                            <p class="text-muted"><em>Quản lý bài viết của nhà hàng</em></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection