@extends('layouts.app')
@section('content')
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Tambah User</h3>
        </div>

        <!-- form-add-user -->
        <form class="tf-section-2 form-add-product" method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Nama User <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" name="name" placeholder="Masukkan Nama" required maxlength="100">
                    <div class="text-tiny">Jangan melebihi 100 karakter.</div>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="email" name="email" placeholder="Masukkan Email" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">Password <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="password" name="password" placeholder="Masukkan Password" required>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">Alamat</div>
                    <input class="mb-10" type="text" name="alamat" placeholder="Alamat (Opsional)">
                </fieldset>
            </div>

            <div class="wg-box">
                <fieldset class="role">
                    <div class="body-title mb-10">Role <span class="tf-color-1">*</span></div>
                    <div class="select">
                        <select name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="fakturis">Fakturis</option>
                            <option value="gudang">Gudang</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>
                </fieldset>

                <div class="cols gap10 mt-4">
                    <button class="tf-button w-full" type="submit">Tambah User</button>
                </div>
            </div>
        </form>
        <!-- /form-add-user -->
    </div>
@endsection
