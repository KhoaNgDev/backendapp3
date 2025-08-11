      <table class="table table-sm table-striped align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Khách hàng & Xe</th>
                            <th>Đánh giá</th>
                            <th>Rating</th>
                            <th>Phản hồi Admin</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $i => $fb)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="text-start">
                                    <div><strong>{{ $fb->repair->vehicle->user->name ?? '-' }}</strong></div>
                                    <div class="small text-muted">
                                        Biển số: <span
                                            class="text-primary">{{ $fb->repair->vehicle->plate_number ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>{{ $fb->feedback ?? '-' }}</td>
                                <td>
                                    @foreach (range(1, 5) as $j)
                                        <i
                                            class="fas fa-star fa-lg {{ $j <= $fb->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endforeach
                                </td>
                                <td>
                                    @php $reply = $fb->adminReplies->first(); @endphp
                                    @if ($reply)
                                        <div>
                                            <span>{{ $reply->reply }}</span>
                                            <small
                                                class="d-block text-muted">{{ $reply->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Chưa phản hồi</span>
                                    @endif
                                </td>
                                <td>
                                    @if (!$reply)
                                        <button class="btn btn-sm btn-primary reply-btn"
                                            data-feedback-id="{{ $fb->id }}" data-bs-toggle="modal"
                                            data-bs-target="#replyModal">
                                            Phản hồi
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>Đã phản hồi</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted fst-italic">Không tìm thấy phản hồi nào phù hợp.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>