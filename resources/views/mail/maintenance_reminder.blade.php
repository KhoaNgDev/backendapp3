<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin: auto;
        }
        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        p {
            line-height: 1.6;
        }
        .highlight {
            font-weight: bold;
            color: #2e7d32;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Nhắc lịch bảo trì xe</h2>

    <p>Xin chào <span class="highlight">{{ $user->name }}</span>,</p>

    <p>
        Đây là email nhắc nhở lịch bảo trì định kỳ cho xe của bạn:
    </p>

    <ul>
        <li><strong>Biển số:</strong> {{ $vehicle->plate_number }}</li>
        <li><strong>Hãng xe:</strong> {{ $vehicle->brand }} - {{ $vehicle->model }}</li>
        <li><strong>Ngày bảo trì dự kiến:</strong> <span class="highlight">{{ \Carbon\Carbon::parse($nextMaintenanceDate)->format('d/m/Y') }}</span></li>
        @if($repair)
            <li><strong>Lần sửa gần nhất:</strong> {{ \Carbon\Carbon::parse($repair->repair_date)->format('d/m/Y') }}</li>
            <li><strong>Ghi chú kỹ thuật viên:</strong> {{ $repair->technician_note ?? 'Không có' }}</li>
        @endif
    </ul>

    <p>Vui lòng liên hệ garage để đặt lịch hẹn bảo trì sớm nhất, tránh hỏng hóc phát sinh.</p>

    <div class="footer">
        Trân trọng,<br>
        Đội ngũ kỹ thuật
    </div>
</div>
</body>
</html>

