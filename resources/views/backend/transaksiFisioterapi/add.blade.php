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
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <label for="pasien" class="form-label">Pasien</label>
                                        @if (auth()->user()->type != 'Pasien')
                                            <select class="form-control select2" data-toggle="select2" name="pasien"
                                                id="pasien">
                                                <option value="">-- Pilih Pasien --</option>
                                                @foreach ($pasien as $row)
                                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="pasien" id="pasien"
                                                value="{{ auth()->user()->id }}">
                                            <input type="text" name="pasien_name" id="pasien_name" class="form-control"
                                                value="{{ auth()->user()->name }}" readonly>
                                        @endif
                                        <div class="invalid-feedback errorPasien"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
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
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <label for="dokter" class="form-label">Dokter</label>
                                        @if (auth()->user()->type != 'Dokter')
                                            <select class="form-control select2" data-toggle="select2" name="dokter"
                                                id="dokter">
                                                <option value="">-- Pilih Dokter --</option>
                                                @foreach ($dokter as $row)
                                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="dokter" id="dokter"
                                                value="{{ auth()->user()->id }}">
                                            <input type="text" name="dokter_name" id="dokter_name" class="form-control"
                                                value="{{ auth()->user()->name }}" readonly>
                                        @endif
                                        <div class="invalid-feedback errorDokter"></div>
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
                            <div class="row ">
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <label for="fisioterapi" class="form-label">Fisioterapi</label>
                                        <select class="form-control select2" data-toggle="select2" name="fisioterapi"
                                            id="fisioterapi">
                                            <option value="">-- Pilih Fisioterapi --</option>
                                            @foreach ($fisioterapi as $row)
                                                <option value="{{ $row->id }}">{{ $row->kode_fisioterapi }} -
                                                    {{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback errorFisioterapi"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Biaya Layanan</label>
                                        <input type="text" name="harga" id="harga" class="form-control"
                                            readonly>
                                        <div class="invalid-feedback errorHarga"></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
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
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                                <button type="button" class="btn btn-secondary mb-2"
                                    onclick="window.location='{{ route('transaksi-fisioterapi.index') }}'">Kembali</button>
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

            $('#jarak').on('input', function() {
                var jarak = $(this).val();
                var biaya = Math.floor(jarak / 10) * 15000; // biaya per 10 km
                $('#biaya_tambahan').val(biaya); // tampilkan biaya dalam format rupiah

                // hitung total biaya
                var harga = $('#harga').val();
                if (harga) {
                    var total_biaya = parseInt(harga) + parseInt(biaya);
                    $('#total_biaya').val(total_biaya);
                }
            });

            $('#fisioterapi').on('change', function() {
                var id_fisioterapi = $('#fisioterapi').val();
                if (id_fisioterapi) {
                    $.ajax({
                        url: "{{ route('transaksi-fisioterapi.get-fisioterapi') }}",
                        type: "POST",
                        data: {
                            id_fisioterapi: id_fisioterapi,
                        },
                        success: function(response) {
                            let harga = response.harga;
                            $('#harga').val(harga);

                            // hitung ulang total biaya
                            var biaya_tambahan = $('#biaya_tambahan').val();
                            if (biaya_tambahan) {
                                var total_biaya = parseInt(harga) + parseInt(biaya_tambahan);
                                $('#total_biaya').val(total_biaya);
                            }
                        }
                    });
                } else {
                    $('#harga').val('');
                    $('#total_biaya').val('');
                }
            });


            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: new FormData(this),
                    url: "{{ route('transaksi-fisioterapi.store') }}",
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
                            if (response.errors.fisioterapi) {
                                $('#fisioterapi').addClass('is-invalid');
                                $('.errorFisioterapi').html(response.errors.fisioterapi);
                            } else {
                                $('#fisioterapi').removeClass('is-invalid');
                                $('.errorFisioterapi').html('');
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
                                    "{{ route('transaksi-fisioterapi.index') }}";
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
