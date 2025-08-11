@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const apiUrl = "{{ route('admin.dashboard.statistics') }}";
    const statCards = document.getElementById('statCards');
    const maintenanceBody = document.getElementById('maintenanceBody');

    const metrics = [
        { key: 'total_users', trendKey: 'trend_users', label: 'Tổng người dùng', icon: 'fa-users', color: 'primary', link: '{{ route('admin.customers.index') }}' },
        { key: 'total_repairs', trendKey: 'trend_repairs', label: 'Lượt sửa chữa', icon: 'fa-tools', color: 'success', link: '{{ route('admin.repairs.index') }}' },
        { key: 'average_rating', trendKey: null, label: 'Trung bình đánh giá', icon: 'fa-star', color: 'warning', link: '{{ route('admin.repair.feedbacks.index') }}' },
        { key: 'admin_replies', trendKey: null, label: 'Phản hồi admin', icon: 'fa-reply', color: 'info', link: '{{ route('admin.repair.feedbacks.index') }}' },
        { key: 'upcoming_maintenance_count', trendKey: null, label: 'Bảo trì sắp tới', icon: 'fa-calendar-check', color: 'dark', link: '{{ route('admin.maintenance.reminders') }}' },
        { key: 'overdue_maintenance', trendKey: null, label: 'Bảo trì quá hạn', icon: 'fa-exclamation-triangle', color: 'danger', link: '{{ route('admin.maintenance.reminders') }}' },
    ];

    function renderCards(data) {
        statCards.innerHTML = '';
        metrics.forEach((item, index) => {
            const value = data[item.key] ?? '--';
            const trend = item.trendKey ? data[item.trendKey] : [];
            const chartId = `chart-${index}`;

            statCards.innerHTML += `
                <div class="col-md-4">
                    <div class="card text-white bg-${item.color} shadow-sm hover-card" onclick="window.location.href='${item.link}'">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-uppercase fw-bold">${item.label}</div>
                                    <h4 class="mb-0">${value}</h4>
                                </div>
                                <i class="fas ${item.icon} fa-2x opacity-75"></i>
                            </div>
                            ${trend.length ? `<canvas id="${chartId}" height="50" class="mt-2"></canvas>` : ''}
                        </div>
                    </div>
                </div>
            `;

            if (trend.length > 0) {
                const ctx = document.getElementById(chartId).getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: trend.map(d => d.date.slice(5)),
                        datasets: [{
                            data: trend.map(d => d.total),
                            backgroundColor: 'rgba(255,255,255,0.7)',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        plugins: { legend: { display: false } },
                        scales: { x: { display: false }, y: { display: false } },
                        maintainAspectRatio: false
                    }
                });
            }
        });
    }

    function renderMaintenanceTable(schedules) {
        maintenanceBody.innerHTML = '';
        if (!schedules.length) {
            maintenanceBody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Không có lịch nào sắp tới</td></tr>`;
            return;
        }

        schedules.forEach((item, index) => {
            maintenanceBody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.next_maintenance_date}</td>
                    <td>${item.plate_number}</td>
                    <td>${item.owner}</td>
                    <td>${item.note ?? ''}</td>
                </tr>
            `;
        });
    }

    function renderRatingChart(data) {
        const ctx = document.getElementById('ratingChart').getContext('2d');
        const labels = data.ratings_breakdown.map(r => `${r.rating} ★`);
        const totals = data.ratings_breakdown.map(r => r.total);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Số lượt đánh giá',
                    data: totals,
                    backgroundColor: '#ffc107',
                    borderRadius: 4
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Lượt' } }
                }
            }
        });
    }

    function renderMonthlyChart(data) {
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const months = Object.keys(data.monthly_summary);
        const repairs = months.map(m => data.monthly_summary[m]?.repairs || 0);
        const vehicles = months.map(m => data.monthly_summary[m]?.vehicles || 0);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    { label: 'Sửa chữa', data: repairs, backgroundColor: '#17a2b8', borderRadius: 3 },
                    { label: 'Xe đăng ký', data: vehicles, backgroundColor: '#6f42c1', borderRadius: 3 }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Số lượng' } },
                    x: { title: { display: true, text: 'Tháng' } }
                }
            }
        });
    }

    function loadStatistics() {
        fetch(apiUrl)
            .then(res => res.json())
            .then(data => {
                renderCards(data);
                renderMaintenanceTable(data.upcoming_schedules);
                renderRatingChart(data);
                renderMonthlyChart(data);
            });
    }

    document.addEventListener('DOMContentLoaded', loadStatistics);
</script>

<style>
    .hover-card {
        transition: 0.2s ease-in-out;
    }

    .hover-card:hover {
        transform: scale(1.02);
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
    }

    #maintenanceTable td,
    #maintenanceTable th {
        vertical-align: middle;
    }
</style>
@endpush
