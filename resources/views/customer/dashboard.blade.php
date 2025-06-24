@extends('layouts.app')
@section('content')
    <h3>Halo {{ Auth::user()->name }} as {{ Auth::user()->role }} 🙌</h3>
    <h4 class="mt-5 mb-10">Pesanan yang Dikirim</h4>

    <div class="main-content-wrap">
        <div class="mb-30">
            <div class="flex gap20 flex-wrap-mobile">
                @forelse($activeOrders as $order)
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div>
                                    <div class="body-text mb-2">No. Faktur</div>
                                    <a href="{{ asset('storage/pdf/' . $order->file_faktur) }}"
                                        class="text-decoration-underline" target="_blank">
                                        <h6>{{ $order->file_faktur }}</h6>
                                    </a>
                                </div>

                            </div>
                        </div>


                        <div class="flex gap20">
                            @if ($order->status !== 'diterima')
                                <form action="{{ route('order.terima', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-success p-3 fs-4">Diterima</button>
                                </form>
                            @endif

                            <!-- Tombol Return -->
                            <button class="btn btn-danger p-3 fs-4" data-bs-toggle="modal" data-bs-target="#returnModal"
                                data-id="{{ $order->id }}" data-faktur="{{ $order->no }}">
                                Return
                            </button>

                        </div>
                    </div>
                @empty
                    <p class="text-muted">Tidak ada pesanan yang dikirim saat ini.</p>
                @endforelse
            </div>
        </div>

        <div class="tf-section mb-30">

            <div class="wg-box">
                <div class="flex items-center justify-between">
                    <h5>Riwayat Pesanan</h5>

                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-75 mx-auto">
                            <thead>
                                <tr>
                                    <th style="width: 30px">#</th>
                                    <th class="text-center w-20">Faktur</th>
                                    <th class="text-center">Tanggal Pesan</th>
                                    <th class="text-center">Metode Pembayaran</th>
                                    <th class="text-center">Tagihan</th>
                                    <th class="text-center">Jatuh Tempo</th>
                                    <th class="text-center">Status Bayar</th>
                                    <th class="text-center">Status Pesanan</th>
                                    <th class="text-center">Bukti Bayar</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($orders as $key => $order)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center">
                                            <a href="{{ asset('storage/pdf/' . $order->file_faktur) }}" target="_blank"
                                                class="text-decoration-underline">
                                                {{ $order->file_faktur }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                                        <td class="text-center text-capitalize">{{ $order->metode }}</td>
                                        <td class="text-center">
                                            {{ $order->metode === 'termin' && $order->status_bayar === 'belum bayar' ? 'Rp ' . number_format($order->total) : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $order->metode === 'termin' && $order->status_bayar === 'belum bayar' ? \Carbon\Carbon::parse(Auth::user()->jatuh_tempo)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($order->status_bayar === 'lunas')
                                                <span class="badge bg-success p-2 fs-6">Lunas</span>
                                            @else
                                                <span class="badge bg-warning text-dark p-2 fs-6">Belum Bayar</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @switch($order->status)
                                                @case('proses')
                                                    <span class="badge bg-secondary p-2 fs-6">Proses</span>
                                                @break

                                                @case('dikirim')
                                                    <span class="badge bg-info p-2 fs-6 text-white">Dikirim</span>
                                                @break

                                                @case('diterima')
                                                    <span class="badge bg-primary p-2 fs-6">Diterima</span>
                                                @break

                                                @case('return')
                                                    <span class="badge bg-danger p-2 fs-6">Return</span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            @if ($order->bukti_bayar)
                                                <a href="{{ asset('storage/' . $order->bukti_bayar) }}" target="_blank"
                                                    class="text-decoration-underline">
                                                    Lihat Bukti
                                                </a>
                                            @elseif ($order->status_bayar === 'belum bayar' || $order->bukti_bayar == null)
                                                <form action="{{ route('order.upload.bukti', $order->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <label class="btn btn-primary fs-6">
                                                        Upload
                                                        <input type="file" name="bukti_bayar" hidden
                                                            onchange="this.form.submit()">
                                                    </label>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>


                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Belum ada pesanan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>


                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal Return -->
                <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true"
                    data-bs-backdrop="false">
                    <div class="modal-dialog">
                        <form id="returnForm" method="POST" action="{{ route('orders.return') }}">
                            @csrf
                            <input type="hidden" name="order_id" id="order_id">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-3" id="returnModalLabel">Form Return</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-4">
                                        <label class="body-title mb-2">No. Faktur</label>
                                        <input type="text" class="form-control" id="faktur_display" disabled>
                                    </div>

                                    <div class="form-floating">
                                        <textarea class="form-control fs-4 pt-5" placeholder="Alasan Return.." name="alasan_return" style="height: 100px"
                                            required></textarea>
                                        <label for="floatingTextarea2" class="body-title">Alasan Return</label>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary p-3 fs-5"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary p-3 fs-5">Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endsection
            @push('scripts')
                <script>
                    const returnModal = document.getElementById('returnModal');
                    returnModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const orderId = button.getAttribute('data-id');
                        const faktur = button.getAttribute('data-faktur');

                        document.getElementById('order_id').value = orderId;
                        document.getElementById('faktur_display').value = faktur;
                    });
                </script>
            @endpush
