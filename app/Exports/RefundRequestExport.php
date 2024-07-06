<?php

namespace App\Exports;

use App\Models\RefundRequest;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class RefundRequestExport implements FromView, WithStyles, WithCustomStartCell, WithColumnWidths
{
    public function view(): View
    {
        $refundRequest = RefundRequest::with('user')->orderBy('user_id', 'asc')->get();
        return view('admin.refund.table-refund-request', ['refundRequest' => $refundRequest]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // "Data Refund Request" header
            2 => ['font' => ['bold' => true]], // Column titles row
        ];
    }

    public function startCell(): string
    {
        return 'A3'; // Start data from cell A3 to leave space for the header
    }

    public function columnWidths(): array
    {
        $refundRequest = RefundRequest::with('user')->orderBy('user_id', 'asc')->get();

        // Calculate the maximum length for each column
        $userMaxLength = max($refundRequest->pluck('user.name')->map('strlen')->toArray()) + 2;
        $orderIdMaxLength = max($refundRequest->pluck('order_id')->map('strlen')->toArray()) + 2;
        $descriptionMaxLength = max($refundRequest->pluck('reason')->map('strlen')->toArray()) + 2;
        $statusMaxLength = max($refundRequest->pluck('status')->map('strlen')->toArray()) + 2;

        return [
            'A' => $userMaxLength,
            'B' => $orderIdMaxLength,
            'C' => $descriptionMaxLength,
            'D' => $statusMaxLength,
        ];
    }
}
