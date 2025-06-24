@extends('layouts.app')
@section('content')
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between mb-27">
            <h3>Buat Faktur</h3>
        </div>

        <form class="form-add-faktur" id="fakturForm" method="POST" action="{{ route('fakturis.store') }}"
            enctype="multipart/form-data">
            @csrf
            <div class="wg-box">
                {{-- Nama Customer --}}
                <fieldset class="name ps-3" style="width: 30%">
                    <div class="body-title mb-10">Nama Customer<span class="tf-color-1">*</span></div>
                    <div class="select">
                        <select name="id_customer" id="id_customer" required>
                            <option value="">Pilih Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </fieldset>

                {{-- Tabel Obat --}}
                <div class="d-flex justify-content-end mb-3">
                    <div style="width: 40%;">
                        <input type="text" class="form-control" id="searchInput"
                            placeholder="Cari nama obat atau no batch...">
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-striped table-bordered" id="obatTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Obat</th>
                                <th>No Batch</th>
                                <th>Kemasan</th>
                                <th>Harga</th>
                                <th>Expired Date</th>
                                <th>Stock</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($obats as $index => $obat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $obat->nama_obat }}</td>
                                    <td>{{ $obat->no_batch }}</td>
                                    <td>{{ $obat->kemasan }}</td>
                                    <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($obat->ed)->format('d-m-Y') }}</td>
                                    <td>{{ $obat->quantity }}</td>
                                    <td>
                                        <input type="hidden" name="pesanan[{{ $index }}][id]"
                                            value="{{ $obat->id }}">
                                        <input type="number" class="border border-secondary quantity-input"
                                            name="pesanan[{{ $index }}][quantity]" min="0"
                                            max="{{ $obat->quantity }}" value="0" data-harga="{{ $obat->harga }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Metode Pembayaran --}}
                <fieldset class="name ps-3 mt-5" style="width: 30%">
                    <div class="body-title mb-10">Metode Pembayaran<span class="tf-color-1">*</span></div>
                    <div class="select">
                        <select name="metode" id="metode" required>
                            <option value="">Pilih Metode</option>
                            <option value="tunai">Tunai</option>
                            <option value="termin">Termin</option>
                        </select>
                    </div>
                </fieldset>

                {{-- Total --}}
                <div class="ps-3 mt-3 fs-5" id="totalDisplay">Total: Rp 0</div>

                {{-- Submit --}}
                <div class="cols justify-end pe-5 mt-4">
                    <button type="submit" class="tf-button">Buat!</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Script total --}}
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#obatTable tbody tr');

            rows.forEach(row => {
                const namaObat = row.cells[1].textContent.toLowerCase(); // kolom "Nama Obat"
                const noBatch = row.cells[2].textContent.toLowerCase(); // kolom "No Batch"

                if (namaObat.includes(filter) || noBatch.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('input', updateTotal);
        });

        document.getElementById('metode').addEventListener('change', function() {
            const metode = this.value;
            const buktiField = document.getElementById('buktiBayarField');
            if (metode === 'termin') {
                buktiField.style.display = 'none';
            } else {
                buktiField.style.display = 'block';
            }
        });

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.quantity-input').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const harga = parseInt(input.dataset.harga) || 0;
                total += qty * harga;
            });
            document.getElementById('totalDisplay').textContent = 'Total: Rp ' + total.toLocaleString('id-ID');
        }
    </script>
@endsection
