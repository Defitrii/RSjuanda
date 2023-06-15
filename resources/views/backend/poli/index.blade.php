@extends('layouts.backend_main')

@section('title', 'Poli')

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">@yield('title')</h4>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active" id="basic-preview">
                                <div class="d-md-flex justify-content-between">
                                    <div class="d-flex align-items-center mb-3">
                                        <a href="{{ route('poli.printPDF') }}" class="btn btn-secondary btn-sm"
                                            id="btnExportPDF"> <i class="mdi mdi-file-pdf"></i> Export PDF</a>
                                        <a href="{{ route('poli.exportExcel') }}" class="btn btn-secondary btn-sm ms-1"
                                            id="btnExportExcel"> <i class="mdi mdi-file-excel"></i> Export Excel</a>
                                        <button class="btn btn-secondary btn-sm ms-1" id="btnPrint">
                                            <i class="mdi mdi-printer"></i> Print
                                        </button>
                                    </div>
                                    <div class="d-flex align-items-center mb-3 text-md-end">
                                        <button type="button" class="btn btn-danger btn-sm" id="btnHapusBanyak">
                                            <i class="mdi mdi-trash-can"></i> Hapus Banyak
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm ms-md-1" id="btnTambah">
                                            <i class="mdi mdi-plus"></i> Tambah Data
                                        </button>
                                    </div>
                                </div>
                                <table id="datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th width="1px"><input type="checkbox" id="check_all"></th>
                                            <th>#</th>
                                            <th>Kode Poli</th>
                                            <th>Nama</th>
                                            <th width="10">Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div> <!-- end preview-->
                        </div> <!-- end tab-content-->
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div>
        </div>
        <!-- end page title -->
    </div>

    <!-- poli modal -->
    <div id="poliModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="poliModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="poliModalLabel"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" name="id" id="id">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" id="name" name="name" class="form-control">
                            <div class="invalid-feedback errorName">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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
                ajax: "{{ url('poli') }}",
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
                        data: 'kode_poli',
                        name: 'kode_poli'
                    },
                    {
                        data: 'name',
                        name: 'Nama'
                    },
                    {
                        data: 'aksi',
                        name: 'Aksi'
                    }
                ]
            });

            $('#btnPrint').on('click', function() {
                var table = $('#datatable').DataTable();
                var data = table.data().toArray();

                var printContent =
                    '<table class="table"><thead><tr><th>No</th><th>Kode Poli</th><th>Nama</th></tr></thead><tbody>';

                $.each(data, function(index, value) {
                    printContent += '<tr><td>' + (index + 1) + '</td><td>' + value.kode_poli +
                        '</td><td>' + value.name + '</td></tr>';
                });

                printContent += '</tbody></table>';

                var printWindow = window.open('', '', 'height=500,width=800');
                printWindow.document.write('<html><head><title>Print Poli</title>');
                printWindow.document.write(
                    '<style>body{font-family: Arial, sans-serif;font-size: 14px;}table {width: 100%;border-collapse: collapse;}td,th {padding: 5px;border: 1px solid #ddd;}th {background - color: #f2f2f2;text - align: left;}h2 {font - size: 18 px;margin - top: 0;}.text - bold {font - weight: bold;}.text - center {text - align: center;}.text - right {text - align: right;}.mb - 10 {margin - bottom: 10 px;}</style>'
                );
                printWindow.document.write('</head><body>');
                printWindow.document.write(printContent);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            });

            // Tambah Data
            $('#btnTambah').click(function() {
                $('#id').val('');
                $('#poliModalLabel').html("Tambah Data");
                $('#poliModal').modal('show');
                $('#form').trigger("reset");

                $('#name').removeClass('is-invalid');
                $('.errorName').html('');
            });

            // Edit Data
            $('body').on('click', '#btnEdit', function() {
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    url: "poli/" + id + "/edit",
                    dataType: "json",
                    success: function(response) {
                        $('#poliModalLabel').html("Edit Data");
                        $('#simpan').val("edit-poli");
                        $('#poliModal').modal('show');

                        $('#name').removeClass('is-invalid');
                        $('.errorName').html('');

                        $('#id').val(response.id);
                        $('#name').val(response.name);
                    }
                });
            })

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
                            url: "{{ url('poli/"+id+"') }}",
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

            // Proses tambah & edit
            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('poli.store') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function() {
                        $('#simpan').attr('disable', 'disabled');
                        $('#simpan').text('Proses...');
                    },
                    complete: function() {
                        $('#simpan').removeAttr('disable');
                        $('#simpan').html('Simpan');
                    },
                    success: function(response) {
                        if (response.errors) {
                            if (response.errors.name) {
                                $('#name').addClass('is-invalid');
                                $('.errorName').html(response.errors.name);
                            } else {
                                $('#name').removeClass('is-invalid');
                                $('.errorName').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            })
                            $('#poliModal').modal('hide');
                            $('#form').trigger("reset");
                            $('#datatable').DataTable().ajax.reload()
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });

            // Ceklis Checkbox Hapus Banyak
            $('#check_all').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    $(".checkbox").prop('checked', true);
                } else {
                    $(".checkbox").prop('checked', false);
                }
            });

            // Hapus Data Banyak
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
                                url: "{{ route('delete-multiple-poli') }}",
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
        });
    </script>
@endsection
