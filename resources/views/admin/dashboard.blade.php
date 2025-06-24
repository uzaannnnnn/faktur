@extends('layouts.app')
@section('content')

    <h3>Halo Admin 🙌</h3>
    @if ($stokMinimum->count())
        <div class="alert alert-warning p-4 mt-5 fs-5">
            <strong class="fs-4">⚠️ Stok Minimum Ditemukan:</strong>
            <ul class="mt-2">
                @foreach ($stokMinimum as $obat)
                    <li class="mb-3">{{ $obat->nama_obat }} — Stok: {{ $obat->quantity }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($hampirExpired->count())
        <div class="alert alert-danger p-4 fs-5">
            <strong class="fs-4">⚠️ Obat Hampir Expired:</strong>
            <ul class="mt-2">
                @foreach ($hampirExpired as $obat)
                    <li>{{ $obat->nama_obat }} — Expired: {{ \Carbon\Carbon::parse($obat->ed)->format('d-m-Y') }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection
