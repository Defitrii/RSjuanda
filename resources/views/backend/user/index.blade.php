@extends('layouts.backend_main')
@section('title', 'Pengguna')
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
                                <a href="{{ route('pengguna.printPDF') }}" class="btn btn-secondary btn-sm"
                                    id="btnExportPDF">
                                    <i class="mdi mdi-file-pdf"></i> Export PDF</a>
                                <a href="{{ route('pengguna.exportExcel') }}" class="btn btn-secondary btn-sm ms-1"
                                    id="btnExportExcel"> <i class="mdi mdi-file-excel"></i> Export Excel</a>
                                <button class="btn btn-secondary btn-sm ms-1" id="btnPrint">
                                    <i class="mdi mdi-printer"></i> Print
                                </button>
                            </div>
                            <div class="d-flex align-items-center mb-3 text-md-end">
                                <button type="button" class="btn btn-danger mb-2 btn-sm" id="btnHapusBanyak">
                                    <i class="mdi mdi-trash-can"></i> Hapus Banyak
                                </button>
                                <a href="{{ route('user.create') }}" class="btn btn-primary mb-2 btn-sm ms-1"><i
                                        class="mdi mdi-plus-circle"></i> Tambah Data</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th width="1px"><input type="checkbox" id="check_all"></th>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No Telepon</th>
                                        <th>Alamat</th>
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

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#datatable').DataTable({
                processing: true,
                serverside: true,
                ajax: "{{ route('user.index') }}",
                columns: [{
                        data: 'comboBox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'Nama'
                    }, {
                        data: 'email',
                        name: 'Email'
                    }, {
                        data: 'no_telepon',
                        name: 'No Telepon'
                    }, {
                        data: 'address',
                        name: 'Alamat'
                    }, {
                        data: 'aksi',
                        name: 'Aksi'
                    }
                ]
            });
        });

        $('#btnPrint').on('click', function() {
            var table = $('#datatable').DataTable();
            var data = table.data().toArray();

            var printContent =
                '<table class="table"><thead><tr><th>No</th><th>Nama</th><th>Email</th><th>No Telepon</th><th>Alamat</th></tr></thead><tbody>';

            $.each(data, function(index, value) {
                printContent += '<tr><td>' + (index + 1) + '</td><td>' + value.name +
                    '</td><td>' + value.email + '</td><td>' + value.no_telepon + '</td><td>' + value
                    .address +
                    '</td></tr>';
            });

            printContent += '</tbody></table>';

            var printWindow = window.open('', '', 'height=500,width=800');
            printWindow.document.write('<html><head><title>Print Pengguna</title>');
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
                        url: "{{ url('pengguna/"+id+"') }}",
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
                            url: "{{ route('delete-multiple-user') }}",
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
