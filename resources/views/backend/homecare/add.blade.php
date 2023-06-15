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
                <form action="{{ route('homecare.store') }}" method="post" id="form" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="kode_homecare" class="form-label">Kode Homecare</label>
                                        <input type="text" id="kode_homecare" name="kode_homecare" class="form-control"
                                            value="{{ $homecareCode }}" readonly>
                                        <div class="invalid-feedback errorKodeHomecare">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama</label>
                                        <input type="text" id="name" name="name" class="form-control">
                                        <div class="invalid-feedback errorName">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <label for="kategori" class="form-label">Kategori</label>
                                        <select name="kategori" id="kategori" class="form-control select2"
                                            data-toggle="select2">
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach ($kategori as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback errorKategori">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <label for="poli" class="form-label">Poli</label>
                                        <select name="poli" id="poli" class="form-control select2"
                                            data-toggle="select2">
                                            <option value="">-- Pilih Poli --</option>
                                            @foreach ($poli as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback errorPoli">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <label for="bayar" class="form-label">Jenis Bayar</label>
                                        <select name="bayar" id="bayar" class="form-control select2"
                                            data-toggle="select2">
                                            <option value="">-- Pilih Jenis --</option>
                                            @foreach ($bayar as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback errorJenisBayar">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" id="deskripsi" rows="1" class="form-control"></textarea>
                                        <div class="invalid-feedback errorDeskripsi">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="foto" class="form-label">Foto</label>
                                        <input type="file" id="foto" name="foto" class="form-control">
                                        <div class="invalid-feedback errorFoto">
                                        </div>
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
                                        <label for="paket_obat" class="form-label">Paket Obat</label>
                                        <input type="number" name="paket_obat" id="paket_obat" class="form-control"
                                            value="0">
                                        <div class="invalid-feedback errorPaketObat">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kso" class="form-label">K.S.O</label>
                                        <input type="number" name="kso" id="kso" class="form-control"
                                            value="0">
                                        <div class="invalid-feedback errorKSO">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jasa_medis_dokter" class="form-label">Jasa Medis Dokter</label>
                                        <input type="number" name="jasa_medis_dokter" id="jasa_medis_dokter"
                                            class="form-control" value="0">
                                        <div class="invalid-feedback errorJasaMedisDokter">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="total_biaya_dokter" class="form-label">Total Biaya Dokter</label>
                                        <input type="number" name="total_biaya_dokter" id="total_biaya_dokter"
                                            class="form-control" value="0">
                                        <div class="invalid-feedback errorTotalBiayaDokter">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="form-label" for="jasa_rumah_sakit">Jasa Rumah Sakit</label>
                                                <input type="number" name="jasa_rumah_sakit" id="jasa_rumah_sakit"
                                                    class="form-control" value="0">
                                                <div class="invalid-feedback errorJasaRumahSakit">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label" for="menejemen">Menejemen</label>
                                                <input type="number" name="menejemen" id="menejemen"
                                                    class="form-control" value="0">
                                                <div class="invalid-feedback errorMenejemen">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jasa_medis_perawat" class="form-label">Jasa Medis Perawat</label>
                                        <input type="number" name="jasa_medis_perawat" id="jasa_medis_perawat"
                                            class="form-control" value="0">
                                        <div class="invalid-feedback errorJasaMedisPerawar">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="total_biaya_perawat" class="form-label">Total Biaya Perawat</label>
                                        <input type="number" name="total_biaya_perawat" id="total_biaya_perawat"
                                            class="form-control" value="0">
                                        <div class="invalid-feedback errorTotalBiayaPerawat">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="total_biaya_perawat_dokter" class="form-label">Total Biaya Dokter &
                                            Perawat</label>
                                        <input type="number" name="total_biaya_perawat_dokter"
                                            id="total_biaya_perawat_dokter" class="form-control" value="0">
                                        <div class="invalid-feedback errorTotalBiayaDokterPerawat">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary mb-2"
                                        onclick="window.location='{{ route('homecare.index') }}'">Kembali</button>
                                    <button type="submit" class="btn btn-primary mb-2" id="simpan">Simpan</button>
                                </div>
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

            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: new FormData(this),
                    url: "{{ route('homecare.store') }}",
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
                            if (response.errors.name) {
                                $('#name').addClass('is-invalid');
                                $('.errorName').html(response.errors.name);
                            } else {
                                $('#name').removeClass('is-invalid');
                                $('.errorName').html('');
                            }

                            if (response.errors.kategori) {
                                $('#kategori').addClass('is-invalid');
                                $('.errorKategori').html(response.errors.kategori);
                            } else {
                                $('#kategori').removeClass('is-invalid');
                                $('.errorKategori').html('');
                            }

                            if (response.errors.poli) {
                                $('#poli').addClass('is-invalid');
                                $('.errorPoli').html(response.errors.poli);
                            } else {
                                $('#poli').removeClass('is-invalid');
                                $('.errorPoli').html('');
                            }

                            if (response.errors.bayar) {
                                $('#bayar').addClass('is-invalid');
                                $('.errorJenisBayar').html(response.errors.bayar);
                            } else {
                                $('#bayar').removeClass('is-invalid');
                                $('.errorJenisBayar').html('');
                            }

                            if (response.errors.deskripsi) {
                                $('#deskripsi').addClass('is-invalid');
                                $('.errorDeskripsi').html(response.errors.deskripsi);
                            } else {
                                $('#deskripsi').removeClass('is-invalid');
                                $('.errorDeskripsi').html('');
                            }

                            if (response.errors.foto) {
                                $('#foto').addClass('is-invalid');
                                $('.errorFoto').html(response.errors.foto);
                            } else {
                                $('#foto').removeClass('is-invalid');
                                $('.errorFoto').html('');
                            }

                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href = "{{ route('homecare.index') }}";
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
