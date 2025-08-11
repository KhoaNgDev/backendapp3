<div class="card shadow-sm">
    <div class="card-body">
        @include('admin.repairs.partials.filter')
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle text-center">
                <thead class="table-light">
                    <tr style="text-align: left">
                        <th>#</th>
                        <th>Ngày sửa</th>
                        <th>Khách hàng</th>
                        <th>Biển số</th>
                        <th>Xe</th>
                        <th class="text-start">Chi tiết sửa chữa</th>
                        <th>Đánh giá</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($repairs as $index => $r)
                        <tr style="text-align: left">
                            <td class="fw-bold">{{ $repairs->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->repair_date)->format('d/m/Y') }}</td>
                            <td class="text-start">
                                <div class="fw-semibold">{{ $r->user->name }}</div>
                                <small class="text-muted">☎ {{ $r->user->phone }}</small>
                            </td>
                            <td><span class="badge bg-info">{{ $r->vehicle->plate_number }}</span></td>
                            <td>{{ $r->vehicle->brand }} {{ $r->vehicle->model }}</td>
                            <td class="text-start">
                                <div><strong> Dịch vụ:</strong> {{ Str::limit($r->services_performed, 50) ?: '-' }}
                                </div>
                                <div><strong>Phụ tùng:</strong> {{ Str::limit($r->parts_replaced, 50) ?: '-' }}</div>
                                <div><strong>Note KTV:</strong> {{ Str::limit($r->technician_note, 50) ?: '-' }}</div>

                                <div class="mt-1 text-danger fw-bold">
                                    <strong>Chi phí:</strong> {{ number_format($r->total_cost) }} đ
                                </div>

                            </td>
                            <td>
                                @php $feedback = $r->repairFeedbacks->first(); @endphp
                                @if ($feedback)
                                    <span class="text-warning fw-semibold">⭐ {{ $feedback->rating }}/5</span>
                                @else
                                    <span class="text-muted">Chưa có</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted py-3">Không có dữ liệu sửa chữa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {!! $repairs->withQueryString()->links('pagination::bootstrap-5') !!}
        </div>
    </div>
</div>
