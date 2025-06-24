<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AcceptedOrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Order::where('status', 'diterima')
            ->where('status_bayar', 'lunas')
            ->select('no', 'nama_customer', 'metode', 'total', 'created_at', 'file_faktur')
            ->get();
    }

    public function headings(): array
    {
        return ['No Faktur', 'Nama Customer', 'Metode', 'Total', 'Tanggal', 'File Faktur'];
    }

    public function map($order): array
    {
        return [
            "'" . $order->no, // pakai ' di depan agar dianggap string oleh Excel
            $order->nama_customer,
            $order->metode,
            $order->total,
            $order->created_at->format('Y-m-d H:i:s'),
            $order->file_faktur,
        ];
    }
}
