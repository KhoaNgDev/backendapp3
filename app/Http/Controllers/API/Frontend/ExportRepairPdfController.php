<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Frontend\ExportRepairPdfRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ExportRepairPdfController extends Controller
{
    public function export(ExportRepairPdfRequest $request, Vehicle $vehicle)
    {
        $id = Auth::user()->id;
        if ($id !== optional($vehicle->user)->id) {
            return response()->json([
                'message' => 'Bạn không có quyền truy cập xe này.'
            ], 403);
        }

        $vehicle->load([
            'user',
            'repairs.repairFeedbacks',
        ]);

        $pdf = Pdf::loadView('pdf.repairs', [
            'vehicle' => $vehicle,
            'user' => $vehicle->user,
            'repairs' => $vehicle->repairs
        ]);

        return $pdf->download('lich-su-sua-chua.pdf');
    }

}
