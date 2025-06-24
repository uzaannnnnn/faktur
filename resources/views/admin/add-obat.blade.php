@extends('layouts.app')
@section('content')
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Tambah Obat</h3>
        </div>

        <!-- form-add-obat -->
        <form class="tf-section-2 form-add-product" method="POST" action="{{ route('obats.store') }}">
            @csrf
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Nama Obat <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Masukkan Nama Obat" name="nama_obat"
                        value="{{ old('nama_obat') }}" required maxlength="100">
                    <div class="text-tiny">Jangan melebihi 100 karakter saat memasukkan nama.</div>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">No Batch <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" name="no_batch" placeholder="Masukkan Nomor Batch"
                        value="{{ old('no_batch') }}" required maxlength="50">
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">Harga <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="number" name="harga" placeholder="Masukkan Harga (Rp)"
                        value="{{ old('harga') }}" required min="0" step="1">
                    <div class="text-tiny">Masukkan harga dalam rupiah tanpa titik/koma. Contoh: 15000</div>
                </fieldset>


                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Kemasan <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="kemasan" required>
                                <option value="">Pilih Kemasan</option>
                                <option value="fls">fls</option>
                                <option value="btl">btl</option>
                                <option value="box">box</option>
                                <option value="tube">tube</option>
                                <option value="strip">strip</option>
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="number" name="quantity" placeholder="Masukkan Jumlah"
                            value="{{ old('quantity') }}" required>
                    </fieldset>
                </div>



            </div>

            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Distributor <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" name="distributor" placeholder="Masukkan Distributor"
                        value="{{ old('distributor') }}" required>
                </fieldset>
                <fieldset class="name">
                    <div class="body-title mb-10">Pabrik <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" name="pabrik" placeholder="Masukkan Pabrik"
                        value="{{ old('pabrik') }}" required>
                </fieldset>
                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tanggal Masuk <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}"
                            required>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Expired Date <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="date" name="ed" value="{{ old('ed') }}" required>
                    </fieldset>
                </div>

                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Tambah Obat</button>
                </div>
            </div>
        </form>
        <!-- /form-add-obat -->
    </div>
@endsection
