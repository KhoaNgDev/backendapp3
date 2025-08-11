<form method="GET" action="{{ route('admin.repairs.index') }}" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="month" name="month" value="{{ request('month') }}" class="form-control">
    </div>

    <div class="col-md-3">
        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
               placeholder="Tìm khách hàng/biển số">
    </div>

    <div class="col-md-3">
        <select name="has_feedback" class="form-control">
            <option value="">-- Đánh giá --</option>
            <option value="1" {{ request('has_feedback') == '1' ? 'selected' : '' }}>Có đánh giá</option>
            <option value="0" {{ request('has_feedback') == '0' ? 'selected' : '' }}>Chưa có đánh giá</option>
        </select>
    </div>
 
    <div class="col-md-3 d-flex gap-1">
        <button type="submit" class="btn btn-success w-100">Lọc</button>
        <a href="{{ route('admin.repairs.index') }}" class="btn btn-secondary w-100">Đặt lại</a>
        <a href="{{ route('admin.repairs.export', request()->query()) }}" class="btn btn-primary w-100">Xuất Excel</a>
    </div>
</form>
