<?php

namespace App\Mail;

use App\Models\Repair;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MaintenanceReminderMail extends Mailable
{
    use Queueable, SerializesModels;
    public Vehicle $vehicle;
    public ?Repair $repair;
    public $nextMaintenanceDate;
    public $user;
    /**
     * Create a new message instance.
     */
    public function __construct(Vehicle $vehicle, ?Repair $repair, $nextMaintenanceDate)
    {
        $this->vehicle = $vehicle;
        $this->repair = $repair;
        $this->nextMaintenanceDate = $nextMaintenanceDate;
        $this->user = $vehicle->user;
    }

    public function build()
    {
        return $this->subject('Nhắc lịch bảo trì xe - ' . $this->vehicle->plate_number)
            ->view('mail.maintenance_reminder')
            ->with([
                'user' => $this->user,
                'vehicle' => $this->vehicle,
                'repair' => $this->repair,
                'nextMaintenanceDate' => $this->nextMaintenanceDate,
            ]);
    }

}
