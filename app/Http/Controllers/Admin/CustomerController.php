<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query()
                ->withCount(['vehicles', 'repairs'])
                ->withSum('repairs as total_repair_cost', 'total_cost')
                ->withMax('repairs as last_repair_date', 'repair_date')
                ->where([
                    ['id', '!=', 1],
                    ['status', 'active'],
                    ['is_delete', 0],
                    ['group_role', 'User'],
                ])
                ->whereHas('vehicles')
                ->whereHas('repairs');

            if ($request->filled('keyword')) {
                $keyword = trim($request->keyword);
                if (strlen($keyword) >= 2 && strlen($keyword) <= 50) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                            ->orWhere('first_name', 'like', "%{$keyword}%")
                            ->orWhere('last_name', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%");
                    });
                }
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('phone', fn($row) => $row->phone)
                ->editColumn('email', fn($row) => $row->email ?? '-')
                ->editColumn('vehicles_count', fn($row) => $row->vehicles_count)
                ->editColumn('repairs_count', fn($row) => $row->repairs_count)
                ->editColumn('total_repair_cost', function ($row) {
                    return $row->total_repair_cost > 0
                        ? number_format((float) $row->total_repair_cost, 0, ',', '.') . ' đ'
                        : '-';
                })
                ->editColumn('last_repair_date', function ($row) {
                    return $row->last_repair_date
                        ? Carbon::parse($row->last_repair_date)->format('d/m/Y')
                        : '-';
                })
                ->rawColumns(['email']) // nếu có custom HTML
                ->make(true);
        }

        return view('admin.customers.index');
    }

    public function export(Request $request)
    {
        return Excel::download(new CustomersExport($request), 'khach-hang.xlsx');
    }
}
