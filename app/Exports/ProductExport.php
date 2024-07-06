<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ProductExport implements FromView, WithStyles, WithCustomStartCell, WithColumnWidths
{
    public function view(): View
    {
        $products = Product::with('styles', 'subcategories.mainCategory')->orderBy('name', 'asc')->get();
        return view('admin.product.table-product', ['products' => $products]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // "Data Product" header
            2 => ['font' => ['bold' => true]], // Column titles row
        ];
    }

    public function startCell(): string
    {
        return 'A3'; // Start data from cell A3 to leave space for the header
    }

    public function columnWidths(): array
    {
        $products = Product::with('styles', 'subcategories.mainCategory')->orderBy('name', 'asc')->get();

        // Calculate the maximum length for each column
        $numberMaxLength = strlen((string)$products->count()) + 2;
        $nameMaxLength = max($products->pluck('name')->map('strlen')->toArray()) + 2;
        $descriptionMaxLength = max($products->pluck('description')->map('strlen')->toArray()) + 2;
        $priceMaxLength = max($products->pluck('price')->map('strlen')->toArray()) + 2;
        $stylesMaxLength = max($products->pluck('styles')->map(function ($styles) {
            return strlen($styles->pluck('name')->implode(', '));
        })->toArray()) + 2;
        $mainCategoryMaxLength = max($products->pluck('subcategories')->map(function ($subcategories) {
            return strlen($subcategories->pluck('mainCategory.name')->implode(', '));
        })->toArray()) + 2;
        $sellMaxLength = max($products->pluck('sell')->map('strlen')->toArray()) + 2;
        $ratingMaxLength = max($products->pluck('rating')->map('strlen')->toArray()) + 2;

        return [
            'A' => $numberMaxLength,
            'B' => $nameMaxLength,
            'C' => $descriptionMaxLength,
            'D' => $priceMaxLength,
            'E' => $stylesMaxLength,
            'F' => $mainCategoryMaxLength,
            'G' => $sellMaxLength,
            'H' => $ratingMaxLength,
        ];
    }
}
