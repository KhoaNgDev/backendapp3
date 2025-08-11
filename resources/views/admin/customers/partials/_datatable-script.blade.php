<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>

<script>
    $(document).ready(function() {
        const table = $('#customer-table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [
                [20, 40, 50, -1],
                [20, 40, 50, "Tất cả"]
            ],
            ajax: {
                url: '{{ route('admin.customers.index') }}',
                data: d => {
                    d.keyword = $('#keyword').val();
                },
                error: xhr => {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let html = '<ul class="text-start">';
                        Object.values(errors).forEach(err => html += `<li>${err[0]}</li>`);
                        html += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi tìm kiếm',
                            html
                        });
                    }
                }
            },
            columns: [{
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row, meta) =>
                        meta.row + meta.settings._iDisplayStart + 1
                },
                {
                    data: 'name',
                    name: 'name',
                    className: 'text-start fw-semibold'
                },
                {
                    data: 'last_name',
                    name: 'last_name',
                    className: 'text-start'
                },
                {
                    data: 'first_name',
                    name: 'first_name',
                    className: 'text-start'
                },
                {
                    data: 'phone',
                    name: 'phone',
                    render: data => `<span class="text-primary fw-semibold">☎ ${data}</span>`
                },
                {
                    data: 'email',
                    name: 'email',
                    render: data => data ? `<span class="text-muted">${data}</span>` : '-'
                },
                {
                    data: 'vehicles_count',
                    name: 'vehicles_count',
                    render: data => `<span class="badge bg-info">${data}</span>`
                },
                {
                    data: 'repairs_count',
                    name: 'repairs_count',
                    render: data => `<span class="badge bg-secondary">${data}</span>`
                },
                {
                    data: 'total_repair_cost',
                    name: 'total_repair_cost',
                    render: data => `<span class="text-danger fw-bold">${data}</span>`
                },
                {
                    data: 'last_repair_date',
                    name: 'last_repair_date',
                    render: data => data ?
                        `<span class="text-muted">${moment(data).format('DD/MM/YYYY')}</span>` : ''
                },
            ],
            language: {
                url: "/js/datatables/i18n/vi.json"
            }
        });

        $('#search-btn').on('click', () => {
            const keyword = $('#keyword').val().trim();

            if (keyword && (keyword.length < 2 || keyword.length > 50)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Từ khóa không hợp lệ',
                    text: 'Vui lòng nhập từ 2 đến 50 ký tự.',
                });
                return;
            }

            table.ajax.reload();
        });
        $('#reset-btn').on('click', () => {
            $('#filter-form')[0].reset();
            table.ajax.reload();
        });
        $('#export-btn').on('click', e => {
            e.preventDefault();
            const query = $.param({
                keyword: $('#keyword').val()
            });
            window.location.href = '{{ route('admin.customers.export') }}?' + query;
        });
    });
</script>
