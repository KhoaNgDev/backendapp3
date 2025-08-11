<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\MaintenanceReminderMail;
use App\Models\MaintenanceSchedules;
use Carbon\Carbon;

class SendMaintenanceReminder extends Command
{
    protected $signature = 'maintenance:remind';
    protected $description = 'Gửi nhắc nhở bảo trì định kỳ tới khách hàng';

    public function handle()
    {
        try {
            $today = Carbon::today()->addDays(1); // Ngày mai
            $schedules = MaintenanceSchedules::with(['vehicle.user', 'repair'])
                ->whereDate('next_maintenance_date', $today)
                ->where('status', 'pending')
                ->get();

            $count = 0;

            foreach ($schedules as $schedule) {
                $user = $schedule->vehicle->user ?? null;
                if (!$user || !$user->email) {
                    continue;
                }

                Mail::to($user->email)->send(
                    new MaintenanceReminderMail(
                        $schedule->vehicle,
                        $schedule->repair,
                        $schedule->next_maintenance_date
                    )
                );

                $schedule->update([
                    'status' => 'sent',
                    'notified_at' => now(),
                ]);

                $count++;
            }

            Log::info("[Scheduler] Đã gửi $count email nhắc bảo trì định kỳ.");
        } catch (\Exception $e) {
            Log::error("[Scheduler] Lỗi khi gửi nhắc bảo trì: " . $e->getMessage());
        }
    }
}
