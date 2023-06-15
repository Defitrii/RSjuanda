@extends('layouts.backend_main')
@section('title', 'Perawat')
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
                                <a href="{{ route('perawat.printPDF') }}" class="btn btn-secondary btn-sm"
                                    id="btnExportPDF">
                                    <i class="mdi mdi-file-pdf"></i> Export PDF</a>
                                <a href="{{ route('perawat.exportExcel') }}" class="btn btn-secondary btn-sm ms-1"
                                    id="btnExportExcel"> <i class="mdi mdi-file-excel"></i> Export Excel</a>
                                <button class="btn btn-secondary btn-sm ms-1" id="btnPrint">
                                    <i class="mdi mdi-printer"></i> Print
                                </button>
                            </div>
                            <div class="d-flex align-items-center mb-3 text-md-end">
                                <button type="button" class="btn btn-danger mb-2 btn-sm" id="btnHapusBanyak">
                                    <i class="mdi mdi-trash-can"></i> Hapus Banyak
                                </button>
                                <a href="{{ route('perawat.create') }}" class="btn btn-primary mb-2 btn-sm ms-1"><i
                                        class="mdi mdi-plus-circle"></i> Tambah Perawat</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th width="1px"><input type="checkbox" id="check_all"></th>
                                        <th>No</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Status</th>
                                        <th style="width: 75px;">Aksi</th>
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

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Perawat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <tr>
                            <td>NIP</td>
                            <td>:</td>
                            <td id="nip"></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td id="name"></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td id="email"></td>
                        </tr>
                        <tr>
                            <td>No Telepon</td>
                            <td>:</td>
                            <td id="no_telepon"></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td id="gender"></td>
                        </tr>
                        <tr>
                            <td>Gol Darah</td>
                            <td>:</td>
                            <td id="gol_darah"></td>
                        </tr>
                        <tr>
                            <td>Tempat, Tanggal Lahir</td>
                            <td>:</td>
                            <td id="ttl"></td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td>:</td>
                            <td id="agama"></td>
                        </tr>
                        <tr>
                            <td>Status Nikah</td>
                            <td>:</td>
                            <td id="status_nikah"></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td id="jabatan"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('body').on('click', '#btn-detail', function() {
                let id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ url('/perawat/detail/"+id+"') }}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#nip').text(response.perawat.nip);
                        $('#name').text(response.perawat.user.name);
                        $('#email').text(response.perawat.user.email);
                        $('#no_telepon').text(response.perawat.user.no_telepon);
                        $('#gender').text(response.perawat.user.gender == 'L' ? 'Laki-laki' :
                            'Perempuan');
                        $('#gol_darah').text(response.perawat.gol_darah);
                        $('#ttl').text(response.perawat.tempat_lahir + ', ' + response.perawat
                            .tanggal_lahir);
                        $('#agama').text(response.perawat.agama);
                        $('#status_nikah').text(response.perawat.status_nikah);
                        $('#jabatan').text(response.perawat.jabatan.name);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                })
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#datatable').DataTable({
                processing: true,
                serverside: true,
                ajax: "{{ route('perawat.index') }}",
                columns: [{
                    data: 'comboBox',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'nip',
                    name: 'nip'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'jabatan',
                    name: 'jabatan'
                }, {
                    data: 'status',
                    name: 'status'
                }, {
                    data: 'aksi',
                    name: 'Aksi'
                }]
            });
        });

        $('#btnPrint').on('click', function() {
            var table = $('#datatable').DataTable();
            var data = table.data().toArray();

            var printContent =
                '<table class="table"><thead><tr><th>No</th><th>NIP</th><th>Nama</th><th>Jabatan</th><th>Status</th></tr></thead><tbody>';

            $.each(data, function(index, value) {
                printContent += '<tr><td>' + (index + 1) + '</td><td>' + value.nip +
                    '</td><td>' + value.name + '</td><td>' + value.jabatan + '</td><td>' + value.status +
                    '</td></tr>';
            });

            printContent += '</tbody></table>';

            var printWindow = window.open('', '', 'height=500,width=800');
            printWindow.document.write('<html><head><title>Print Perawat</title>');
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
                        url: "{{ url('perawat/"+id+"') }}",
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

        // Ceklis Checkbox Hapus Banyak
        $('#check_all').on('click', function(e) {
            if ($(this).is(':checked', true)) {
                $(".checkbox").prop('checked', true);
            } else {
                $(".checkbox").prop('checked', false);
            }
        });

        $('#btnHapusBanyak').on('click', function(e) {
            let idArr = [];
            $(".checkbox:checked").each(function() {
                idArr.push($(this).attr('data-id'));
            });
            if (idArr.length <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silakan pilih data terlebih dahulu untuk dihapus!',
                })
            } else {
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
                    let strId = idArr.join(",");
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete-multiple-perawat') }}",
                            type: 'POST',
                            data: 'id=' + strId,
                            success: function(response) {
                                console.log(response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sukses',
                                        text: response.success,
                                    });
                                    $('#datatable').DataTable().ajax.reload();
                                    $("#check_all").prop('checked', false);
                                    $(".checkbox").prop('checked', false);
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                                    thrownError);
                            }
                        })
                    }
                })
            }
        });
    </script>
@endsection
