<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Frontend\AdminReplyRequest;
use App\Http\Requests\API\Frontend\RepairRatingRequest;
use App\Models\Repair;
use App\Models\RepairFeedback;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepairRatingController extends Controller
{
    public function store(RepairRatingRequest $request)
    {
        DB::beginTransaction();

        try {
            $repair = Repair::with('vehicle.user')->findOrFail($request->repair_id);

            $id = Auth::user()->id;
            if ($id !== optional($repair->vehicle->user)->id) {
                return response()->json([
                    'message' => 'Bạn không có quyền đánh giá lần sửa chữa này.'
                ], 403);
            }

            $feedback = RepairFeedback::where('repair_id', $request->repair_id)->first();

            if ($feedback) {
                $feedback->update([
                    'feedback' => $request->feedback,
                    'rating' => $request->rating,
                ]);
            } else {
                $feedback = new RepairFeedback();
                $feedback->repair_id = $request->repair_id;
                $feedback->feedback = $request->feedback;
                $feedback->rating = $request->rating;
                $feedback->save();
            }

            $feedback->load('adminReplies');

            DB::commit();

            return response()->json([
                'id' => $feedback->id,
                'repair_id' => $feedback->repair_id,
                'rating' => $feedback->rating,
                'feedback' => $feedback->feedback,
                'created_at' => $feedback->created_at->format('d/m/Y H:i'),
                'admin_replies' => $feedback->adminReplies
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Có lỗi xảy ra khi gửi/cập nhật đánh giá.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $feedback = RepairFeedback::with('repair.vehicle.user')->findOrFail($id);
            $idU = Auth::user()->id;

            if ($idU !== optional($feedback->repair->vehicle->user)->id) {
                return response()->json([
                    'message' => 'Bạn không có quyền xoá đánh giá này.'
                ], 403);
            }

            // Xoá phản hồi admin trước
            $feedback->adminReplies()->delete();
            $feedback->delete();

            DB::commit();

            return response()->json(['message' => 'Đã xoá đánh giá và phản hồi thành công.']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Có lỗi xảy ra khi xoá đánh giá và phản hồi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
