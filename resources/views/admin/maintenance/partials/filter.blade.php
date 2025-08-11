<form method="GET" action="{{ route('admin.maintenance.reminders') }}" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
            placeholder="Tên khách hàng / Biển số / SĐT">
    </div>

    <div class="col-md-3">
        <select name="status" class="form-control">
            <option value="">-- Tất cả trạng thái --</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ gửi</option>
            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Đã gửi</option>
            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Quá hạn</option>
        </select>
    </div>

    <div class="col-md-2">
        <input type="month" name="month" value="{{ request('month') }}" class="form-control">
    </div>

    <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-success">Lọc</button>
        <a href="{{ route('admin.maintenance.reminders') }}" class="btn btn-secondary">Đặt lại</a>
        <a href="{{ route('admin.maintenance.export', request()->query()) }}" class="btn btn-primary">Xuất Excel</a>
    </div>
</form>
