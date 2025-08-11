<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'repair_date',
        'technician_note',
        'total_cost',
        'services_performed',
        'parts_replaced',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }


    public function repairFeedbacks()
    {
        return $this->hasMany(RepairFeedback::class)->with('adminReplies');
    }
    public function maintenanceSchedule()
    {
        return $this->hasOne(MaintenanceSchedules::class);
    }


}
