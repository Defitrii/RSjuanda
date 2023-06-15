<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="{{ csrf_token() }}" x="csrf-token">
    <title>@yield('title') | Home Care - Rumah Sakit Juanda</title>
    <!-- Favicon -->
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon_juanda.png">
    <!-- CSS -->
    <link href="{{ asset('assets') }}/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/plugins/owl-carousel/owl.carousel.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/plugins/owl-carousel/owl.theme.default.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/plugins/magnific-popup/magnific-popup.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/plugins/sal/sal.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/css/theme.css" rel="stylesheet">
    <!-- Fonts/Icons -->
    <link href="{{ asset('assets') }}/plugins/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/plugins/font-awesome/css/all.css" rel="stylesheet">
</head>

<body data-preloader="1">

    @include('layouts.frontend_topbar')

    @yield('content')

    <footer>
        <div class="section-sm bg-dark">
            <div class="container">
                <div class="row g-3">
                    <div class="col-6 col-sm-6 col-lg-3">
                        <img class="logo-light" src="{{ asset('assets') }}/images/juanda_header.png" alt=""
                            style="width: 70%;">
                    </div>
                    <div class="col-6 col-sm-6 col-lg-3">
                        <h6 class="font-small fw-normal uppercase">Navigasi Link</h6>
                        <ul class="list-dash">
                            <li><a href="{{ route('beranda') }}">Beranda</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-sm-6 col-lg-3">
                        <h6 class="font-small fw-normal uppercase">Layanan</h6>
                        <ul class="list-dash">
                            <li><a href="{{ route('frontend.homecare') }}">Homecare</a></li>
                            <li><a href="{{ route('frontend.paket-homecare') }}">Paket Homecare</a></li>
                            <li><a href="{{ route('frontend.fisioterapi') }}">Fisioterapi</a></li>
                            <li><a href="{{ route('frontend.telemedicine') }}">Telemedicine</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-sm-6 col-lg-3">
                        <h6 class="font-small fw-normal uppercase">Kontak Kami</h6>
                        <ul class="list-unstyled">
                            <li><i class="fa-solid fa-location-dot margin-right-10"></i>Jln. Ir. H. Juanda no 207
                                Kuningan, Jawa Barat
                            </li>
                            <li><i class="fa-solid fa-phone-alt margin-right-10"></i>(0232) 876 433</li>
                            <li><i class="fa-solid fa-envelope margin-right-10"></i>rs.juanda@gmail.com</li>
                        </ul>
                    </div>
                </div><!-- end row(1) -->

                <hr class="margin-top-30 margin-bottom-30">

                <div class="row g-2">
                    <div class="col-12 col-md-6 text-center text-md-start">
                        <p>&copy; 2023 Hak Cipta Dilindungi | Defitri Nur Erdila.</p>
                    </div>
                    <div class="col-12 col-md-6 text-center text-md-end">
                        <ul class="list-inline">
                            <li><a href="#"><i class="bi bi-facebook"></i></a></li>
                            <li><a href="#"><i class="bi bi-instagram"></i></a></li>
                            <li><a href="#"><i class="bi bi-youtube"></i></a></li>
                            <li><a href="#"><i class="bi bi-whatsapp"></i></a></li>
                        </ul>
                    </div>
                </div><!-- end row(2) -->
            </div><!-- end container -->
        </div>
    </footer>

    <!-- Scroll to top button -->
    <div class="scrolltotop">
        <a class="button-circle button-circle-sm button-circle-dark" href="#"><i class="bi bi-arrow-up"></i></a>
    </div>
    <!-- end Scroll to top button -->

    <!-- ***** JAVASCRIPTS ***** -->
    <script src="{{ asset('assets') }}/plugins/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver"></script>
    <script src="{{ asset('assets') }}/plugins/plugins.js"></script>
    <script src="{{ asset('assets') }}/js/functions.js"></script>
</body>

</html>
