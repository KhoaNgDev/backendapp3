<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\MaintenanceReminderMail;
use App\Models\MaintenanceSchedules;
use App\Services\BrevoService;
use Carbon\Carbon;
class SendMaintenanceReminder extends Command
{
    protected $signature = 'maintenance:remind';
    protected $description = 'Gửi nhắc nhở bảo trì định kỳ tới khách hàng';

    protected $brevo;

    public function __construct(BrevoService $brevo)
    {
        parent::__construct();
        $this->brevo = $brevo;
    }

    public function handle()
    {
        try {
            $today = now()->addDay();
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

                $subject = 'Nhắc nhở bảo trì định kỳ';
                $htmlContent = view('emails.maintenance_reminder', [
                    'vehicle' => $schedule->vehicle,
                    'repair' => $schedule->repair,
                    'date' => $schedule->next_maintenance_date
                ])->render();

                $this->brevo->sendEmail(
                    $user->email,
                    $user->name ?? 'Khách hàng',
                    $subject,
                    $htmlContent
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