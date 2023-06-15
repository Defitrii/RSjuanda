@extends('layouts.backend_main')

@section('title', 'Layanan')

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
                                        <a href="{{ route('layanan.printPDF') }}" class="btn btn-secondary btn-sm"
                                            id="btnExportPDF"> <i class="mdi mdi-file-pdf"></i> Export PDF</a>
                                        <a href="{{ route('layanan.exportExcel') }}" class="btn btn-secondary btn-sm ms-1"
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
                                            <th>Kode Layanan</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
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

    <!-- layanan modal -->
    <div id="layananModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="layananModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="layananModalLabel"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" name="id" id="id">
                            <label for="name" class="form-label">Layanan</label>
                            <input type="text" id="name" name="name" class="form-control">
                            <div class="invalid-feedback errorName">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Dekskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="1" class="form-control"></textarea>
                            <div class="invalid-feedback errorDeskripsi">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" id="harga" name="harga" class="form-control">
                            <div class="invalid-feedback errorHarga">
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

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td id="name_detail"></td>
                        </tr>
                        <tr>
                            <td>Deskripsi</td>
                            <td>:</td>
                            <td id="deskripsi_detail"></td>
                        </tr>
                        <tr>
                            <td>Harga</td>
                            <td>:</td>
                            <td id="harga_detail"></td>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('body').on('click', '#btn-detail', function() {
                let id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ url('/layanan/detail/"+id+"') }}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#name_detail').text(response.layanan.name);
                        $('#deskripsi_detail').text(response.layanan.deskripsi);
                        $('#harga_detail').text(response.layanan.harga);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                })
            });

            $('#datatable').DataTable({
                processing: true,
                serverside: true,
                ajax: "{{ url('layanan') }}",
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
                        data: 'kode_layanan',
                        name: 'kode_layanan'
                    },
                    {
                        data: 'name',
                        name: 'Nama'
                    },
                    {
                        data: 'harga',
                        name: 'harga'
                    },
                    {
                        data: 'aksi',
                        name: 'Aksi'
                    }
                ]
            });

            // Tombol print
            $('#btnPrint').on('click', function() {
                var table = $('#datatable').DataTable();
                var data = table.data().toArray();

                var printContent =
                    '<table class="table"><thead><tr><th>No</th><th>Kode Layanan</th><th>Nama Layanan</th><th>Harga</th></tr></thead><tbody>';

                $.each(data, function(index, value) {
                    printContent += '<tr><td>' + (index + 1) + '</td><td>' + value.kode_layanan +
                        '</td><td>' + value.name + '</td><td>' + value.harga + '</td></tr>';
                });

                printContent += '</tbody></table>';

                var printWindow = window.open('', '', 'height=500,width=800');
                printWindow.document.write('<html><head><title>Print Layanan</title>');
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
                $('#layananModalLabel').html("Tambah Layanan");
                $('#layananModal').modal('show');
                $('#form').trigger("reset");

                $('#name').removeClass('is-invalid');
                $('.errorName').html('');

                $('#deskripsi').removeClass('is-invalid');
                $('.errorDeskripsi').html('');

                $('#harga').removeClass('is-invalid');
                $('.errorHarga').html('');
            });

            // Edit Data
            $('body').on('click', '#btnEdit', function() {
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    url: "layanan/" + id + "/edit",
                    dataType: "json",
                    success: function(response) {
                        $('#layananModalLabel').html("Edit Layanan");
                        $('#simpan').val("edit-layanan");
                        $('#layananModal').modal('show');

                        $('#name').removeClass('is-invalid');
                        $('.errorName').html('');

                        $('#deskripsi').removeClass('is-invalid');
                        $('.errorDeskripsi').html('');

                        $('#harga').removeClass('is-invalid');
                        $('.errorHarga').html('');

                        $('#id').val(response.id);
                        $('#name').val(response.name);
                        $('#deskripsi').val(response.deskripsi);
                        $('#harga').val(response.harga);
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
                            url: "{{ url('layanan/"+id+"') }}",
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
                    url: "{{ route('layanan.store') }}",
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

                            if (response.errors.deskripsi) {
                                $('#deskripsi').addClass('is-invalid');
                                $('.errorDeskripsi').html(response.errors.deskripsi);
                            } else {
                                $('#deskripsi').removeClass('is-invalid');
                                $('.errorDeskripsi').html('');
                            }

                            if (response.errors.harga) {
                                $('#harga').addClass('is-invalid');
                                $('.errorHarga').html(response.errors.harga);
                            } else {
                                $('#harga').removeClass('is-invalid');
                                $('.errorHarga').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            })
                            $('#layananModal').modal('hide');
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
                                url: "{{ route('delete-multiple-layanan') }}",
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
