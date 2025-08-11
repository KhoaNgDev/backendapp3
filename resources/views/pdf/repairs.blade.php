<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .sub-header {
            text-align: center;
            font-size: 13px;
            margin-top: -5px;
        }

        h2 {
            font-size: 14px;
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            page-break-inside: auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            font-weight: bold;
            background-color: #eee;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-style: italic;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>BÁO CÁO LỊCH SỬ SỬA CHỮA XE</h1>
        <div class="sub-header">Ngày xuất: {{ \Carbon\Carbon::now()->format('d/m/Y') }} – Phường Sài Gòn, TP.HCM</div>
    </div>

    <h2>1. Thông tin chủ xe</h2>
    <table>
        <tr>
            <th>Họ và tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Mã khách hàng</th>
        </tr>
        <tr>
            <td>{{ $user->last_name }} {{ $user->first_name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone }}</td>
            <td>KH-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
    </table>

    <h2>2. Thông tin xe</h2>
    <table>
        <tr>
            <th>Biển số</th>
            <th>Hãng</th>
            <th>Model</th>
            <th>Màu</th>
            <th>Năm</th>
            <th>Loại xe</th>
            <th>Số máy</th>
            <th>Số khung</th>
            <th>Ghi chú</th>
        </tr>
        <tr>
            <td>{{ $vehicle->plate_number }}</td>
            <td>{{ $vehicle->brand }}</td>
            <td>{{ $vehicle->model }}</td>
            <td>{{ $vehicle->color }}</td>
            <td>{{ $vehicle->year }}</td>
            <td>{{ $vehicle->type ?? '-' }}</td>
            <td>{{ $vehicle->engine_number ?? '-' }}</td>
            <td>{{ $vehicle->chassis_number ?? '-' }}</td>
            <td>{{ $vehicle->note ?: '-' }}</td>
        </tr>
    </table>

    <h2>3. Lịch sử sửa chữa</h2>
    @if ($repairs->isEmpty())
        <p><em>Chưa có lần sửa chữa nào được ghi nhận.</em></p>
    @else
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Ngày sửa</th>
                    <th>Dịch vụ</th>
                    <th>Linh kiện thay</th>
                    <th>Ghi chú kỹ thuật</th>
                    <th>Kỹ thuật viên</th>
                    <th>Trạng thái</th>
                    <th>Chi phí (VNĐ)</th>
                    <th>Đánh giá</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($repairs as $index => $repair)
                    @php $total += $repair->total_cost; @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($repair->repair_date)->format('d/m/Y') }}</td>
                        <td>{{ $repair->services_performed ?: '-' }}</td>
                        <td>{{ $repair->parts_replaced ?: '-' }}</td>
                        <td>{{ $repair->technician_note ?: '-' }}</td>
                        <td>{{ $repair->technician_name ?? '-' }}</td>
                        <td>{{ $repair->status ?? 'Hoàn tất' }}</td>
                        <td class="text-right">{{ number_format($repair->total_cost, 0, ',', '.') }}</td>
                        <td>
                            @if ($repair->repairFeedbacks->isNotEmpty())
                                @foreach ($repair->repairFeedbacks as $feedback)
                                    <div><strong>★ {{ $feedback->rating }}/5</strong></div>
                                    <div>{{ $feedback->feedback }}</div>
                                @endforeach
                            @else
                                <em>Chưa có</em>
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr class="summary">
                    <td colspan="7" class="text-right">TỔNG CỘNG</td>
                    <td class="text-right">{{ number_format($total, 0, ',', '.') }} đ</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
        Trân trọng cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của chúng tôi!
    </div>

</body>

</html>
