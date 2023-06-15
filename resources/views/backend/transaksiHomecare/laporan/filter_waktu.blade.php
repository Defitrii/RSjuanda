@extends('layouts.backend_main')
@section('title', 'Laporan Paket Homecare')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">@yield('title')</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ old('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ old('end_date') }}">
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2">
                                <button type="button" class="btn btn-secondary mb-2" id="refresh">Refresh</button>
                                <button type="button" class="btn btn-primary mb-2" id="filter">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-md-flex justify-content-between">
                            <div class="d-flex align-items-center mb-3">
                                <button type="button" class="btn btn-secondary btn-sm" id="btnExportPDF"> <i
                                        class="mdi mdi-file-pdf"></i> Export PDF</button>
                                <button class="btn btn-secondary btn-sm ms-1" id="btnExportExcel"> <i
                                        class="mdi mdi-file-excel"></i> Export Excel</button>
                                <button class="btn btn-secondary btn-sm ms-1" id="btnPrint">
                                    <i class="mdi mdi-printer"></i> Print
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pasien</th>
                                        <th>Perawat</th>
                                        <th>Dokter</th>
                                        <th>Homecare</th>
                                        <th>Waktu</th>
                                        <th>Total Biaya</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->

    </div> <!-- content -->

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            load_data();

            function load_data(start_date = '', end_date = '') {
                $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('laporan-paket-homecare.waktu') }}',
                        data: {
                            start_date: start_date,
                            end_date: end_date
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'pasien',
                            name: 'pasien'
                        },
                        {
                            data: 'perawat',
                            name: 'perawat'
                        },
                        {
                            data: 'dokter',
                            name: 'dokter'
                        },
                        {
                            data: 'layanan',
                            name: 'layanan'
                        },
                        {
                            data: 'waktu',
                            name: 'waktu'
                        },
                        {
                            data: 'total_biaya',
                            name: 'total_biaya'
                        },
                    ]
                });
            }

            $('#filter').click(function() {
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                if (start_date != '' && end_date != '') {
                    $('#datatable').DataTable().destroy();
                    load_data(start_date, end_date);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Silakan isi data terlebih dahulu!',
                    })
                }

            });

            $('#refresh').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#datatable').DataTable().destroy();
                load_data();
            });

            $('#btnPrint').on('click', function() {
                var table = $('#datatable').DataTable();
                var data = table.data().toArray();

                var printContent =
                    '<table class="table"><thead><tr><th>No</th><th>Pasien</th><th>Perawat</th><th>Dokter</th><th>Homecare</th><th>Pembayaran</th><th>Total Biaya</th></tr></thead><tbody>';

                $.each(data, function(index, value) {
                    printContent += '<tr><td>' + (index + 1) + '</td><td>' + value
                        .pasien +
                        '</td><td>' + value.perawat + '</td><td>' + value.dokter +
                        '</td><td>' + value.layanan +
                        '</td><td>' + value.metode_pembayaran +
                        '</td><td>' + value.total_biaya +
                        '</td></tr>';
                });

                printContent += '</tbody></table>';

                var printWindow = window.open('', '', 'height=500,width=800');
                printWindow.document.write('<html><head><title>Print Laporan Paket Homecare</title>');
                printWindow.document.write(
                    '<style>body{font-family: Arial, sans-serif;font-size: 14px;}table {width: 100%;border-collapse: collapse;}td,th {padding: 5px;border: 1px solid #ddd;}th {background - color: #f2f2f2;text - align: left;}h2 {font - size: 18 px;margin - top: 0;}.text - bold {font - weight: bold;}.text - center {text - align: center;}.text - right {text - align: right;}.mb - 10 {margin - bottom: 10 px;}</style>'
                );
                printWindow.document.write('</head><body>');
                printWindow.document.write(printContent);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            });

            $('#btnExportPDF').on('click', function() {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                $.ajax({
                    url: '{{ route('laporan-paket-homecare.printPDF') }}',
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        //lakukan sesuatu setelah menerima respon dari server
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        //lakukan sesuatu jika terjadi kesalahan
                        console.log(error);
                    }
                });
            });

            $('#btnExportExcel').click(function() {
                // mengambil tanggal awal dan tanggal akhir dari form
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                // mengirim request ke server dengan menggunakan AJAX
                $.ajax({
                    url: '{{ route('laporan-paket-homecare.exportExcel') }}',
                    method: 'POST',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response, status, xhr) {
                        var contentType = xhr.getResponseHeader('content-type');

                        // membuat URL dari response blob
                        var blobUrl = URL.createObjectURL(response);

                        // membuat link download dengan menggunakan URL blob
                        var downloadLink = document.createElement('a');
                        downloadLink.href = blobUrl;
                        downloadLink.download = 'laporan-transaksi-paket-homecare.xlsx';
                        downloadLink.style.display = 'none';
                        document.body.appendChild(downloadLink);

                        // mengklik link download secara otomatis
                        downloadLink.click();

                        // menghapus link download dari halaman
                        document.body.removeChild(downloadLink);

                        // menghapus URL blob dari browser
                        URL.revokeObjectURL(blobUrl);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
@endsection
