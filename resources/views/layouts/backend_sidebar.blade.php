<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">

    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="{{ asset('assets') }}/images/juanda_header.png" alt="" height="35">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('assets') }}/images/favicon_juanda.png" alt="" height="35">
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar="">

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title side-nav-item">Navigation</li>

            <li class="side-nav-item {{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
                <a href="{{ url('dashboard') }}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> Dashboard </span>
                </a>
            </li>
            @if (auth()->user()->type == 'Administrator')
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarMaster" aria-expanded="false"
                        aria-controls="sidebarMaster" class="side-nav-link">
                        <i class="fa-solid fa-server"></i>
                        <span> Data Master </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarMaster">
                        <ul class="side-nav-second-level">
                            <li>
                                <a href="{{ route('layanan.index') }}">Homecare</a>
                            </li>
                            <li>
                                <a href="{{ route('homecare.index') }}">Paket Homecare</a>
                            </li>
                            <li>
                                <a href="{{ route('fisioterapi.index') }}">Fisioterapi</a>
                            </li>
                            <li>
                                <a href="{{ route('bayar.index') }}">Bayar</a>
                            </li>
                            <li>
                                <a href="{{ route('kategori.index') }}">Kategori</a>
                            </li>
                            <li>
                                <a href="{{ route('poli.index') }}">Poli</a>
                            </li>
                            <li>
                                <a href="{{ route('jabatan.index') }}">Jabatan</a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarTransaksi" aria-expanded="false"
                    aria-controls="sidebarTransaksi" class="side-nav-link">
                    <i class="fa-sharp fa-solid fa-money-bill-1-wave"></i>
                    <span> Transaksi </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTransaksi">
                    <ul class="side-nav-second-level">
                        @if (auth()->user()->type != 'Dokter')
                            <li>
                                <a href="{{ route('transaksi-homecare-perawat.index') }}">Layanan Homecare</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('transaksi-homecare.index') }}">Layanan Paket Homecare</a>
                        </li>
                        <li>
                            <a href="{{ route('transaksi-fisioterapi.index') }}">Layanan Fisioterapi</a>
                        </li>
                    </ul>
                </div>
            </li>
            @if (auth()->user()->type == 'Administrator' || auth()->user()->type == 'Dokter')
                <li class="side-nav-item {{ request()->segment(1) == 'rekam-medis' ? 'active' : '' }}">
                    <a href="{{ route('rekam-medis.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-laptop-medical"></i>
                        <span> Rekam Medis </span>
                    </a>
                </li>
            @endif
            @if (auth()->user()->type != 'Perawat')
                <li class="side-nav-item {{ request()->segment(1) == 'chatpayment' ? 'active' : '' }}">
                    <a href="{{ route('chatpayment.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-comments-dollar"></i>
                        <span> Chat Payment </span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->type == 'Administrator')
                <li class="side-nav-item {{ request()->segment(1) == 'dokter' ? 'active' : '' }}">
                    <a href="{{ route('dokter.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-user-doctor"></i>
                        <span> Dokter </span>
                    </a>
                </li>
                <li class="side-nav-item {{ request()->segment(1) == 'perawat' ? 'active' : '' }}">
                    <a href="{{ route('perawat.index') }}" class="side-nav-link">
                        <i class="fa-sharp fa-solid fa-user-nurse"></i>
                        <span> Perawat </span>
                    </a>
                </li>
            @endif
            @if (auth()->user()->type == 'Administrator' || auth()->user()->type == 'Dokter')
                <li class="side-nav-item {{ request()->segment(1) == 'pasien' ? 'active' : '' }}">
                    <a href="{{ route('pasien.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-notes-medical"></i>
                        <span> Pasien </span>
                    </a>
                </li>
            @endif
            @if (auth()->user()->type == 'Administrator')
                <li class="side-nav-item {{ request()->segment(1) == 'pengguna' ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}" class="side-nav-link">
                        <i class="fa-solid fa-users"></i>
                        <span> Pengguna </span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarMultiLevel" aria-expanded="false"
                        aria-controls="sidebarMultiLevel" class="side-nav-link">
                        <i class="uil-folder-plus"></i>
                        <span> Laporan Transaksi </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarMultiLevel">
                        <ul class="side-nav-second-level">
                            <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarThirdLevelHomecare" aria-expanded="false"
                                    aria-controls="sidebarThirdLevelHomecare">
                                    <span> Homecare </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarThirdLevelHomecare">
                                    <ul class="side-nav-third-level">
                                        <ul class="side-nav-third-level">
                                            <li>
                                                <a href="{{ route('laporan-homecare.waktu') }}">Waktu</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('laporan-homecare.wilayah') }}">Wilayah</a>
                                            </li>
                                        </ul>
                                </div>
                            </li>
                            <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarThirdLevelPaket" aria-expanded="false"
                                    aria-controls="sidebarThirdLevelPaket">
                                    <span> Paket Homecare </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarThirdLevelPaket">
                                    <ul class="side-nav-third-level">
                                        <ul class="side-nav-third-level">
                                            <li>
                                                <a href="{{ route('laporan-paket-homecare.waktu') }}">Waktu</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('laporan-paket-homecare.wilayah') }}">Wilayah</a>
                                            </li>
                                        </ul>
                                </div>
                            </li>
                            <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarThirdLevelFisioterapi"
                                    aria-expanded="false" aria-controls="sidebarThirdLevelFisioterapi">
                                    <span> Fisioterapi </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarThirdLevelFisioterapi">
                                    <ul class="side-nav-third-level">
                                        <li>
                                            <a href="{{ route('laporan-fisioterapi.waktu') }}">Waktu</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('laporan-fisioterapi.wilayah') }}">Wilayah</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            <li class="side-nav-item {{ request()->segment(1) == 'ganti-password' ? 'active' : '' }}">
                <a href="{{ route('ganti-password.index') }}" class="side-nav-link">
                    <i class="fa-solid fa-lock"></i>
                    <span> Ganti Password </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('logout') }}" class="side-nav-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span> Keluar </span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
