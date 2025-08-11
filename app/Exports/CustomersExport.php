<?php

namespace App\Exports;

use App\Models\User as Customer;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Customer::query()
            ->withCount(['vehicles', 'repairs'])
            ->withSum('repairs as total_repair_cost', 'total_cost')
            ->withMax('repairs as last_repair_date', 'repair_date')
            ->where('id', '!=', 1)
            ->where('status', 'active')
            ->where('is_delete', 0)
            ->where('group_role', 'User')
            ->whereHas('vehicles')
            ->whereHas('repairs');

        if ($this->request->filled('keyword')) {
            $keyword = $this->request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }

        return $query->orderByDesc('last_repair_date');
    }


    public function headings(): array
    {
        return [
            'Tên khách hàng',
            'SĐT',
            'Email',
            'Số xe',
            'Số lần sửa',
            'Tổng chi phí',
            'Lần sửa gần nhất',
        ];
    }

    public function map($user): array
    {
        $fullName = trim($user->first_name . ' ' . $user->last_name) ?: $user->name;

        return [
            $fullName,
            $user->phone,
            $user->email ?? '-',
            $user->vehicles_count,
            $user->repairs_count,
            $user->total_repair_cost > 0
            ? number_format((float) $user->total_repair_cost, 0, ',', '.') . ' đ'
            : '-',
            $user->last_repair_date
            ? date('d/m/Y', strtotime($user->last_repair_date))
            : '-',
        ];
    }

}
