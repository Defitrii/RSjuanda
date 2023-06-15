@extends('layouts.backend_main')
@section('title', 'Laporan Homecare')
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
                            <div class="col-lg-3 col-md-12">
                                <div class="mb-3">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <select class="form-control select2" data-toggle="select2" name="provinsi"
                                        id="provinsi">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach ($provinces as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback errorProvinsi"></div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="mb-3">
                                    <label for="kabupaten" class="form-label">Kabupaten</label>
                                    <select class="form-control select2" data-toggle="select2" name="kabupaten"
                                        id="kabupaten">
                                        <option value="">-- Pilih Kabupaten --</option>
                                    </select>
                                    <div class="invalid-feedback errorKabupaten"></div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="mb-3">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <select class="form-control select2" data-toggle="select2" name="kecamatan"
                                        id="kecamatan">
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                    <div class="invalid-feedback errorKecamatan"></div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="mb-3">
                                    <label for="desa" class="form-label">Desa</label>
                                    <select class="form-control select2" data-toggle="select2" name="desa"
                                        id="desa">
                                        <option value="">-- Pilih Desa --</option>
                                    </select>
                                    <div class="invalid-feedback errorDesa"></div>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2">
                                <button type="button" class="btn btn-secondary mb-2" id="refresh">Refresh</button>
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
                                        <th>Jarak (KM)</th>
                                        <th>Waktu</th>
                                        <th>Pembayaran</th>
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

            var provinsiAwal = $('#provinsi').html();
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('laporan-homecare.wilayah') }}',
                    data: function(d) {
                        d.provinsi = $('#provinsi').val();
                        d.kabupaten = $('#kabupaten').val();
                        d.kecamatan = $('#kecamatan').val();
                        d.desa = $('#desa').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'pasien',
                        name: 'pasien'
                    },
                    {
                        data: 'perawat',
                        name: 'perawat'
                    },
                    {
                        data: 'jarak',
                        name: 'jarak'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'metode_pembayaran',
                        name: 'metode_pembayaran'
                    },
                    {
                        data: 'total_biaya',
                        name: 'total_biaya'
                    },
                ]
            });

            $('#provinsi').on('change', function() {
                $('#kabupaten').empty().append('<option value="">-- Pilih Kabupaten --</option>');
                $('#kecamatan').empty().append('<option value="">-- Pilih Kecamatan --</option>');
                $('#desa').empty().append('<option value="">-- Pilih Desa --</option>');
                table.ajax.reload();
            });

            $('#kabupaten').on('change', function() {
                $('#kecamatan').empty().append('<option value="">-- Pilih Kecamatan --</option>');
                $('#desa').empty().append('<option value="">-- Pilih Desa --</option>');
                table.ajax.reload();
            });

            $('#kecamatan').on('change', function() {
                $('#desa').empty().append('<option value="">-- Pilih Desa --</option>');
                table.ajax.reload();
            });

            $('#desa').on('change', function() {
                table.ajax.reload();
            });

            $('#refresh').on('click', function() {
                $('#provinsi').html(provinsiAwal);
                $('#kabupaten').empty().append('<option value="">-- Pilih Kabupaten --</option>');
                $('#kecamatan').empty().append('<option value="">-- Pilih Kecamatan --</option>');
                $('#desa').empty().append('<option value="">-- Pilih Desa --</option>');
                table.ajax.reload();
            });

            $('#btnPrint').on('click', function() {
                var table = $('#datatable').DataTable();
                var data = table.data().toArray();

                var printContent =
                    '<table class="table"><thead><tr><th>No</th><th>Pasien</th><th>Perawat</th><th>Jarak (KM)</th><th>Waktu</th><th>Pembayaran</th><th>Total Biaya</th></tr></thead><tbody>';

                $.each(data, function(index, value) {
                    printContent += '<tr><td>' + (index + 1) + '</td><td>' + value
                        .pasien +
                        '</td><td>' + value.perawat + '</td><td>' + value.jarak +
                        '</td><td>' + value.waktu +
                        '</td><td>' + value.metode_pembayaran +
                        '</td><td>' + value.total_biaya +
                        '</td></tr>';
                });

                printContent += '</tbody></table>';

                var printWindow = window.open('', '', 'height=500,width=800');
                printWindow.document.write('<html><head><title>Print Laporan Homecare</title>');
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
                var provinsi = $('#provinsi').val();
                var kabupaten = $('#kabupaten').val();
                var kecamatan = $('#kecamatan').val();
                var desa = $('#desa').val();

                $.ajax({
                    url: '{{ route('laporan-homecare.printPDFWilayah') }}',
                    type: 'GET',
                    data: {
                        provinsi: provinsi,
                        kabupaten: kabupaten,
                        kecamatan: kecamatan,
                        desa: desa,
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
                // mengirim request ke server dengan menggunakan AJAX
                var provinsi = $('#provinsi').val();
                var kabupaten = $('#kabupaten').val();
                var kecamatan = $('#kecamatan').val();
                var desa = $('#desa').val();

                $.ajax({
                    url: '{{ route('laporan-homecare.exportExcelWilayah') }}',
                    method: 'POST',
                    data: {
                        provinsi: provinsi,
                        kabupaten: kabupaten,
                        kecamatan: kecamatan,
                        desa: desa,
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
                        downloadLink.download = 'laporan-transaksi-homecare.xlsx';
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

            $('#provinsi').on('change', function() {
                let id_provinsi = $('#provinsi').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('laporan-homecare.get-kabupaten') }}",
                    data: {
                        id_provinsi: id_provinsi
                    },
                    success: function(response) {
                        $('#kabupaten').html(response);
                        $('#kecamatan').html('');
                        $('#desa').html('');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });

            $('#kabupaten').on('change', function() {
                let id_kabupaten = $('#kabupaten').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('laporan-homecare.get-kecamatan') }}",
                    data: {
                        id_kabupaten: id_kabupaten
                    },
                    success: function(response) {
                        $('#kecamatan').html(response);
                        $('#desa').html('');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });

            $('#kecamatan').on('change', function() {
                let id_kecamatan = $('#kecamatan').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('laporan-homecare.get-desa') }}",
                    data: {
                        id_kecamatan: id_kecamatan
                    },
                    success: function(response) {
                        $('#desa').html(response);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });

        });
    </script>
@endsection
