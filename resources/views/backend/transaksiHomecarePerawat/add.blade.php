@extends('layouts.backend_main')
@section('title', 'Tambah Data')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">@yield('title')</h4>
                </div>
                <form id="form" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    @if (auth()->user()->type != 'Pasien')
                                        <div class="mb-3">
                                            <label for="pasien" class="form-label">Pasien</label>
                                            <select class="form-control select2" data-toggle="select2" name="pasien"
                                                id="pasien">
                                                <option value="">-- Pilih Pasien --</option>
                                                @foreach ($pasien as $row)
                                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback errorPasien"></div>
                                        </div>
                                    @else
                                        <label for="pasien" class="form-label">Pasien</label>
                                        <input type="hidden" name="pasien" id="pasien"
                                            value="{{ auth()->user()->id }}">
                                        <input type="text" name="pasien_name" id="pasien_name" class="form-control"
                                            value="{{ auth()->user()->name }}" readonly>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="perawat" class="form-label">Perawat</label>
                                        @if (auth()->user()->type != 'Perawat')
                                            <select class="form-control select2" data-toggle="select2" name="perawat"
                                                id="perawat">
                                                <option value="">-- Pilih Perawat --</option>
                                                @foreach ($perawat as $row)
                                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="perawat" id="perawat"
                                                value="{{ auth()->user()->id }}">
                                            <input type="text" name="perawat_name" id="perawat_name" class="form-control"
                                                value="{{ auth()->user()->name }}" readonly>
                                        @endif
                                        <div class="invalid-feedback errorPerawat"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="riwayat_penyakit" class="form-label">Riwayat Penyakit</label>
                                        <textarea name="riwayat_penyakit" id="riwayat_penyakit" rows="1" class="form-control"></textarea>
                                        <div class="invalid-feedback errorRiwayatPenyakit"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="waktu" class="form-label">Waktu Mulai</label>
                                        <input type="datetime-local" name="waktu" id="waktu" class="form-control">
                                        <div class="invalid-feedback errorWaktu"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="jarak" class="form-label">Jarak (KM)</label>
                                        <input type="number" name="jarak" id="jarak" class="form-control"
                                            value="0">
                                        <div class="invalid-feedback errorJarak"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="biaya_tambahan" class="form-label">Tambahan Biaya (Per 10 KM)</label>
                                        <input type="text" name="biaya_tambahan" id="biaya_tambahan" value="0"
                                            class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="homecare" class="form-label">Homecare</label>
                                        <div style="overflow-y:scroll;height:150px;margin-bottom:30px;">
                                            <div class="form-group">
                                                <label>
                                                    @foreach ($homecare as $row)
                                                        <div class="form-group">
                                                            <label>
                                                                <input type="checkbox" name="homecare[]" id="homecare"
                                                                    class="homecare-checkbox" value="{{ $row->name }}"
                                                                    data-id="{{ $row->id }}">
                                                                {{ $row->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </label>
                                            </div>
                                        </div>
                                        <div class="text-danger errorHomecare text-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="total_biaya" class="form-label">Total Biaya</label>
                                        <input type="text" name="total_biaya" id="total_biaya" class="form-control"
                                            readonly>
                                        <div class="invalid-feedback errorTotalBiaya"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="jarak" class="form-label">Metode Pembayaran</label>
                                        <select name="pembayaran" id="pembayaran" class="form-control">
                                            <option value="">-- Pilih Metode --</option>
                                            <option value="COD">COD</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                        <div class="invalid-feedback errorPembayaran"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                                            class="form-control">
                                        <div class="invalid-feedback errorBuktiPembayaran"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-secondary mb-2"
                                    onclick="window.location='{{ route('transaksi-homecare-perawat.index') }}'">Kembali</button>
                                <button type="submit" class="btn btn-primary mb-2" id="simpan">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end page title -->
    </div>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var total_biaya = 0;
            var jumlah_checked = 0;
            $('.homecare-checkbox').change(function() {
                let biaya_tambahan = parseInt($('#biaya_tambahan').val());
                let homecare_id = $(this).data('id');
                let is_checked = $(this).is(':checked');

                // cek apakah checkbox di-check atau di-uncheck
                if (is_checked) {
                    if (jumlah_checked < 3) {
                        $.ajax({
                            url: '/transaksi-homecare-perawat/getHomecarePrice',
                            method: 'POST',
                            data: {
                                homecare_id: homecare_id
                            },
                            dataType: 'json',
                            success: function(response) {
                                let price = parseInt(response.price);
                                total_biaya += price;
                                if (total_biaya <= 0) {
                                    $('#total_biaya').val(0);
                                } else {
                                    $('#total_biaya').val(total_biaya);
                                }

                                // tambahkan biaya tambahan jarak hanya sekali saat layanan pertama dipilih
                                if (jumlah_checked == 0) {
                                    let jarak = $('#jarak').val();
                                    let biaya_jarak = Math.floor(jarak / 10) * 15000;
                                    total_biaya += biaya_jarak;
                                    $('#biaya_tambahan').val(biaya_jarak);
                                    $('#total_biaya').val(total_biaya);
                                }
                                jumlah_checked++;
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Anda hanya dapat memilih maksimal 3 layanan!',
                        });

                        $(this).prop('checked', false);
                    }
                } else {
                    $.ajax({
                        url: '/transaksi-homecare-perawat/getHomecarePrice',
                        method: 'POST',
                        data: {
                            homecare_id: homecare_id
                        },
                        dataType: 'json',
                        success: function(response) {
                            let price = parseInt(response.price);
                            total_biaya -= price;
                            if (total_biaya <= 0) {
                                $('#total_biaya').val(0);
                            } else {
                                $('#total_biaya').val(total_biaya);
                            }

                            // kurangi biaya tambahan jarak hanya saat layanan terakhir di-uncheck
                            jumlah_checked--;
                            if (jumlah_checked == 0) {
                                $('#biaya_tambahan').val(0);
                                $('#total_biaya').val(total_biaya);
                            }
                        }
                    });
                }
            });


            $('#jarak').on('input', function() {
                var jarak = $(this).val();
                var biaya = Math.floor(jarak / 10) * 15000; // biaya per 10 km
                $('#biaya_tambahan').val(biaya); // tampilkan biaya dalam format rupiah
                if (total_biaya > 0) {
                    $('#total_biaya').val(total_biaya + biaya);
                }
            });

            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: new FormData(this),
                    url: "{{ route('transaksi-homecare-perawat.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    cache: false,
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
                            if (response.errors.pasien) {
                                $('#pasien').addClass('is-invalid');
                                $('.errorPasien').html(response.errors.pasien);
                            } else {
                                $('#pasien').removeClass('is-invalid');
                                $('.errorPasien').html('');
                            }
                            if (response.errors.perawat) {
                                $('#perawat').addClass('is-invalid');
                                $('.errorPerawat').html(response.errors.perawat);
                            } else {
                                $('#perawat').removeClass('is-invalid');
                                $('.errorPerawat').html('');
                            }
                            if (response.errors.dokter) {
                                $('#dokter').addClass('is-invalid');
                                $('.errorDokter').html(response.errors.dokter);
                            } else {
                                $('#dokter').removeClass('is-invalid');
                                $('.errorDokter').html('');
                            }
                            if (response.errors.riwayat_penyakit) {
                                $('#riwayat_penyakit').addClass('is-invalid');
                                $('.errorRiwayatPenyakit').html(response.errors
                                    .riwayat_penyakit);
                            } else {
                                $('#riwayat_penyakit').removeClass('is-invalid');
                                $('.errorRiwayatPenyakit').html('');
                            }
                            if (response.errors.waktu) {
                                $('#waktu').addClass('is-invalid');
                                $('.errorWaktu').html(response.errors.waktu);
                            } else {
                                $('#waktu').removeClass('is-invalid');
                                $('.errorWaktu').html('');
                            }
                            if (response.errors.jarak) {
                                $('#jarak').addClass('is-invalid');
                                $('.errorJarak').html(response.errors.jarak);
                            } else {
                                $('#jarak').removeClass('is-invalid');
                                $('.errorJarak').html('');
                            }
                            if (response.errors.homecare) {
                                $('.errorHomecare').html(response.errors.homecare);
                            } else {
                                $('.errorHomecare').html('');
                            }
                            if (response.errors.bukti_pembayaran) {
                                $('#bukti_pembayaran').addClass('is-invalid');
                                $('.errorBuktiPembayaran').html(response.errors
                                    .bukti_pembayaran);
                            } else {
                                $('#bukti_pembayaran').removeClass('is-invalid');
                                $('.errorBuktiPembayaran').html('');
                            }
                            if (response.errors.pembayaran) {
                                $('#pembayaran').addClass('is-invalid');
                                $('.errorPembayaran').html(response.errors
                                    .pembayaran);
                            } else {
                                $('#pembayaran').removeClass('is-invalid');
                                $('.errorPembayaran').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href =
                                    "{{ route('transaksi-homecare-perawat.index') }}";
                            });
                        }
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
