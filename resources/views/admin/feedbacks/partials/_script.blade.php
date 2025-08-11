@push('scripts')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
  

        document.querySelectorAll('.reply-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.feedbackId;
                document.getElementById('modal-feedback-id').value = id;
            });
        });

        @if (session('success'))
            showAlert('success', 'Thành công', '{{ session('success') }}');
        @endif

        @if (session('error'))
            showAlert('error', 'Lỗi', '{{ session('error') }}');
        @endif

        @if ($errors->any())
            showAlert('error', 'Lỗi', `{!! implode('<br>', $errors->all()) !!}`);
        @endif

        function showAlert(icon, title, message) {
            Swal.fire({
                icon,
                title,
                html: message,
                timer: icon === 'success' ? 2000 : undefined,
                showConfirmButton: icon !== 'success'
            });
        }
    });
</script>
@endpush
