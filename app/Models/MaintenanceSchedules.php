<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceSchedules extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'repair_id',
        'next_maintenance_date',
        'status',
        'note',
        'notified_at',
    ];

    protected $dates = [
        'next_maintenance_date',
        'notified_at',
    ];
    protected $casts = [
        'next_maintenance_date' => 'date',
        'notified_at' => 'datetime',
    ];

    /** =============================
     * Relationships
     * ============================= */

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    /** =============================
     * Accessors / Helpers
     * ============================= */

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->next_maintenance_date->isPast();
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'pending' && $this->next_maintenance_date->isFuture();
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Chờ gửi',
            'sent' => 'Đã gửi',
            'overdue' => 'Quá hạn',
            default => 'Không xác định',
        };
    }
}
