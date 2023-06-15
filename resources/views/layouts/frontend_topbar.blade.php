<!-- Header -->
<div class="header right dark sticky-autohide" style="background-color: #033b22;">
    <div class="container">
        <!-- Logo -->
        <div class="header-logo">
            <!-- <h3><a href="#">mono</a></h3> -->
            <!-- <img class="logo-dark" src="{{ asset('assets') }}/images/your-logo-dark.png" alt=""> -->
            <img class="logo-light" src="{{ asset('assets') }}/images/juanda_header.png" alt=""
                style="height: 100px;">
        </div>
        <!-- Menu -->
        <div class="header-menu">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('beranda') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.homecare') }}">Homecare</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.paket-homecare') }}">Paket Homecare</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.fisioterapi') }}">Fisioterapi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('frontend.telemedicine') }}">Telemedicine</a>
                </li>
                <li class="nav-item">
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="#">Hi, {{ Auth::user()->name }}</a>
                        <ul class="nav-dropdown">
                            <li class="nav-dropdown-item"><a class="nav-dropdown-link"
                                    href="{{ route('dashboard.index') }}">Dashboard</a>
                            </li>
                            <li class="nav-dropdown-item"><a class="nav-dropdown-link" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <a class="button button-sm button-fancy-1-outline-white margin-right-10 margin-bottom-10"
                        href="{{ route('login') }}">Login</a>
                @endauth
                </li>
            </ul>
        </div>
        <!-- Menu Toggle -->
        <button class="header-toggle">
            <span></span>
        </button>
    </div><!-- end container -->
</div>
<!-- end Header -->
