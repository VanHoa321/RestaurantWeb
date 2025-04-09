<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hóa đơn</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            text-align: center;
            background: #fff;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background: white;
        }
        h4, h5 {
            margin: 5px 0;
        }
        p {
            margin: 5px 0;
        }
        .d-flex {
            display: flex;
            justify-content: space-between;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background: #f8f8f8;
            font-weight: bold;
        }
        .fw-bold {
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .text-primary {
            color: #007bff;
        }
        .fst-italic {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div>
            <h2 class="fw-bold">Restaurant</h2>
            <p>Cơ sở 1: 121 - Lê Hồng Phong - Hưng Bình - Tp.Vinh - Nghệ An</p>
            <p>Cơ sở 2: 295 Lê Duẩn - Vinh - Nghệ An</p>
            <p>ĐT: 034.9191.354 - 0972.756.829</p>
            <h3 class="fw-bold" style="margin:10px 0">HÓA ĐƠN THANH TOÁN</h3>
        </div>

        <div style="display: table; width: 100%;">
            <div style="display: table-cell; text-align: left; width: 50%;">
                <p><strong>Ngày In:</strong> {{ \Carbon\Carbon::parse($time)->format('d/m/Y H:i:s') }}</p>
                <p><strong>Khu vực:</strong> 
                    @if(isset($invoice->table))
                        {{ $invoice->table->area->name }} - {{ $invoice->table->area->branch->name }}
                    @else
                        Mang về
                    @endif
                </p>
                <p><strong>Giờ vào:</strong> {{ \Carbon\Carbon::parse($invoice->time_in)->format('d/m/Y H:i:s') }}</p>
                <p><strong>Thu ngân:</strong> {{ $invoice->user->name }}</p>
            </div>
            <div style="display: table-cell; text-align: right; width: 50%;">
                <p><strong>Số HĐ:</strong> {{ $invoice->id }}</p>
                <p><strong>Bàn:</strong> 
                    @if(isset($invoice->table))
                        {{ $invoice->table->name }}
                    @else
                        Mang về
                    @endif   
                </p>
                <p><strong>Giờ ra:</strong> {{ \Carbon\Carbon::parse($invoice->time_out)->format('d/m/Y H:i:s') }}</p>
                <p><strong>Khách hàng:</strong> {{ $invoice->customer->full_name }}</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Món ăn</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($details as $detail)
                <tr>
                    <td>
                        @if ($detail->item_id) 
                            {{ $detail->item->name }}
                        @elseif ($detail->combo_id) 
                            {{ $detail->combo->name }}
                        @endif
                    </td>
                    <td>{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->amount, 0, ',', '.') }} đ</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="3" class="fw-bold">Tổng cộng:</td>
                    <td class="fw-bold">{{ number_format($invoice->total_amount, 0, ',', '.') }} đ</td>
                </tr>
            </tbody>
        </table>

        <div class="text-end">
            <h4 class="fw-bold">Tổng tiền: <span class="text-primary">{{ number_format($invoice->total_amount, 0, ',', '.') }} đ</span></h4>
        </div>

        <p class="fst-italic">Cảm ơn quý khách - Hẹn gặp lại</p>
    </div>
</body>
</html>