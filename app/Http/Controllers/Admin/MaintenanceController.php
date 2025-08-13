<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MaintenanceExport;
use App\Http\Controllers\Controller;
use App\Mail\MaintenanceReminderMail;
use App\Models\MaintenanceSchedules;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MaintenanceController extends Controller
{

    public function reminders(Request $request)
    {
        $reminders = MaintenanceSchedules::with(['vehicle.user', 'vehicle.latestRepair'])
            ->whereHas('vehicle.user', function ($query) {
                $query->where('id', '!=', 1);
            })
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $this->applyKeywordFilter($query, $request->keyword);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('month'), function ($query) use ($request) {
                $query->whereMonth('next_maintenance_date', $request->month);
            })
            ->orderByDesc('next_maintenance_date')
            ->paginate(20);

        return view('admin.maintenance.reminders', compact('reminders'));
    }
    protected function applyKeywordFilter($query, $keyword)
    {
        $query->where(function ($q) use ($keyword) {
            $q->whereHas('vehicle.user', function ($userQuery) use ($keyword) {
                $userQuery->where('name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            })->orWhereHas('vehicle', function ($vehicleQuery) use ($keyword) {
                $vehicleQuery->where('plate_number', 'like', "%{$keyword}%");
            });
        });
    }


    public function sendReminderEmail($id)
    {
        $reminder = MaintenanceSchedules::with(['vehicle.user', 'vehicle.latestRepair'])->findOrFail($id);
        $vehicle = $reminder->vehicle;
        $repair = $vehicle->latestRepair ?? null;

        $user = $vehicle->user;

        if (!$user || !$user->email) {
            return response()->json(['message' => 'Không có email khách hàng'], 422);
        }

        $nextDate = Carbon::parse($reminder->next_maintenance_date)->format('d/m/Y');

        $apiKey = config('services.brevo.api_key');
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'api-key' => $apiKey,
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => 'Garage System',
                'email' => 'nganhkhoa.becloud@gmail.com'
            ],
            'to' => [
                [
                    'email' => $user->email,
                    'name' => $user->name
                ]
            ],
            'subject' => 'Nhắc bảo trì xe',
            'htmlContent' => "
                <p>Xin chào {$user->name},</p>
                <p>Xe của bạn ({$vehicle->plate_number}) dự kiến bảo trì vào ngày <strong>{$nextDate}</strong>.</p>
                <p>Vui lòng liên hệ với chúng tôi để đặt lịch.</p>
                <p>Trân trọng,<br>Garage</p>
            ",
        ]);

        if ($response->successful()) {
            $reminder->update([
                'status' => 'sent',
                'notified_at' => now(),
            ]);
            return response()->json(['message' => 'Đã gửi nhắc bảo trì thành công!']);
        } else {
            return response()->json([
                'message' => 'Gửi email thất bại',
                'error' => $response->json()
            ], 500);
        }
    }
    public function export(Request $request)
    {
        return (new MaintenanceExport($request))->download('maintenance_reminders.xlsx');
    }
}
