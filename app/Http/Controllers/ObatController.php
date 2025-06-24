<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Obat::query();

        // Cek apakah ada parameter pencarian
        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;

            // Filter berdasarkan kolom yang relevan
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%$search%")
                    ->orWhere('no_batch', 'like', "%$search%")
                    ->orWhere('distributor', 'like', "%$search%")
                    ->orWhere('pabrik', 'like', "%$search%");
            });
        }

        // Ambil data dengan pagination dan simpan query string pencarian
        $obats = $query->paginate(10);

        return view('admin.obat', compact('obats'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.add-obat');
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama_obat' => 'required|max:100',
            'no_batch' => 'required|max:50',
            'kemasan' => 'required|in:fls,btl,box,tube,strip',
            'distributor' => 'required|max:100',
            'pabrik' => 'required|max:100',
            'quantity' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'ed' => 'required|date|after_or_equal:tanggal_masuk',
        ]);

        // Penentuan status otomatis
        $quantity = $request->quantity;
        $ed = Carbon::parse($request->ed);
        $status = 'tersedia';

        if ($quantity == 0) {
            $status = 'habis';
        } elseif ($ed->isPast()) {
            $status = 'expired';
        }

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'no_batch' => $request->no_batch,
            'kemasan' => $request->kemasan,
            'distributor' => $request->distributor,
            'pabrik' => $request->pabrik,
            'quantity' => $quantity,
            'harga' => $request->harga,
            'tanggal_masuk' => $request->tanggal_masuk,
            'ed' => $ed,
            'status' => $status,
        ]);

        return redirect()->route('obats')->with('success', 'Obat berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Obat $obat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Obat $obat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:obats,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $obat = Obat::findOrFail($request->id);
        $obat->quantity = $request->quantity;

        // Hitung ulang status
        if ($request->quantity == 0) {
            $obat->status = 'habis';
        } elseif (Carbon::parse($obat->ed)->isPast()) {
            $obat->status = 'expired';
        } else {
            $obat->status = 'tersedia';
        }

        $obat->save();

        return redirect()->back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function updateHarga(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:obats,id',
            'harga' => 'required|numeric|min:0'
        ]);

        $obat = Obat::find($request->id);
        $obat->harga = $request->harga;
        $obat->save();

        return redirect()->back()->with('success', 'Harga berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Obat $obat)
    {
        //
    }
}
