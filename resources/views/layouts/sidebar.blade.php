@php
    $role = Auth::user()->role;
@endphp

<div class="section-menu-left">
    <div class="box-logo">
        <a href="{{ url('/') }}" id="site-logo-inner">
            <img class="" id="logo_header" alt="" src="{{ asset('images/logo/logo.png') }}"
                data-light="{{ asset('images/logo/logo.png') }}" data-dark="{{ asset('images/logo/logo.png') }}" />
        </a>
        <div class="button-show-hide">
            <i class="icon-menu-left"></i>
        </div>
    </div>

    <div class="center">
        <div class="center-item">
            <div class="center-heading">Main Home</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="/{{ $role }}/dashboard" class="">
                        <div class="icon"><i class="icon-grid"></i></div>
                        <div class="text">Dashboard</div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="center-item">
            <ul class="menu-list">

                {{-- hanya admin dan gudang yang bisa lihat menu obat --}}
                @if ($role === 'admin' || $role === 'gudang')
                    <li class="menu-item has-children">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-aid-kit"></i></div>
                            <div class="text">Obat-Obat</div>
                        </a>
                        <ul class="sub-menu">
                            @if ($role === 'gudang')
                                <li class="sub-menu-item">
                                    <a href="/gudangAdmin/add-obat">
                                        <div class="text">Tambah Obat</div>
                                    </a>
                                </li>
                            @endif
                            <li class="sub-menu-item">
                                <a href="/gudangAdmin/obat">
                                    <div class="text">Obat</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif


                {{-- admin dan fakturis bisa lihat faktur --}}
                @if ($role === 'admin')
                    <li class="menu-item">
                        <a href="/{{ $role }}/faktur" class="">
                            <div class="icon"><i class="icon-aid-kit"></i></div>
                            <div class="text">Faktur</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="/{{ $role }}/orders" class="">
                            <div class="icon"><i class="icon-aid-kit"></i></div>
                            <div class="text">Orders</div>
                        </a>
                    </li>
                    <li class="menu-item has-children">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-user"></i></div>
                            <div class="text">Data Users</div>
                        </a>
                        <ul class="sub-menu">
                            <li class="sub-menu-item">
                                <a href="/{{ $role }}/add-user" class="">
                                    <div class="text">Tambah User</div>
                                </a>
                            </li>
                            <li class="sub-menu-item">
                                <a href="/{{ $role }}/users" class="">
                                    <div class="text">Users</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- ini biarkan sebagai komentar --}}
                {{--
                <li class="menu-item">
                    <a href="orders" class="">
                        <div class="icon"><i class="icon-shopping-cart"></i></div>
                        <div class="text">Orders</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="settings.html" class="">
                        <div class="icon"><i class="icon-settings"></i></div>
                        <div class="text">Settings</div>
                    </a>
                </li> --}}
            </ul>
        </div>
    </div>
</div>
