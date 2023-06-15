@extends('layouts.backend_main')
@section('title', 'Chat Payment')
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
                        <div class="d-md-flex justify-content-between">
                            <div class="d-flex align-items-center mb-3">
                                @if (auth()->user()->type != 'Pasien' && auth()->user()->type != 'Perawat')
                                    <a href="{{ route('chatpayment.printPDF') }}" class="btn btn-secondary btn-sm"
                                        id="btnExportPDF">
                                        <i class="mdi mdi-file-pdf"></i> Export PDF</a>
                                    <a href="{{ route('chatpayment.exportExcel') }}" class="btn btn-secondary btn-sm ms-1"
                                        id="btnExportExcel"> <i class="mdi mdi-file-excel"></i> Export Excel</a>
                                    <button class="btn btn-secondary btn-sm ms-1" id="btnPrint">
                                        <i class="mdi mdi-printer"></i> Print
                                    </button>
                                @endif
                            </div>
                            <div class="d-flex align-items-center mb-3 text-md-end">
                                <a href="{{ route('chatpayment.create') }}" class="btn btn-primary mb-2 btn-sm"><i
                                        class="mdi mdi-plus-circle"></i> Tambah Data</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pasien</th>
                                        <th>Dokter</th>
                                        <th>No Telepon</th>
                                        <th>Waktu Selesai</th>
                                        <th>Biaya Chat</th>
                                        <th>Waktu</th>
                                        <th>Aksi</th>
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

            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('chatpayment.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'pasien',
                        name: 'pasien'
                    },
                    {
                        data: 'dokter',
                        name: 'dokter'
                    },
                    {
                        data: 'no_telepon',
                        name: 'no_telepon'
                    },
                    {
                        data: 'waktu_selesai',
                        name: 'waktu selesai'
                    },
                    {
                        data: 'biaya_chat',
                        name: 'biaya chat'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        });

        $('#btnPrint').on('click', function() {
            var table = $('#datatable').DataTable();
            var data = table.data().toArray();

            var printContent =
                '<table class="table"><thead><tr><th>No</th><th>Pasien</th><th>Dokter</th><th>No Telepon</th><th>Waktu Selesai</th><th>Biaya Chat</th><th>Waktu</th></tr></thead><tbody>';

            $.each(data, function(index, value) {
                printContent += '<tr><td>' + (index + 1) + '</td><td>' + value.pasien +
                    '</td><td>' + value.dokter + '</td><td>' + value.no_telepon + '</td><td>' + value
                    .waktu_selesai +
                    '</td><td>' + value.biaya_chat + '</td><td>' + value.waktu + '</td></tr>';
            });

            printContent += '</tbody></table>';

            var printWindow = window.open('', '', 'height=500,width=800');
            printWindow.document.write('<html><head><title>Print Chatpayment</title>');
            printWindow.document.write(
                '<style>body{font-family: Arial, sans-serif;font-size: 14px;}table {width: 100%;border-collapse: collapse;}td,th {padding: 5px;border: 1px solid #ddd;}th {background - color: #f2f2f2;text - align: left;}h2 {font - size: 18 px;margin - top: 0;}.text - bold {font - weight: bold;}.text - center {text - align: center;}.text - right {text - align: right;}.mb - 10 {margin - bottom: 10 px;}</style>'
            );
            printWindow.document.write('</head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });

        // Hapus Data
        $('body').on('click', '#btnHapus', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Hapus',
                text: "Apakah anda yakin?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('chatpayment/"+id+"') }}",
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: response.success,
                                });
                                $('#datatable').DataTable().ajax.reload()
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                                thrownError);
                        }
                    })
                }
            })
        })
    </script>
@endsection
