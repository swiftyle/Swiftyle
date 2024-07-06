<?php

namespace App\Exports;

use App\Models\Preference;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PreferenceExport implements FromView, WithStyles, WithCustomStartCell, WithColumnWidths
{
    public function view(): View
    {
        $preferences = Preference::orderBy('user_id', 'asc')->get();
        return view('admin.product.table-preference', ['preferences' => $preferences]);
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
        $preferences = Preference::with(['user', 'style'])->orderBy('user_id', 'asc')->get();

        $userMaxLength = $preferences->map(function ($preference) {
            return strlen($preference->user->name);
        })->max() + 2;

        $styleMaxLength = $preferences->map(function ($preference) {
            return strlen($preference->style->name);
        })->max() + 2;

        return [
            'A' => $userMaxLength,
            'B' => $styleMaxLength,
        ];
    }
}
