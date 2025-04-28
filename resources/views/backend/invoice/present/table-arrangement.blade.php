@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('inv-present.index') }}" class="text-info">Hóa đơn</a></li>
                            <li class="breadcrumb-item active text-default">Xếp bàn</li>
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
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Hóa đơn của <span class="text-info">{{ $invoice->customer->full_name }} - {{ $invoice->customer->phone }}</span>
                                </h3>
                            </div>
                            <div class="card-body mt-3">
                                <div class="row">
                                    <div class="col-5 col-sm-2">
                                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                            @foreach($areas as $area)
                                                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tabs-area-{{ $area->id }}" data-toggle="pill" href="#vert-{{ $area->id }}" role="tab" aria-controls="vert-{{ $area->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                    {{ $area->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-7 col-sm-10">
                                        <div class="tab-content" id="vert-tabs-tabContent">
                                            @foreach($areas as $area)
                                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="vert-{{ $area->id }}" role="tabpanel" aria-labelledby="tabs-area-{{ $area->id }}">
                                                    <div class="row">
                                                        @foreach($tables->where("area_id", $area->id) as $table)
                                                            <div class="col-md-4 col-sm-6 col-12">
                                                                <div class="card">
                                                                    <div class="card-header text-center fw-bold bg-light">
                                                                        {{ $table->type->name }} - {{ $table->type->max_seats }} người
                                                                    </div>
                                                                    <div class="row g-0">
                                                                        <div class="col-4 d-flex align-items-center justify-content-center border-right">
                                                                            <a href="{{ route('inv-post.table', ['id' => $invoice->id, 'table_id' => $table->id]) }}"><h4 class="fw-bold text-info" style="font-weight: 600">{{ $table->name }}</h4></a>
                                                                        </div>
                                                                        <div class="col-8">                              
                                                                            <div class="row g-0 w-100">
                                                                                <div class="col-12 d-flex align-items-center justify-content-center py-3">
                                                                                @if ($table->status == 1)
                                                                                    <span class="badge bg-success p-2">
                                                                                        <i class="bi bi-check-circle"></i> Còn trống
                                                                                    </span>                                                                               
                                                                                @elseif ($table->status == 2)
                                                                                    <span class="badge bg-primary p-2">
                                                                                        <i class="bi bi-question-circle"></i> Đã đặt trước
                                                                                    </span>
                                                                                @else
                                                                                    <span class="badge bg-danger p-2">
                                                                                        <i class="bi bi-x-circle"></i> Đang phục vụ
                                                                                    </span>
                                                                                @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                                
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>                                            
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="card-footer">
                                <a href="{{route('inv-present.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection