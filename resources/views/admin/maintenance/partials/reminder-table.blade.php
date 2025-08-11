<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Gửi nhắc bảo trì</h4>
    </div>
    <div class="card-body">
        @include('admin.maintenance.partials.filter')
        <div class="table-responsive">
            <table class="table table-sm table-striped align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Khách hàng</th>
                        <th>Ngày sửa gần nhất</th>
                        <th>Bảo trì tiếp theo</th>
                        <th>Gửi lúc</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reminders as $i => $r)
                        <tr data-row-id="{{ $r->id }}">
                            <td>{{ $i + 1 }} | </td>
                            <td class="text-start" style="text-align: left; ">
                                <div><strong>{{ $r->vehicle->user->name ?? '-' }}</strong>
                                    {{ $r->vehicle->user->first_name }} {{ $r->vehicle->user->last_name }} </div>
                                <div>Biển số: <span class="text-primary">{{ $r->vehicle->plate_number }}</span></div>
                                <div>☎ {{ $r->vehicle->user->phone ?? '-' }}</div>
                                <div>✉ {{ $r->vehicle->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                @php
                                    $latestRepairDate = $r->vehicle->latestRepair?->repair_date;
                                @endphp

                                @if ($latestRepairDate)
                                    <span class="text-muted">
                                        {{ \Carbon\Carbon::parse($latestRepairDate)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">Chưa có</span>
                                @endif
                            </td>

                            <td>{{ \Carbon\Carbon::parse($r->next_maintenance_date)->format('d/m/Y') }}</td>
                            <td>{{ optional($r->notified_at)->format('d/m/Y H:i') ?? '-' }}</td>
                            <td>
                                <span class="{{ $r->status === 'sent' ? 'text-success fw-bold' : 'text-danger' }}">
                                    {{ $r->status === 'sent' ? 'Đã gửi' : 'Chưa gửi' }}
                                </span>
                            </td>
                            <td>
                                <button
                                    class="btn btn-sm {{ $r->status === 'sent' ? 'btn-secondary' : 'btn-success' }} send-email-btn"
                                    data-id="{{ $r->id }}">
                                    {{ $r->status === 'sent' ? 'Gửi lại' : 'Gửi Email' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">Không có nhắc bảo trì nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {!! $reminders->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        document.querySelectorAll('.send-email-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;

                Swal.fire({
                    title: 'Gửi nhắc bảo trì?',
                    text: "Bạn có chắc muốn gửi email nhắc lịch bảo trì cho khách hàng này?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Gửi ngay',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('admin/maintenance/send-reminder-email') }}/${id}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                const row = document.querySelector(`tr[data-row-id="${id}"]`);
                                if (row) {
                                    const sentAtCell = row.children[4];
                                    const statusCell = row.children[5];
                                    const actionCell = row.children[6];

                                    const now = new Date();
                                    const formatted = now.toLocaleDateString('vi-VN') + ' ' +
                                        now.toLocaleTimeString('vi-VN');

                                    sentAtCell.textContent = formatted;
                                    statusCell.innerHTML =
                                        `<span class="text-success fw-bold">Đã gửi</span>`;
                                    actionCell.innerHTML =
                                        `<button class="btn btn-sm btn-secondary">Gửi lại</button>`;

                                    row.style.backgroundColor = '#e8f5e9';
                                    setTimeout(() => row.style.backgroundColor = '', 3000);
                                }

                            })
                            .catch(err => {
                                console.error(err); 
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: 'Có vấn đề khi gửi email, vui lòng đợi lại sau.'
                                });
                            });

                    }
                });
            });
        });
    </script>
@endpush
