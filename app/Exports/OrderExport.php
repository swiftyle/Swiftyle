<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class OrderExport implements FromView, WithStyles, WithCustomStartCell, WithColumnWidths
{
    public function view(): View
    {
        $orders = Order::with('user', 'shipping')->orderBy('id', 'asc')->get();
        return view('admin.seller.table-order', ['orders' => $orders]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // "Data Order" header
            2 => ['font' => ['bold' => true]], // Column titles row
        ];
    }

    public function startCell(): string
    {
        return 'A3'; // Start data from cell A3 to leave space for the header
    }

    public function columnWidths(): array
    {
        $orders = Order::with('user', 'shipping')->orderBy('id', 'asc')->get();

        // Calculate the maximum length for each column
        $idMaxLength = max($orders->pluck('id')->map('strlen')->toArray()) + 2;
        $userMaxLength = max($orders->pluck('user.name')->map('strlen')->toArray()) + 2;
        $shippingMaxLength = max($orders->pluck('shipping.shipping_address')->map(function ($address) {
            return strlen($address ?: 'No shipping address found');
        })->toArray()) + 2;
        $statusMaxLength = max($orders->pluck('status')->map('strlen')->toArray()) + 2;

        return [
            'A' => $idMaxLength,
            'B' => $userMaxLength,
            'C' => $shippingMaxLength,
            'D' => $statusMaxLength,
        ];
    }
}
