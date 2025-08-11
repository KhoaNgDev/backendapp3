<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Frontend\RepairLookupRequest;
use App\Http\Traits\OtpHandler;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RepairLookupController extends Controller
{
    use OtpHandler;

    public function search(RepairLookupRequest $request)
    {
        $phone = $request->input('phone');
        $plate = $request->input('plate');

        if (!$phone && !$plate) {
            return response()->json(['message' => 'Vui lòng nhập số điện thoại hoặc biển số'], 400);
        }

        $query = Vehicle::with([
            'user:id,name,email',
            'repairs.repairFeedbacks.adminReplies'
        ]);

        if ($plate) {
            $query->where('plate_number', $plate);
        }

        if ($phone) {
            $query->whereHas('user', fn($q) => $q->where('phone', 'like', "%$phone%"));
        }

        $vehicles = $query->get();

        if ($vehicles->isEmpty()) {
            return response()->json([], 200);
        }

        return response()->json($vehicles, 200);
    }

   
    public function requestOtpForLookup(Request $request)
    {
        $request->validate([
            'plate' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $vehicleQuery = Vehicle::with('user');

        if ($request->filled('plate')) {
            $vehicleQuery->where('plate_number', $request->plate);
        }

        if ($request->filled('phone')) {
            $vehicleQuery->whereHas('user', fn($q) => $q->where('phone', 'like', "%{$request->phone}%"));
        }

        $vehicle = $vehicleQuery->first();

        if (!$vehicle || !$vehicle->user || !$vehicle->user->email) {
            return response()->json(['message' => 'Không tìm thấy người dùng phù hợp.'], 404);
        }

        try {
            $this->sendOtpTo($vehicle->user, 'lookup');
            return response()->json(['message' => 'Đã gửi OTP tới email người dùng.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 429);
        }
    }

    public function verifyOtpForLookup(Request $request)
    {
        $validated = $request->validate([
            'plate' => 'nullable|string',
            'phone' => 'nullable|string',
            'otp' => 'required|string',
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP',
        ]);

        $vehicleQuery = Vehicle::with('user');

        if (!empty($validated['plate'])) {
            $vehicleQuery->where('plate_number', $validated['plate']);
        }

        if (!empty($validated['phone'])) {
            $vehicleQuery->whereHas('user', fn($q) => $q->where('phone', 'like', '%' . $validated['phone'] . '%'));
        }

        $vehicle = $vehicleQuery->first();

        if (!$vehicle || !$vehicle->user) {
            return response()->json(['message' => 'Không tìm thấy người dùng để xác minh OTP.'], 404);
        }

        try {
            $this->verifyOtp($vehicle->user, $validated['otp'], 'lookup');
            return response()->json(['message' => 'Xác thực OTP thành công.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }




}
