      <form method="GET" action="{{ route('admin.repair.feedbacks.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                    placeholder="Tìm tên KH, biển số hoặc nội dung">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- Trạng thái phản hồi --</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đã phản hồi</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Chưa phản hồi</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-control">
                    <option value="">-- Rating --</option>
                    @foreach (range(1, 5) as $i)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-success w-50">Lọc</button>
                <a href="{{ route('admin.repair.feedbacks.index') }}" class="btn btn-secondary w-50">Đặt lại</a>
            </div>
            <div class="col-md-12 mt-2">
                <a href="{{ route('admin.repair.feedbacks.export', request()->query()) }}" class="btn btn-primary">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
            </div>
        </form>