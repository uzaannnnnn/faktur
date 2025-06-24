@extends('layouts.app')
@section('content')
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Obat</h3>

        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">

                @auth
                    @if (auth()->user()->role === 'gudang')
                        <a class="tf-button style-1 w208" href="add-obat"><i class="icon-plus"></i>Tambah Obat</a>
                    @endif
                @endauth


                <form method="GET" action="{{ route('obats') }}" class="d-flex justify-content-end mb-3">
                    <div style="width: 400px;">
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                            placeholder="Cari obat, batch, distributor, atau pabrik...">
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th style="width: 150px">Nama Obat</th>
                            <th style="width: 150px">No Batch</th>
                            <th style="width: 80px">Kemasan</th>
                            <th>Distributor</th>
                            <th>Pabrik</th>
                            <th style="width: 70px">Quantity</th>
                            <th>Tanggal Masuk</th>
                            <th>Expired Date</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($obats as $index => $obat)
                            <tr>
                                <td>{{ ($obats->currentPage() - 1) * $obats->perPage() + $loop->iteration }}</td>
                                <td>{{ $obat->nama_obat }}</td>
                                <td>{{ $obat->no_batch }}</td>
                                <td>{{ $obat->kemasan }}</td>
                                <td>{{ $obat->distributor }}</td>
                                <td>{{ $obat->pabrik }}</td>
                                <td>
                                    {{ $obat->quantity }}
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#stokModal"
                                        data-id="{{ $obat->id }}" data-quantity="{{ $obat->quantity }}"
                                        class="ms-3 status-action stok-edit-btn">
                                        <span class="item edit"><i class="icon-edit-3"></i></span>
                                    </a>

                                </td>
                                <td>{{ \Carbon\Carbon::parse($obat->tanggal_masuk)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($obat->ed)->format('d-m-Y') }}</td>
                                <td>Rp {{ $obat->harga }}
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#hargaModal"
                                        data-id="{{ $obat->id }}" data-harga="{{ $obat->harga }}"
                                        class="ms-3 status-action harga-edit-btn">
                                        <span class="item edit"><i class="icon-edit-3"></i></span>
                                    </a>
                                </td>
                                <td>
                                    @if ($obat->status === 'tersedia')
                                        <span class="badge bg-success text-light">Tersedia</span>
                                    @elseif ($obat->status === 'habis')
                                        <span class="badge bg-info text-dark">Habis</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Expired</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $obats->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    <div class="modal fade" id="stokModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="stokModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{ route('obats.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="modal-obat-id">

                    <div class="modal-body">
                        <div style="margin-bottom: 20px">
                            <div class="body-title mb-5">Quantity</div>
                            <input type="number" name="quantity" id="modal-obat-quantity" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary p-3 fs-5" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary p-3 fs-5">Kirim</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="hargaModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="hargaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{ route('obats.update.harga') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="modal-obat-id-harga">

                    <div class="modal-body">
                        <div style="margin-bottom: 20px">
                            <div class="body-title mb-5">Harga (Rp)</div>
                            <input type="number" name="harga" id="modal-obat-harga" class="form-control" required
                                min="0">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary p-3 fs-5" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary p-3 fs-5">Kirim</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.stok-edit-btn');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const obatId = this.getAttribute('data-id');
                    const quantity = this.getAttribute('data-quantity');

                    document.getElementById('modal-obat-id').value = obatId;
                    document.getElementById('modal-obat-quantity').value = quantity;
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const hargaButtons = document.querySelectorAll('.harga-edit-btn');

            hargaButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const obatId = this.getAttribute('data-id');
                    const harga = this.getAttribute('data-harga');

                    document.getElementById('modal-obat-id-harga').value = obatId;
                    document.getElementById('modal-obat-harga').value = harga;
                });
            });
        });
    </script>
@endpush
