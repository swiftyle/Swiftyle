<?php

namespace App\Exports;

use App\Models\Shop;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ShopsExport implements FromView, WithStyles, WithCustomStartCell, WithColumnWidths
{
    protected $shops;

    public function __construct()
    {
        $this->shops = Shop::orderBy('name', 'asc')->get();
    }

    public function view(): View
    {
        return view('admin.seller.tableshop', ['shops' => $this->shops]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // "Data User" header
            2 => ['font' => ['bold' => true]], // Column titles row
        ];
    }

    public function startCell(): string
    {
        return 'A3'; // Start data from cell A3 to leave space for the header
    }

    public function columnWidths(): array
    {
        $columnWidths = [];
        foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $column) {
            $columnWidths[$column] = $this->calculateColumnWidth($column);
        }
        return $columnWidths;
    }

    private function calculateColumnWidth($column)
    {
        $maxLength = 0;
        foreach ($this->shops as $shop) {
            $field = $this->getFieldByColumn($column);
            $value = $this->getValueByField($shop, $field);
            $length = strlen((string) $value);
            if ($length > $maxLength) {
                $maxLength = $length;
            }
        }
        // Add some padding to the length
        return $maxLength + 2;
    }

    private function getFieldByColumn($column)
    {
        $fields = [
            'A' => 'name',
            'B' => 'user->name',
            'C' => 'rating',
            'D' => 'email',
            'E' => 'address',
            'F' => 'phone',
        ];

        return $fields[$column];
    }

    private function getValueByField($shop, $field)
    {
        $parts = explode('->', $field);
        $value = $shop;
        foreach ($parts as $part) {
            $value = $value->{$part};
        }
        return $value;
    }
}
