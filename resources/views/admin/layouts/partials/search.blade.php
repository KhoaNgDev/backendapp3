<input type="text" id="tableSearch" class="form-control mb-3" placeholder="Tìm kiếm...">
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tableSearch').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $('#searchableTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
@endpush
