<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title') | Home Care - Rumah Sakit Juanda</title>
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon_juanda.png">

    <!-- App css -->
    <link href="{{ asset('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets') }}/css/app.min.css" rel="stylesheet" type="text/css" id="light-style">
    <link href="{{ asset('assets') }}/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- Datatables css -->
    <link href="{{ asset('assets') }}/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets') }}/css/vendor/responsive.bootstrap5.css" rel="stylesheet" type="text/css" />

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="loading"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <!-- Begin page -->
    <div class="wrapper">
        @include('layouts.backend_sidebar')

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                @include('layouts.backend_topbar')
                <!-- end Topbar -->

                <!-- Start Content-->
                @yield('content')
                <!-- container -->
                <!-- content -->
            </div>

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            &copy;
                            <script>
                                document.write(new Date().getFullYear())
                            </script> Hak Cipta Dilindungi | Defitri Nur Erdila.
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    <!-- bundle -->
    <script src="{{ asset('assets') }}/js/vendor.min.js"></script>
    <script src="{{ asset('assets') }}/js/app.min.js"></script>

    <!-- Datatables js -->
    <script src="{{ asset('assets') }}/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets') }}/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/js/vendor/dataTables.responsive.min.js"></script>
    <script src="{{ asset('assets') }}/js/vendor/responsive.bootstrap5.min.js"></script>
</body>

</html>
