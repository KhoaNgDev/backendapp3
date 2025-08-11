<?php

namespace App\Exports;

use App\Models\MaintenanceSchedules;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MaintenanceExport implements FromCollection, WithHeadings, Responsable
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return MaintenanceSchedules::with(['vehicle.user', 'vehicle.latestRepair'])
            ->when($this->request->keyword, function ($q) {
                $keyword = $this->request->keyword;
                $q->whereHas('vehicle.user', function ($user) use ($keyword) {
                    $user->where('name', 'like', "%$keyword%")
                        ->orWhere('phone', 'like', "%$keyword%")
                        ->orWhere('email', 'like', "%$keyword%");
                })->orWhereHas('vehicle', function ($v) use ($keyword) {
                    $v->where('plate_number', 'like', "%$keyword%");
                });
            })
            ->when($this->request->status, fn($q) => $q->where('status', $this->request->status))
            ->when($this->request->month, fn($q) => $q->whereMonth('next_maintenance_date', $this->request->month))
            ->get()
            ->map(function ($r) {
                return [
                    'Tên KH' => $r->vehicle->user->name ?? '-',
                    'Biển số' => $r->vehicle->plate_number,
                    'SĐT' => $r->vehicle->user->phone ?? '-',
                    'Email' => $r->vehicle->user->email ?? '-',
                    'Ngày sửa gần nhất' => $r->vehicle->latestRepair?->repair_date
                        ? Carbon::parse($r->vehicle->latestRepair->repair_date)->format('d/m/Y')
                        : '-',
'Bảo trì tiếp theo' => $r->next_maintenance_date
    ? Carbon::parse($r->next_maintenance_date)->format('d/m/Y')
    : '-',                    'Trạng thái' => $r->status,
                    'Gửi lúc' => $r->notified_at?->format('d/m/Y H:i') ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return ['Tên KH', 'Biển số', 'SĐT', 'Email', 'Ngày sửa gần nhất', 'Bảo trì tiếp theo', 'Trạng thái', 'Gửi lúc'];
    }
}
