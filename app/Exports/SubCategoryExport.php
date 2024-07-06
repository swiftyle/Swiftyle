<?php

namespace App\Exports;

use App\Models\SubCategory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SubCategoryExport implements FromView, WithStyles, WithCustomStartCell, WithColumnWidths
{
    public function view(): View
    {
        $subCategories = SubCategory::with('mainCategory')->orderBy('name', 'asc')->get();
        return view('admin.product.table-sub-category', ['subCategories' => $subCategories]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // "Data Sub Categories" header
            2 => ['font' => ['bold' => true]], // Column titles row
        ];
    }

    public function startCell(): string
    {
        return 'A3'; // Start data from cell A3 to leave space for the header
    }

    public function columnWidths(): array
    {
        $subCategories = SubCategory::with('mainCategory')->orderBy('name', 'asc')->get();

        // Calculate the maximum length for each column
        $nameMaxLength = max($subCategories->pluck('name')->map('strlen')->toArray()) + 2;
        $descriptionMaxLength = max($subCategories->pluck('description')->map('strlen')->toArray()) + 2;
        $mainCategoryMaxLength = max($subCategories->pluck('mainCategory.name')->map('strlen')->toArray()) + 2;

        return [
            'A' => $nameMaxLength,
            'B' => $descriptionMaxLength,
            'C' => $mainCategoryMaxLength,
        ];
    }
}
