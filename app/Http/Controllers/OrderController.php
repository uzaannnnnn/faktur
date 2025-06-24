<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf; // ← tambahkan ini

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.faktur', compact('orders'));
    }
    public function fakturIndex()
    {
        $orders = Order::latest()->paginate(10);
        return view('fakturis.dashboard', compact('orders'));
    }
    public function allOrders()
    {
        $orders = Order::where('status', 'diterima')
            ->where('status_bayar', 'lunas')
            ->latest()
            ->paginate(10);

        return view('admin.orders', compact('orders'));
    }

    public function submitReturn(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'alasan_return' => 'required|string',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->status = 'return';
        $order->status_nota = null;
        $order->alasan_return = $request->alasan_return;
        $order->status_return = 'proses';
        $order->save();

        return redirect()->back()->with('success', 'Return berhasil diajukan.');
    }

    public function generateReturnNota($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'return' || $order->status_nota) {
            return redirect()->back()->with('error', 'Nota sudah dibuat atau status bukan return.');
        }

        $pdfName = 'nota-return-' . $order->no . '-' . now()->format('YmdHis') . '.pdf';
        $pdfPath = 'return-nota/' . $pdfName;

        $pdf = Pdf::loadView('pdf.return-nota', compact('order'));
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Update order
        $order->status_nota = $pdfName;
        $order->save();

        // Kosongkan tagihan & jatuh tempo di user
        $customer = $order->customer;
        $customer->tagihan = 0;
        $customer->jatuh_tempo = null;
        $customer->save();

        return redirect()->back()->with('success', 'Nota return berhasil dibuat.');
    }


    public function create()
    {
        $customers = User::where('role', 'customer')
            ->where('tagihan', 0)
            ->get();

        // Ambil obat yang tersedia dan stok > 0
        $obats = Obat::where('status', 'tersedia')
            ->where('quantity', '>', 0)
            ->get();

        return view('fakturis.add-faktur', compact('customers', 'obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_customer' => 'required|exists:users,id',
            'pesanan' => 'required|array',
            'pesanan.*.id' => 'required|exists:obats,id',
            'pesanan.*.quantity' => 'required|integer|min:0',
            'metode' => 'required|in:tunai,termin',
            'bukti_bayar' => 'nullable|file|mimes:jpg,png,pdf',
        ]);

        $customer = User::where('id', $request->id_customer)
            ->where('role', 'customer')
            ->firstOrFail();

        $no = Order::generateNo();
        $nama_customer = $customer->name;

        $pesanan = [];
        $total = 0;

        foreach ($request->pesanan as $item) {
            if ($item['quantity'] < 1) continue;

            $obat = Obat::findOrFail($item['id']);
            $subtotal = $obat->harga * $item['quantity'];

            // Kurangi stok
            $obat->decrement('quantity', $item['quantity']);

            $pesanan[] = [
                'id' => $obat->id,
                'harga' => $obat->harga,
                'quantity' => $item['quantity'],
            ];

            $total += $subtotal;
        }

        if (count($pesanan) === 0) {
            return back()->with('error', 'Minimal satu item harus memiliki quantity lebih dari 0.');
        }

        $bukti_bayar_path = null;
        if ($request->hasFile('bukti_bayar')) {
            $bukti_bayar_path = $request->file('bukti_bayar')->store('bukti-bayar');
        }

        $status_bayar = Order::determineStatusBayar($request->metode, $bukti_bayar_path);
        $file_faktur = Order::generateFileFaktur($no, $nama_customer);

        $order = Order::create([
            'no' => $no,
            'nama_customer' => $nama_customer,
            'id_customer' => $customer->id,
            'pesanan' => $pesanan,
            'total' => $total,
            'metode' => $request->metode,
            'status' => 'proses',
            'bukti_bayar' => $bukti_bayar_path,
            'status_bayar' => $status_bayar,
            'file_faktur' => $file_faktur,
        ]);

        if ($status_bayar === 'lunas') {
            $customer->update([
                'tagihan' => 0,
                'jatuh_tempo' => null,
            ]);
        } elseif ($request->metode === 'termin' && !$bukti_bayar_path) {
            $customer->update([
                'tagihan' => $customer->tagihan + $total,
                'jatuh_tempo' => now()->addMonths(3),
            ]);
        }

        // 🔽 Buat PDF dan simpan ke storage/pdf/
        $pdf = Pdf::loadView('pdf.faktur', ['order' => $order]);
        Storage::put('public/pdf/' . $file_faktur, $pdf->output());

        return redirect()->route('fakturis')->with('success', 'Order berhasil disimpan.');
    }

    public function gudangIndex()
    {
        $orders = Order::where('status', 'proses')->latest()->get();

        // Tambahan: Order return yang sudah ada nota dan status_return masih proses
        $returns = Order::where('status', 'return')
            ->whereNotNull('status_nota')
            ->where('status_return', 'proses')
            ->latest()
            ->get();

        return view('gudang.dashboard', compact('orders', 'returns'));
    }

    public function markReturnDone($id)
    {
        $order = Order::findOrFail($id);
        $order->status_return = 'done';
        $order->save();

        return redirect()->back()->with('success', 'Return berhasil ditandai selesai.');
    }


    public function kirimOrder(Order $order)
    {
        if ($order->status !== 'proses') {
            return back()->with('error', 'Pesanan tidak bisa dikirim karena statusnya bukan proses.');
        }

        $order->update([
            'status' => 'dikirim',
        ]);

        return back()->with('success', 'Pesanan berhasil dikirim.');
    }


    // public function show(Order $order)
    // {
    //     return view('fakturis.show', compact('order'));
    // }

    // public function edit(Order $order) {}
    // public function update(Request $request, Order $order) {}
    // public function destroy(Order $order) {}
}
