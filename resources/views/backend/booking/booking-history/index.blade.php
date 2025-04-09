@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('present.index') }}" class="text-info">Đặt bàn</a></li>
                        <li class="breadcrumb-item active text-info"><a href="{{ route('booking-history.index') }}" class="text-info">Lịch sử đặt bàn</a></li>
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
                                <a class="nav-link" id="tabs-1" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">Chưa nhận bàn</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabs-2" data-toggle="pill" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">Đã nhận bàn</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabs-3" data-toggle="pill" href="#tab-3" role="tab" aria-controls="tab-3" aria-selected="false">Đã hoàn thành</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabs-4" data-toggle="pill" href="#tab-4" role="tab" aria-controls="tab-4" aria-selected="false">Đã hủy</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="tab-0" role="tabpanel" aria-labelledby="tabs-0">
                                @if($groupedBookings->isNotEmpty())
                                    <table id="example-table-6" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Dự kiến nhận bàn</th>
                                                <th>Khách hàng</th>
                                                <th>Đặt cọc</th>
                                                <th>Số khách</th>
                                                <th>Khu vực/Bàn</th>
                                                <th>Trạng thái</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groupedBookings as $status => $bookings)
                                                @foreach($bookings as $booking)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }} <br> ({{ $booking->time_slot }})</td>
                                                        <td>{{ $booking->customer->full_name }}<br>{{ $booking->customer->phone }}</td>                                                        
                                                        <td>{{ number_format($booking->pre_payment, 0, ',', '.') }} đ</td>
                                                        <td>{{ $booking->guest_count }}</td>
                                                        <td>{{ $booking->table && $booking->table->area ? $booking->table->area->name : "Không có" }} / {{ $booking->table ? $booking->table->name : "Không có" }}</td>
                                                        @if ($booking->status == 1)
                                                            <td><span class="badge bg-warning bg-opacity-50 px-2 py-2">Chưa nhận bàn</span></td>
                                                        @elseif ($booking->status == 2)
                                                            <td><span class="badge bg-info bg-opacity-50 px-2 py-2">Nhận bàn</span></td>
                                                        @elseif ($booking->status == 3)
                                                            <td><span class="badge bg-success bg-opacity-50 px-2 py-2">Hoàn thành</span></td>
                                                        @else
                                                            <td><span class="badge bg-danger bg-opacity-50 px-2 py-2">Đã hủy</span></td>
                                                        @endif                                                   
                                                        <td>
                                                            <a href="{{route('booking-history.detail', $booking->id)}}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                                                <i class="fa-solid fa-eye"></i>
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
                                            <em>Chưa có đơn đặt bàn với trạng thái này!</em>
                                        </h5>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="tab-1" role="tabpanel" aria-labelledby="tabs-1">
                                @if(!empty($groupedBookings[1]) && count($groupedBookings[1]) > 0)
                                    <table id="example-table-7" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Dự kiến nhận bàn</th>
                                                <th>Khách hàng</th>
                                                <th>Đặt cọc</th>
                                                <th>Số khách</th>
                                                <th>Khu vực/Bàn</th>
                                                <th>Trạng thái</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groupedBookings[1] as $booking)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }} <br> ({{ $booking->time_slot }})</td>
                                                    <td>{{ $booking->customer->full_name }}<br>{{ $booking->customer->phone }}</td>                                                        
                                                    <td>{{ number_format($booking->pre_payment, 0, ',', '.') }} đ</td>
                                                    <td>{{ $booking->guest_count }}</td>
                                                    <td>{{ $booking->table && $booking->table->area ? $booking->table->area->name : "Không có" }} / {{ $booking->table ? $booking->table->name : "Không có" }}</td>                                             
                                                    <td><span class="badge bg-warning text-white bg-opacity-50 px-2 py-2">Chưa nhận bàn</span></td>                                                   
                                                    <td>
                                                        <a href="{{route('booking-history.detail', $booking->id)}}" class="btn btn-info btn-sm" title="Xem chi tiết">
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
                                            <em>Chưa có đơn đặt bàn với trạng thái này!</em>
                                        </h5>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="tabs-2">
                                @if(!empty($groupedBookings[2]) && count($groupedBookings[2]) > 0)
                                    <table id="example-table-8" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Dự kiến nhận bàn</th>
                                                <th>Khách hàng</th>
                                                <th>Đặt cọc</th>
                                                <th>Số khách</th>
                                                <th>Khu vực/Bàn</th>
                                                <th>Trạng thái</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groupedBookings[2] as $booking)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }} <br> ({{ $booking->time_slot }})</td>
                                                    <td>{{ $booking->customer->full_name }}<br>{{ $booking->customer->phone }}</td>                                                        
                                                    <td>{{ number_format($booking->pre_payment, 0, ',', '.') }} đ</td>
                                                    <td>{{ $booking->guest_count }}</td>
                                                    <td>{{ $booking->table && $booking->table->area ? $booking->table->area->name : "Không có" }} / {{ $booking->table ? $booking->table->name : "Không có" }}</td>
                                                    <td><span class="badge bg-info bg-opacity-50 px-2 py-2">Nhận bàn</span></td>                                              
                                                    <td>
                                                        <a href="{{route('booking-history.detail', $booking->id)}}" class="btn btn-info btn-sm" title="Xem chi tiết">
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
                                            <em>Chưa có đơn đặt bàn với trạng thái này!</em>
                                        </h5>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="tabs-3">
                                @if(!empty($groupedBookings[3]) && count($groupedBookings[3]) > 0)
                                    <table id="example-table-9" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Dự kiến nhận bàn</th>
                                                <th>Khách hàng</th>
                                                <th>Đặt cọc</th>
                                                <th>Số khách</th>
                                                <th>Khu vực/Bàn</th>
                                                <th>Trạng thái</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groupedBookings[3] as $booking)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }} <br> ({{ $booking->time_slot }})</td>
                                                    <td>{{ $booking->customer->full_name }}<br>{{ $booking->customer->phone }}</td>                                                        
                                                    <td>{{ number_format($booking->pre_payment, 0, ',', '.') }} đ</td>
                                                    <td>{{ $booking->guest_count }}</td>
                                                    <td>{{ $booking->table && $booking->table->area ? $booking->table->area->name : "Không có" }} / {{ $booking->table ? $booking->table->name : "Không có" }}</td>
                                                    <td><span class="badge bg-success bg-opacity-50 px-2 py-2">Hoàn thành</span></td>                                              
                                                    <td>
                                                        <a href="{{route('booking-history.detail', $booking->id)}}" class="btn btn-info btn-sm" title="Xem chi tiết">
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
                                            <em>Chưa có đơn đặt bàn với trạng thái này!</em>
                                        </h5>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="tab-4" role="tabpanel" aria-labelledby="tabs-4">
                                @if(!empty($groupedBookings[0]) && count($groupedBookings[0]) > 0)
                                    <table id="example-table-10" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Dự kiến nhận bàn</th>
                                                <th>Khách hàng</th>
                                                <th>Đặt cọc</th>
                                                <th>Số khách</th>
                                                <th>Khu vực/Bàn</th>
                                                <th>Trạng thái</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groupedBookings[0] as $booking)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }} <br> ({{ $booking->time_slot }})</td>
                                                    <td>{{ $booking->customer->full_name }}<br>{{ $booking->customer->phone }}</td>                                                        
                                                    <td>{{ number_format($booking->pre_payment, 0, ',', '.') }} đ</td>
                                                    <td>{{ $booking->guest_count }}</td>
                                                    <td>{{ $booking->table && $booking->table->area ? $booking->table->area->name : "Không có" }} / {{ $booking->table ? $booking->table->name : "Không có" }}</td>
                                                    <td><span class="badge bg-danger bg-opacity-50 px-2 py-2">Đã hủy</span></td>                                              
                                                    <td>
                                                        <a href="{{route('booking-history.detail', $booking->id)}}" class="btn btn-info btn-sm" title="Xem chi tiết">
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
                                            <em>Chưa có đơn đặt bàn với trạng thái này!</em>
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

            $('#example-table-10').DataTable({
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