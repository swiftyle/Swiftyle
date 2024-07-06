<?php

namespace App\Exports;

use App\Models\MainCategory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CategoryExport implements FromView, WithStyles, WithCustomStartCell, WithColumnWidths
{
    public function view(): View
    {
        $mainCategories = MainCategory::orderBy('name', 'asc')->get();
        return view('admin.product.table-categories', ['mainCategories' => $mainCategories]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // "Data Categories" header
            2 => ['font' => ['bold' => true]], // Column titles row
        ];
    }

    public function startCell(): string
    {
        return 'A3'; // Start data from cell A3 to leave space for the header
    }

    public function columnWidths(): array
    {
        $mainCategories = MainCategory::orderBy('name', 'asc')->get();
        $nameMaxLength = max(array_map('strlen', $mainCategories->pluck('name')->toArray())) + 2;
        $descriptionMaxLength = max(array_map('strlen', $mainCategories->pluck('description')->toArray())) + 2;

        return [
            'A' => $nameMaxLength,
            'B' => $descriptionMaxLength,
        ];
    }
}
