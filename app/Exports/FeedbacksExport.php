<?php

namespace App\Exports;

use App\Models\RepairFeedback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FeedbacksExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    use Exportable;

    protected $request;
    public string $fileName = 'feedbacks.xlsx';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return RepairFeedback::with(['repair.vehicle.user', 'adminReplies'])
            ->when($this->request->keyword, function ($q) {
                $keyword = $this->request->keyword;
                $q->where('feedback', 'like', "%$keyword%")
                  ->orWhereHas('repair.vehicle.user', fn($u) =>
                      $u->where('name', 'like', "%$keyword%")
                        ->orWhere('phone', 'like', "%$keyword%")
                        ->orWhere('email', 'like', "%$keyword%")
                  )
                  ->orWhereHas('repair.vehicle', fn($v) =>
                      $v->where('plate_number', 'like', "%$keyword%")
                  );
            })
            ->when($this->request->status !== null && $this->request->status !== '', function ($q) {
                if ($this->request->status == 1) {
                    $q->whereHas('adminReplies');
                } else {
                    $q->whereDoesntHave('adminReplies');
                }
            })
            ->when($this->request->rating, fn($q) => $q->where('rating', $this->request->rating))
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'Khách hàng',
            'Biển số xe',
            'Đánh giá',
            'Rating',
            'Phản hồi Admin',
            'Ngày tạo',
        ];
    }

    public function map($feedback): array
    {
        return [
            $feedback->repair->vehicle->user->name ?? '-',
            $feedback->repair->vehicle->plate_number ?? '-',
            $feedback->feedback ?? '-',
            $feedback->rating . ' sao',
            $feedback->adminReplies->first()->reply ?? 'Chưa phản hồi',
            optional($feedback->created_at)->format('d/m/Y H:i'),
        ];
    }
}
