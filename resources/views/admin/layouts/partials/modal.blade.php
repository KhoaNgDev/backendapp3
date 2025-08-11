    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('message'))
                Swal.fire({
                    icon: "{{ session('alert-type', 'success') }}",
                    title: "{{ session('alert-type') == 'error' ? 'Lỗi!' : 'Thành công!' }}",
                    text: "{{ session('message') }}",
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

        });
    </script>
