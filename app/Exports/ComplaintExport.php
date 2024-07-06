<?php

namespace App\Exports;

use App\Models\Complaint;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ComplaintExport implements FromView, WithStyles, WithCustomStartCell, WithColumnWidths
{
    public function view(): View
    {
        $complaints = Complaint::with('user')->orderBy('user_id', 'asc')->get();
        return view('admin.transaction.table-complaint', ['complaints' => $complaints]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // "Data Complaint" header
            2 => ['font' => ['bold' => true]], // Column titles row
        ];
    }

    public function startCell(): string
    {
        return 'A3'; // Start data from cell A3 to leave space for the header
    }

    public function columnWidths(): array
    {
        $complaints = Complaint::with('user')->orderBy('user_id', 'asc')->get();

        // Calculate the maximum length for each column
        $userMaxLength = max($complaints->pluck('user.name')->map('strlen')->toArray()) + 2;
        $orderIdMaxLength = max($complaints->pluck('order_id')->map('strlen')->toArray()) + 2;
        $descriptionMaxLength = max($complaints->pluck('description')->map('strlen')->toArray()) + 2;
        $statusMaxLength = max($complaints->pluck('status')->map('strlen')->toArray()) + 2;

        return [
            'A' => $userMaxLength,
            'B' => $orderIdMaxLength,
            'C' => $descriptionMaxLength,
            'D' => $statusMaxLength,
        ];
    }
}
