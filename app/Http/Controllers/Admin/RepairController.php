<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RepairsExport;
use App\Http\Controllers\Controller;
use App\Models\Repair;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index(Request $request)
    {
        $repairs = Repair::with(['user', 'vehicle', 'repairFeedbacks'])
            ->when($request->filled('month'), fn($q) => $this->filterByMonth($q, $request->month))
            ->when($request->filled('keyword'), fn($q) => $this->filterByKeyword($q, $request->keyword))
            ->when(!is_null($request->has_feedback), fn($q) => $this->filterByFeedback($q, $request->has_feedback))
            ->orderByDesc('repair_date')
            ->paginate(20);

        return view('admin.repairs.index', compact('repairs'));
    }

    public function export(Request $request)
    {
        return (new RepairsExport($request))->download('repairslist.xlsx');
    }

    protected function filterByMonth($query, $monthInput)
    {
        $date = date_create($monthInput);
        if (!$date) return $query;

        return $query->whereYear('repair_date', $date->format('Y'))
                     ->whereMonth('repair_date', $date->format('m'));
    }

    protected function filterByKeyword($query, $keyword)
    {
        $keyword = trim($keyword);

        return $query->where(function ($q) use ($keyword) {
            $q->whereHas('vehicle', function ($v) use ($keyword) {
                $v->where('plate_number', 'like', "%{$keyword}%")
                  ->orWhere('brand', 'like', "%{$keyword}%")
                  ->orWhere('model', 'like', "%{$keyword}%");
            })->orWhereHas('user', function ($u) use ($keyword) {
                $u->where('name', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        });
    }

    protected function filterByFeedback($query, $hasFeedback)
    {
        return $hasFeedback
            ? $query->whereHas('repairFeedbacks')
            : $query->whereDoesntHave('repairFeedbacks');
    }

}
