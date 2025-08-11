<?php

namespace App\Exports;

use App\Models\Repair;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RepairsExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    use Exportable;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Repair::with(['user', 'vehicle', 'repairFeedbacks'])
            ->when($this->request->filled('month'), function ($q) {
                $q->whereMonth('repair_date', date('m', strtotime($this->request->month)))
                    ->whereYear('repair_date', date('Y', strtotime($this->request->month)));
            })
            ->when($this->request->filled('keyword'), function ($q) {
                $keyword = $this->request->keyword;
                $q->whereHas('user', function ($sub) use ($keyword) {
                    $sub->where('name', 'like', "%$keyword%")
                        ->orWhere('phone', 'like', "%$keyword%");
                })->orWhereHas('vehicle', function ($sub) use ($keyword) {
                    $sub->where('plate_number', 'like', "%$keyword%");
                });
            })
            ->when($this->request->has('has_feedback') && $this->request->has_feedback !== '', function ($q) {
                if ($this->request->has_feedback == '1') {
                    $q->whereHas('repairFeedbacks');
                } else {
                    $q->whereDoesntHave('repairFeedbacks');
                }
            });
    }

    public function headings(): array
    {
        return ['Ngày sửa', 'Khách hàng', 'SĐT', 'Biển số', 'Xe', ' Ghi Chú','Dịch vụ','Phụ tùng thay thế', 'Chi phí', 'Đánh giá'];
    }

    public function map($repair): array
    {
        $feedback = $repair->repairFeedbacks->first();
        return [
            Carbon::parse($repair->repair_date)->format('d/m/Y'),
            $repair->user->name,
            $repair->user->phone,
            $repair->vehicle->plate_number,
            $repair->vehicle->brand . ' ' . $repair->vehicle->model,
            $repair->technician_note ?? 'Chưa có',
            $repair->services_performed ?? 'Chưa có',
            $repair->parts_replaced ?? 'Chưa có',
            number_format($repair->total_cost) . ' đ',
            $feedback ? $feedback->rating . '/5' : 'Chưa có',
        ];
    }

}
