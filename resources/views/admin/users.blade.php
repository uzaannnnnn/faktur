@extends('layouts.app')
@section('content')
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Users</h3>

        </div>

        <div class="wg-box">
            <a class="tf-button style-1 w208" href="add-user"><i class="icon-plus"></i>Tambah User</a>
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered w-75 mx-auto">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th style="width: 200px">Nama</th>
                                <th style="width: 220px">Email</th>
                                <th>Alamat</th>
                                <th style="width: 90px">Role</th>
                                <th class="text-center" style="width: 130px">Tagihan</th>
                                <th style="width: 160px">Jatuh Tempo</th>
                                <th style="width: 160px">Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                                <tr>
                                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                    <td>
                                        {{ $user->name }}
                                        @if ($user->status === 'aktif')
                                            <span class="badge bg-success ms-3">Aktif</span>
                                        @elseif ($user->status === 'block')
                                            @if ($user->status === 'block')
                                                <form method="POST" action="{{ route('users.aktifkan', $user->id) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="badge bg-danger ms-3 border-0"
                                                        onclick="return confirm('Aktifkan kembali user ini?')">Block</button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->alamat ?? '-' }}</td>
                                    <td><span class="role-text">{{ ucfirst($user->role) }}</span></td>
                                    <td class="text-center">
                                        @if ($user->tagihan > 0)
                                            <a href="{{ route('orders', $user->id) }}"
                                                target="_blank">{{ number_format($user->tagihan, 0, ',', '.') }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $user->jatuh_tempo ?? '-' }}</td>
                                    <td>
                                        @if ($user->status === 'block')
                                            @if ($user->sales)
                                                <span class="badge bg-info">{{ $user->sales }}</span>
                                            @else
                                                <button class="btn btn-sm btn-warning fs-5 tambah-sales-btn"
                                                    data-id="{{ $user->id }}">Tambah Sales</button>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Sales -->
    <div class="modal fade" id="tambahSalesModal" tabindex="-1" data-bs-backdrop="false" aria-labelledby="tambahSalesLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('users.tambahSales') }}">
                @csrf
                <input type="hidden" name="user_id" id="modal-user-id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Sales</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="sales" class="form-control" required
                            placeholder="Masukkan nama sales">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.tambah-sales-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    document.getElementById('modal-user-id').value = userId;
                    const modal = new bootstrap.Modal(document.getElementById('tambahSalesModal'));
                    modal.show();
                });
            });
        });
    </script>
@endpush
