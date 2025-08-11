<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminReply;
use App\Models\MaintenanceSchedules;
use App\Models\Repair;
use App\Models\RepairFeedback;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.dashboard.index');
    }

    public function statistics(Request $request)
    {
        $range = $request->input('range', '7d');
        $days = $range === '30d' ? 30 : 7;

        return response()->json([
            'total_users' => $this->totalUsers(),
            'total_vehicles' => $this->totalVehicles(),
            'total_repairs' => $this->totalRepairs(),
            'average_rating' => $this->averageRating(),
            'admin_replies' => $this->adminRepliesCount(),
            'upcoming_maintenance_count' => $this->upcomingMaintenanceCount(),
            'overdue_maintenance' => $this->overdueMaintenanceCount(),
            'upcoming_schedules' => $this->upcomingSchedules(),
            'trend_users' => $this->trendData(User::where('is_delete', 0), 'created_at', $days),
            'trend_vehicles' => $this->trendData(Vehicle::query(), 'created_at', $days),
            'trend_repairs' => $this->trendData(Repair::query(), 'created_at', $days),
            'ratings_breakdown' => $this->ratingStats(),
            'monthly_summary' => $this->monthlySummary()
        ]);
    }

    protected function totalUsers()
    {
        return User::where('is_delete', 0)->count();
    }

    protected function totalVehicles()
    {
        return Vehicle::count();
    }

    protected function totalRepairs()
    {
        return Repair::count();
    }

    protected function averageRating()
    {
        return round(RepairFeedback::avg('rating') ?? 0, 1);
    }

    protected function adminRepliesCount()
    {
        return AdminReply::count();
    }

    protected function upcomingMaintenanceCount()
    {
        return MaintenanceSchedules::where('status', 'pending')->count();
    }

    protected function overdueMaintenanceCount()
    {
        return MaintenanceSchedules::where('status', 'overdue')->count();
    }
    protected function upcomingSchedules()
    {
        return MaintenanceSchedules::with(['vehicle.user'])
            ->where('status', 'pending')
            ->whereDate('next_maintenance_date', '>=', now())
            ->orderBy('next_maintenance_date')
            ->limit(10)
            ->get()
            ->map(function ($schedule) {
                return [
                    'next_maintenance_date' => Carbon::parse($schedule->next_maintenance_date)->format('d/m/Y'),
                    'plate_number' => $schedule->vehicle->plate_number ?? '',
                    'owner' => $schedule->vehicle->user->name ?? '',
                    'note' => $schedule->note ?? '',
                ];
            });
    }


    protected function trendData($query, $column = 'created_at', $days = 7)
    {
        return $query->selectRaw("DATE($column) as date, COUNT(*) as total")
            ->where($column, '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($item) => [
                'date' => Carbon::parse($item->date)->format('Y-m-d'),
                'total' => $item->total
            ])
            ->values();
    }

    protected function ratingStats()
    {
        return RepairFeedback::selectRaw('rating, COUNT(*) as total')
            ->whereNotNull('rating')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->map(fn($r) => [
                'rating' => (int) $r->rating,
                'total' => $r->total
            ]);
    }

    protected function monthlySummary()
    {
        $months = collect(range(0, 11))->map(fn($i) => Carbon::now()->subMonths($i)->format('Y-m'))->reverse();

        $repairData = Repair::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')->pluck('total', 'month');

        $vehicleData = Vehicle::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')->pluck('total', 'month');

        return $months->mapWithKeys(fn($month) => [
            $month => [
                'repairs' => $repairData[$month] ?? 0,
                'vehicles' => $vehicleData[$month] ?? 0
            ]
        ]);
    }
}
