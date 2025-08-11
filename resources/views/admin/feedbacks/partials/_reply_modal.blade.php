<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.repair.feedbacks.reply') }}">
            @csrf
            <input type="hidden" name="repair_feedback_id" id="modal-feedback-id"
                value="{{ old('repair_feedback_id', session('feedback_id')) }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="replyModalLabel">Phản hồi đánh giá</h5>
                    <button type="button" class="btn-close btn btn-danger" data-bs-dismiss="modal">X</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reply" class="form-label">Nội dung phản hồi</label>
                        <textarea name="reply" id="reply" class="form-control" rows="4" required>{{ old('reply') }}</textarea>
                        @error('reply')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if ($errors->any() && session('modal') === 'reply')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = new bootstrap.Modal(document.getElementById('replyModal'));
            modal.show();
        });
    </script>
@endif
