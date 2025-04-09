@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('inv-present.index') }}" class="text-info">Hóa đơn</a></li>
                        <li class="breadcrumb-item active text-info"><a href="{{ route('invoice-history.index') }}" class="text-info">Lịch sử hóa đơn</a></li>
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
            <div class="col-12 col-sm-12">
                <div class="card card-info card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tabs-0" data-toggle="pill" href="#tab-0" role="tab" aria-controls="tab-0" aria-selected="true">Tất cả</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabs-1" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">Chờ xác nhận thanh toán</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabs-2" data-toggle="pill" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Đã thanh toán</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabs-3" data-toggle="pill" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">Đã hủy</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="tab-0" role="tabpanel" aria-labelledby="tabs-0">
                                @if($groupedInvoices->isNotEmpty())
                                    <table id="example-table-6" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Thu ngân</th>
                                                <th>Khu vực/Bàn</th>
                                                <th>Thông tin KH</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                            @endphp
                                            @foreach($groupedInvoices as $status => $invoices)
                                                @foreach($invoices as $invoice)
                                                    <tr>
                                                        <td>{{ $counter++ }}</td>
                                                        <td>{{ $invoice->user ? $invoice->user->name : "Chưa cập nhật" }}</td>
                                                        <td>{{ $invoice->table ? $invoice->table->area->name : "Không có" }} / {{ $invoice->table ? $invoice->table->name : "Không có" }}</td>
                                                        <td>{{ $invoice->customer->full_name }}<br>{{ $invoice->customer->phone }}</td>                                                        
                                                        <td>{{ number_format($invoice->total_amount, 0, ',', '.') }} đ</td>
                                                        @if ($invoice->status == 1)
                                                            <td><span class="badge bg-warning bg-opacity-50 px-2 py-2">Chờ thanh toán</span></td>
                                                        @elseif ($invoice->status == 2)
                                                            <td><span class="badge bg-success bg-opacity-50 px-2 py-2">Đã thanh toán</span></td>
                                                        @else
                                                            <td><span class="badge bg-danger bg-opacity-50 px-2 py-2">Đã hủy</span></td>
                                                        @endif                                                   
                                                        <td>
                                                            <a href="{{route('invoice-history.detail', $invoice->id)}}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </a>
                                                            <a href="{{route('invoice.pdf', $invoice->id)}}" class="btn btn-secondary btn-sm" title="In hóa đơn">
                                                                <i class="fa-solid fa-print"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>                                   
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center text-center w-100">
                                        <img src="/storage/files/1/Blog/8183434.jpg" alt="Đặt bàn thành công" style="max-width: 350px;">
                                        <h5 class="mt-3 text-success">
                                            <em>Chưa có hóa đơn với trạng thái này!</em>
                                        </h5>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="tab-1" role="tabpanel" aria-labelledby="tabs-1">
                                @if(!empty($groupedInvoices[1]) && count($groupedInvoices[1]) > 0)
                                    <table id="example-table-7" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Thu ngân</th>
                                                <th>Khu vực/Bàn</th>
                                                <th>Thông tin KH</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groupedInvoices[1] as $invoice)
                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $invoice->user ? $invoice->user->name : "Chưa cập nhật" }}</td>
                                                    <td>{{ $invoice->table ? $invoice->table->area->name : "Không có" }} / {{ $invoice->table ? $invoice->table->name : "Không có" }}</td>
                                                    <td>{{ $invoice->customer->full_name }}<br>{{ $invoice->customer->phone }}</td>                                                        
                                                    <td>{{ number_format($invoice->total_amount, 0, ',', '.') }} đ</td>
                                                    <td><span class="badge bg-warning bg-opacity-50 px-2 py-2">Chờ thanh toán</span></td>                                               
                                                    <td>
                                                        <a href="{{route('booking-history.detail', $invoice->id)}}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>                                      
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center text-center w-100">
                                        <img src="/storage/files/1/Blog/8183434.jpg" alt="Đặt bàn thành công" style="max-width: 350px;">
                                        <h5 class="mt-3 text-success">
                                            <em>Chưa có hóa đơn với trạng thái này!</em>
                                        </h5>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="tabs-2">
                                @if(!empty($groupedInvoices[2]) && count($groupedInvoices[2]) > 0)
                                    <table id="example-table-8" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Thu ngân</th>
                                                <th>Khu vực/Bàn</th>
                                                <th>Thông tin KH</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groupedInvoices[2] as $invoice)
                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $invoice->user ? $invoice->user->name : "Chưa cập nhật" }}</td>
                                                    <td>{{ $invoice->table ? $invoice->table->area->name : "Không có" }} / {{ $invoice->table ? $invoice->table->name : "Không có" }}</td>
                                                    <td>{{ $invoice->customer->full_name }}<br>{{ $invoice->customer->phone }}</td>                                                        
                                                    <td>{{ number_format($invoice->total_amount, 0, ',', '.') }} đ</td>
                                                    <td><span class="badge bg-success bg-opacity-50 px-2 py-2">Đã thanh toán</span></td>                                              
                                                    <td>
                                                        <a href="{{route('booking-history.detail', $invoice->id)}}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>                                      
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center text-center w-100">
                                        <img src="/storage/files/1/Blog/8183434.jpg" alt="Đặt bàn thành công" style="max-width: 350px;">
                                        <h5 class="mt-3 text-success">
                                            <em>Chưa có hóa đơn với trạng thái này!</em>
                                        </h5>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="tabs-3">
                                @if(!empty($groupedInvoices[0]) && count($groupedInvoices[0]) > 0)
                                    <table id="example-table-9" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Thu ngân</th>
                                                <th>Khu vực/Bàn</th>
                                                <th>Thông tin KH</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groupedInvoices[0] as $invoice)
                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $invoice->user ? $invoice->user->name : "Chưa cập nhật" }}</td>
                                                    <td>{{ $invoice->table ? $invoice->table->area->name : "Không có" }} / {{ $invoice->table ? $invoice->table->name : "Không có" }}</td>
                                                    <td>{{ $invoice->customer->full_name }}<br>{{ $invoice->customer->phone }}</td>                                                        
                                                    <td>{{ number_format($invoice->total_amount, 0, ',', '.') }} đ</td>
                                                    <td><span class="badge bg-danger bg-opacity-50 px-2 py-2">Đã hủy</span></td>                                                 
                                                    <td>
                                                        <a href="{{route('booking-history.detail', $invoice->id)}}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>                                      
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center text-center w-100">
                                        <img src="/storage/files/1/Blog/8183434.jpg" alt="Đặt bàn thành công" style="max-width: 350px;">
                                        <h5 class="mt-3 text-success">
                                            <em>Chưa có hóa đơn với trạng thái này!</em>
                                        </h5>
                                    </div>
                                @endif
                            </div>
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
            $('#example-table-6').DataTable({
                pageLength: 10,
                scrollCollapse: true,
                language: {
                    "lengthMenu": "Hiển thị _MENU_ mục",
                    "search": "Tìm kiếm:",
                    "zeroRecords": "Không tìm thấy dữ liệu",
                    "info": "Hiển thị từ _START_ đến _END_ của _TOTAL_ mục",
                    "infoEmpty": "Hiển thị từ 0 đến 0 của 0 mục",
                    "infoFiltered": "(được lọc từ _MAX_ tổng số mục)",
                    "paginate": {
                        "first": "Đầu",
                        "last": "Cuối",
                        "next": "Tiếp",
                        "previous": "Trước"
                    }
                },
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5
            });

            $('#example-table-7').DataTable({
                pageLength: 10,
                scrollCollapse: true,
                language: {
                    "lengthMenu": "Hiển thị _MENU_ mục",
                    "search": "Tìm kiếm:",
                    "zeroRecords": "Không tìm thấy dữ liệu",
                    "info": "Hiển thị từ _START_ đến _END_ của _TOTAL_ mục",
                    "infoEmpty": "Hiển thị từ 0 đến 0 của 0 mục",
                    "infoFiltered": "(được lọc từ _MAX_ tổng số mục)",
                    "paginate": {
                        "first": "Đầu",
                        "last": "Cuối",
                        "next": "Tiếp",
                        "previous": "Trước"
                    }
                },
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5
            });

            $('#example-table-8').DataTable({
                pageLength: 10,
                scrollCollapse: true,
                language: {
                    "lengthMenu": "Hiển thị _MENU_ mục",
                    "search": "Tìm kiếm:",
                    "zeroRecords": "Không tìm thấy dữ liệu",
                    "info": "Hiển thị từ _START_ đến _END_ của _TOTAL_ mục",
                    "infoEmpty": "Hiển thị từ 0 đến 0 của 0 mục",
                    "infoFiltered": "(được lọc từ _MAX_ tổng số mục)",
                    "paginate": {
                        "first": "Đầu",
                        "last": "Cuối",
                        "next": "Tiếp",
                        "previous": "Trước"
                    }
                },
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5
            });

            $('#example-table-9').DataTable({
                pageLength: 10,
                scrollCollapse: true,
                language: {
                    "lengthMenu": "Hiển thị _MENU_ mục",
                    "search": "Tìm kiếm:",
                    "zeroRecords": "Không tìm thấy dữ liệu",
                    "info": "Hiển thị từ _START_ đến _END_ của _TOTAL_ mục",
                    "infoEmpty": "Hiển thị từ 0 đến 0 của 0 mục",
                    "infoFiltered": "(được lọc từ _MAX_ tổng số mục)",
                    "paginate": {
                        "first": "Đầu",
                        "last": "Cuối",
                        "next": "Tiếp",
                        "previous": "Trước"
                    }
                },
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5
            });
        })
    </script>
@endsection