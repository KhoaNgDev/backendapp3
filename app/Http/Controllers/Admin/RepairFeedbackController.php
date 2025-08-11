<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FeedbacksExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Frontend\AdminReplyRequest;
use App\Models\AdminReply;
use App\Models\RepairFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RepairFeedbackController extends Controller
{
    public function index(Request $request)
    {
        $feedbacks = RepairFeedback::with(['repair.vehicle.user', 'adminReplies'])
            ->when($request->filled('keyword'), fn($q)
                => $this->filterByKeyword($q, $request->keyword))
            ->when($request->has('status'), fn($q)
                => $this->filterByReplyStatus($q, $request->status))
            ->when($request->filled('rating'), fn($q)
                => $q->where('rating', $request->rating))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.feedbacks.index', ['data' => $feedbacks]);
    }

    public function reply(AdminReplyRequest $request)
    {
        DB::beginTransaction();

        try {
            $feedback = RepairFeedback::findOrFail($request->repair_feedback_id);

            if ($feedback->adminReplies()->exists()) {
                return back()->with('error', 'Phản hồi này đã được trả lời.');
            }

            $feedback->adminReplies()->create([
                'reply' => $request->reply,
            ]);

            DB::commit();
            return back()->with('success', 'Phản hồi đã được gửi!');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Lỗi khi gửi phản hồi admin', [
                'message' => $e->getMessage(),
                'feedback_id' => $request->repair_feedback_id,
                'reply' => $request->reply,
            ]);

            return back()
                ->withInput()
                ->with([
                    'modal' => 'reply',
                    'feedback_id' => $request->repair_feedback_id,
                    'error' => 'Đã xảy ra lỗi khi gửi phản hồi. Vui lòng thử lại.',
                ]);
        }
    }

    public function export(Request $request)
    {
        return (new FeedbacksExport($request))->download('feedbacks.xlsx');
    }

    private function filterByKeyword($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('feedback', 'like', "%{$keyword}%")
                ->orWhereHas('repair.vehicle.user', function ($user) use ($keyword) {
                    $user->where('name', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                })
                ->orWhereHas('repair.vehicle', function ($vehicle) use ($keyword) {
                    $vehicle->where('plate_number', 'like', "%{$keyword}%");
                });
        });
    }

    private function filterByReplyStatus($query, $status)
    {
        return $status == 1
            ? $query->whereHas('adminReplies')
            : $query->whereDoesntHave('adminReplies');
    }
}
