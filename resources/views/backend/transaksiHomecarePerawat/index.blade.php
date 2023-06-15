@extends('layouts.backend_main')
@section('title', 'Transaksi Homecare')
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
                                @if (auth()->user()->type != 'Pasien')
                                    <a href="{{ route('transaksi-homecare-perawat.printPDF') }}"
                                        class="btn btn-secondary btn-sm" id="btnExportPDF"> <i class="mdi mdi-file-pdf"></i>
                                        Export PDF</a>
                                    <a href="{{ route('transaksi-homecare-perawat.exportExcel') }}"
                                        class="btn btn-secondary btn-sm ms-1" id="btnExportExcel"> <i
                                            class="mdi mdi-file-excel"></i> Export Excel</a>
                                    <button class="btn btn-secondary btn-sm ms-1" id="btnPrint">
                                        <i class="mdi mdi-printer"></i> Print
                                    </button>
                                @endif
                            </div>
                            <div class="d-flex align-items-center mb-3 text-md-end">
                                @if (auth()->user()->type != 'Pasien')
                                    <button type="button" class="btn btn-danger mb-2 btn-sm" id="btnHapusBanyak">
                                        <i class="mdi mdi-trash-can"></i> Hapus Banyak
                                    </button>
                                @endif
                                <a href="{{ route('transaksi-homecare-perawat.create') }}"
                                    class="btn btn-primary mb-2 btn-sm ms-1"><i class="mdi mdi-plus-circle"></i> Tambah
                                    data</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th width="1px"><input type="checkbox" id="check_all"></th>
                                        <th>No</th>
                                        <th>Pasien</th>
                                        <th>Perawat</th>
                                        <th>Pembayaran</th>
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
                    <h5 class="modal-title" id="detailModalLabel">Detail Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <tr>
                            <th colspan="3">Detail Pasien</th>
                        </tr>
                        <tr>
                            <td>Nama Pasien</td>
                            <td>:</td>
                            <td id="name_pasien"></td>
                        </tr>
                        <tr>
                            <td>No Telepon</td>
                            <td>:</td>
                            <td id="no_telepon_pasien"></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td id="jenis_kelamin_pasien"></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td id="alamat_pasien"></td>
                        </tr>
                        <tr>
                            <th colspan="3">Detail Perawat</th>
                        </tr>
                        <tr>
                            <td>Nama Perawat</td>
                            <td>:</td>
                            <td id="name_perawat"></td>
                        </tr>
                        <tr>
                            <td>No Telepon</td>
                            <td>:</td>
                            <td id="no_telepon_perawat"></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td id="jenis_kelamin_perawat"></td>
                        </tr>
                        <tr>
                            <th colspan="3">Detail Transaksi</th>
                        </tr>
                        <tr>
                            <td>Layanan</td>
                            <td>:</td>
                            <td id="layanan"></td>
                        </tr>
                        <tr>
                            <td>Waktu</td>
                            <td>:</td>
                            <td id="waktu"></td>
                        </tr>
                        <tr>
                            <td>Jarak</td>
                            <td>:</td>
                            <td id="jarak"></td>
                        </tr>
                        <tr>
                            <td>Metode Pembayaran</td>
                            <td>:</td>
                            <td id="metode_pembayaran"></td>
                        </tr>
                        <tr>
                            <td>Biaya Tambahan</td>
                            <td>:</td>
                            <td id="biaya_tambahan"></td>
                        </tr>
                        <tr>
                            <td>Total Biaya</td>
                            <td>:</td>
                            <td id="total_biaya"></td>
                        </tr>
                        <tr>
                            <td>Bukti Pembayaran</td>
                            <td>:</td>
                            <td>
                                <a href="" id="link_bukti_pembayaran" target="_blank">
                                    <img id="bukti_pembayaran" src="" style="width: 100px;">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3">Detail Kegiatan</th>
                        </tr>
                        <tr>
                            <td>Deskripsi Kegiatan</td>
                            <td>:</td>
                            <td id="deskripsi_kegiatan"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="nonaktifModal" tabindex="-1" aria-labelledby="nonaktifModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="nonaktifModalLabel">Nonaktif Layanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" name="id" id="id">
                            <label for="deskripsi_kegiatan" class="form-label">Deskripsi Kegiatan</label>
                            <textarea class="form-control" id="deskripsi_kegiatan" name="deskripsi_kegiatan" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('body').on('click', '#btn-detail', function() {
                let id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ url('/transaksi-homecare-perawat/detail/"+id+"') }}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#name_pasien').text(response.pasien.name);
                        $('#no_telepon_pasien').text(response.pasien.no_telepon);
                        let gender_pasien = response.pasien.gender;
                        if (gender_pasien === 'L') {
                            gender_pasien = 'Laki-laki';
                        } else if (gender_pasien === 'P') {
                            gender_pasien = 'Perempuan';
                        } else {
                            gender_pasien = '-';
                        }
                        $('#jenis_kelamin_pasien').text(gender_pasien);
                        $('#alamat_pasien').text(response.pasien.address);

                        $('#name_perawat').text(response.perawat.name);
                        $('#no_telepon_perawat').text(response.perawat.no_telepon);
                        let gender_perawat = response.perawat.gender;
                        if (gender_perawat === 'L') {
                            gender_perawat = 'Laki-laki';
                        } else if (gender_perawat === 'P') {
                            gender_perawat = 'Perempuan';
                        } else {
                            gender_perawat = '-';
                        }
                        $('#jenis_kelamin_perawat').text(gender_perawat);

                        $('#layanan').text(response.transaksiHomecare.homecare);
                        $('#waktu').text(response.transaksiHomecare.waktu);
                        $('#jarak').text(response.transaksiHomecare.jarak);
                        $('#metode_pembayaran').text(response.transaksiHomecare
                            .metode_pembayaran);
                        $('#biaya_tambahan').text(response.transaksiHomecare
                            .biaya_tambahan);
                        $('#total_biaya').text(response.transaksiHomecare
                            .total_biaya);
                        $('#bukti_pembayaran').attr('src', response.buktiPembayaran);
                        $("#link_bukti_pembayaran").attr("href", response.buktiPembayaran);
                        $('#deskripsi_kegiatan').text(response.transaksiHomecare
                            .deskripsi_kegiatan);
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
                ajax: "{{ route('transaksi-homecare-perawat.index') }}",
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
                    data: 'pasien',
                    name: 'pasien'
                }, {
                    data: 'perawat',
                    name: 'perawat'
                }, {
                    data: 'metode_pembayaran',
                    name: 'metode_pembayaran'
                }, {
                    data: 'status',
                    name: 'status'
                }, {
                    data: 'aksi',
                    name: 'Aksi'
                }]
            });

            $('#btnPrint').on('click', function() {
                var table = $('#datatable').DataTable();
                var data = table.data().toArray();

                var printContent =
                    '<table class="table"><thead><tr><th>No</th><th>Pasien</th><th>Perawat</th><th>Pembayaran</th><th>Status</th></tr></thead><tbody>';

                $.each(data, function(index, value) {
                    printContent += '<tr><td>' + (index + 1) + '</td><td>' + value
                        .pasien +
                        '</td><td>' + value.perawat + '</td><td>' + value.metode_pembayaran +
                        '</td><td>' + value.status +
                        '</td></tr>';
                });

                printContent += '</tbody></table>';

                var printWindow = window.open('', '', 'height=500,width=800');
                printWindow.document.write('<html><head><title>Print Transaksi Homecare</title>');
                printWindow.document.write(
                    '<style>body{font-family: Arial, sans-serif;font-size: 14px;}table {width: 100%;border-collapse: collapse;}td,th {padding: 5px;border: 1px solid #ddd;}th {background - color: #f2f2f2;text - align: left;}h2 {font - size: 18 px;margin - top: 0;}.text - bold {font - weight: bold;}.text - center {text - align: center;}.text - right {text - align: right;}.mb - 10 {margin - bottom: 10 px;}</style>'
                );
                printWindow.document.write('</head><body>');
                printWindow.document.write(printContent);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            });

            $('body').on('click', '#btnNonaktif', function() {
                let id = $(this).data('id');
                $('#id').val(id);
            })

            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ url('/transaksi-homecare-perawat/nonaktif/"+id+"') }}",
                    data: $(this).serialize(),
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
                        $('#nonaktifModal').modal('hide');
                        $('#form').trigger("reset");
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.success,
                        }).then(function() {
                            top.location.href =
                                "{{ route('transaksi-homecare-perawat.index') }}";
                        });

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            });
        });

        // Aktif Status
        $('body').on('click', '#btnAktif', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Aktif Status',
                text: "Apakah anda yakin?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Aktifkan!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/transaksi-homecare-perawat/aktif/"+id+"') }}",
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
                                }).then(function() {
                                    top.location.href =
                                        "{{ route('transaksi-homecare-perawat.index') }}";
                                });
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
                        url: "{{ url('transaksi-homecare-perawat/"+id+"') }}",
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
                            url: "{{ route('delete-multiple-transaksi-homecare-perawat') }}",
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
