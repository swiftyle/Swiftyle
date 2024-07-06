<?php

namespace App\Exports;

use App\Models\OrderHistory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class OrderHistoryExport implements FromView, WithStyles, WithCustomStartCell, WithColumnWidths
{
    public function view(): View
    {
        $orderHistories = OrderHistory::orderBy('id', 'asc')->get();
        return view('admin.transaction.table-order-histories', ['orderHistories' => $orderHistories]);
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // "Data Order History" header
            2 => ['font' => ['bold' => true]], // Column titles row
        ];
    }

    public function startCell(): string
    {
        return 'A3'; // Start data from cell A3 to leave space for the header
    }

    public function columnWidths(): array
    {
        $orderHistories = OrderHistory::orderBy('id', 'asc')->get();

        // Calculate the maximum length for each column
        $orderIdMaxLength = max($orderHistories->pluck('order_id')->map('strlen')->toArray()) + 2;
        $descriptionMaxLength = max($orderHistories->pluck('description')->map('strlen')->toArray()) + 2;
        $statusMaxLength = max($orderHistories->pluck('status')->map('strlen')->toArray()) + 2;

        return [
            'A' => $orderIdMaxLength,
            'B' => $descriptionMaxLength,
            'C' => $statusMaxLength,
        ];
    }
}
