<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function pesananSaya()
    {
        $userId = Auth::id();

        // Semua pesanan user
        $orders = Order::where('id_customer', $userId)->latest()->get();

        // Khusus pesanan aktif
        $activeOrders = Order::where('id_customer', $userId)
            ->whereIn('status', ['dikirim'])
            ->latest()
            ->get();

        return view('customer.dashboard', compact('orders', 'activeOrders'));
    }
    public function terima(Order $order)
    {
        if ($order->id_customer !== Auth::id()) {
            abort(403);
        }

        $order->update(['status' => 'diterima']);
        return back()->with('success', 'Pesanan telah diterima.');
    }

    public function uploadBukti(Request $request, Order $order)
    {
        if ($order->id_customer !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');

        $order->update([
            'bukti_bayar' => $path,
            'status_bayar' => 'lunas',
        ]);

        // Kosongkan tagihan & jatuh tempo customer kalau ini lunas
        $order->customer->update([
            'tagihan' => 0,
            'jatuh_tempo' => null,
        ]);

        return back()->with('success', 'Bukti bayar berhasil diupload.');
    }
}
