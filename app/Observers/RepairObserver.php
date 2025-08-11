<?php

namespace App\Observers;

use App\Models\Repair;
use App\Models\MaintenanceSchedules;
use Carbon\Carbon;

class RepairObserver
{
    public function created(Repair $repair): void
    {
        $this->createOrUpdateSchedule($repair);
    }

    public function updated(Repair $repair): void
    {
        if ($repair->isDirty('repair_date')) {
            $this->createOrUpdateSchedule($repair);
        }
    }

    protected function createOrUpdateSchedule(Repair $repair): void
    {
        MaintenanceSchedules::where('vehicle_id', $repair->vehicle_id)
            ->where('status', 'pending')
            ->delete();

        MaintenanceSchedules::create([
            'vehicle_id' => $repair->vehicle_id,
            'repair_id' => $repair->id,
            'next_maintenance_date' => Carbon::parse($repair->repair_date)
                ->addMonths(6)
                ->format('Y-m-d'),
            'status' => 'pending',
            'note' => 'Tự động tạo từ sửa chữa ngày ' . $repair->repair_date,
        ]);
    }
}
