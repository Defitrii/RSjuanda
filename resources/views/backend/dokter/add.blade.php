@extends('layouts.backend_main')
@section('title', 'Tambah Dokter')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">@yield('title')</h4>
                </div>
                <form action="{{ route('dokter.store') }}" method="post" id="form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="nip" class="form-label">NIP</label>
                                        <input type="text" id="nip" name="nip" class="form-control">
                                        <div class="invalid-feedback errorNIP">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" id="name" name="name" class="form-control">
                                        <div class="invalid-feedback errorName">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" id="email" name="email" class="form-control">
                                                <div class="invalid-feedback errorEmail">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="no_telepon" class="form-label">No Telepon</label>
                                                <input type="number" id="no_telepon" name="no_telepon"
                                                    class="form-control">
                                                <div class="invalid-feedback errorNoTelepon">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="form-label" for="gender">Jenis Kelamin</label>
                                                <select name="gender" id="gender" class="form-control">
                                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                                <div class="invalid-feedback errorGender">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label" for="gol_darah">Gol. Darah</label>
                                                <select name="gol_darah" id="gol_darah" class="form-control">
                                                    <option value="">-- Pilih Gol. Darah --</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="AB">AB</option>
                                                    <option value="O">O</option>
                                                </select>
                                                <div class="invalid-feedback errorGolDarah">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <label class="form-label" for="tempat_lahir">Tempat, Tanggal Lahir</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="tempat_lahir" id="tempat_lahir"
                                                    class="form-control">
                                                <div class="invalid-feedback errorTempatLahir">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                                    class="form-control">
                                                <div class="invalid-feedback errorTanggalLahir">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="form-label" for="agama">Agama</label>
                                                <select name="agama" id="agama" class="form-control">
                                                    <option value="">-- Pilih Agama --</option>
                                                    <option value="Islam">Islam</option>
                                                    <option value="Kristen">Kristen</option>
                                                    <option value="Katolik">Katolik</option>
                                                    <option value="Hindu">Hindu</option>
                                                    <option value="Budha">Budha</option>
                                                    <option value="Konghucu">Konghucu</option>
                                                </select>
                                                <div class="invalid-feedback errorAgama">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label" for="status_nikah">Status Nikah</label>
                                                <select name="status_nikah" id="status_nikah" class="form-control">
                                                    <option value="">-- Pilih Status Nikah --</option>
                                                    <option value="Belum Menikah">Belum Menikah</option>
                                                    <option value="Menikah">Menikah</option>
                                                </select>
                                                <div class="invalid-feedback errorStatusNikah">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="jabatan">Jabatan</label>
                                        <select name="jabatan" id="jabatan" class="form-control">
                                            <option value="">-- Pilih Jabatan --</option>
                                            @foreach ($jabatan as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback errorJabatan">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Alamat</label>
                                        <textarea name="address" id="address" rows="1" class="form-control"></textarea>
                                        <div class="invalid-feedback errorAddress">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="provinsi" class="form-label">Provinsi</label>
                                                <select class="form-control select2" data-toggle="select2"
                                                    name="provinsi" id="provinsi">
                                                    <option value="">-- Pilih Provinsi --</option>
                                                    @foreach ($provinces as $row)
                                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback errorProvinsi"></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="kabupaten" class="form-label">Kabupaten</label>
                                                <select class="form-control select2" data-toggle="select2"
                                                    name="kabupaten" id="kabupaten">
                                                    <option value="">-- Pilih Kabupaten --</option>
                                                </select>
                                                <div class="invalid-feedback errorKabupaten"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                                <select class="form-control select2" data-toggle="select2"
                                                    name="kecamatan" id="kecamatan">
                                                    <option value="">-- Pilih Kecamatan --</option>
                                                </select>
                                                <div class="invalid-feedback errorKecamatan"></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="desa" class="form-label">Desa</label>
                                                <select class="form-control select2" data-toggle="select2" name="desa"
                                                    id="desa">
                                                    <option value="">-- Pilih Desa --</option>
                                                </select>
                                                <div class="invalid-feedback errorDesa"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="form-label" for="spesialis">Spesialis</label>
                                                <input type="text" name="spesialis" id="spesialis"
                                                    class="form-control">
                                                <div class="invalid-feedback errorSpesialis">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label" for="pengalaman">Pengalaman (Tahun)</label>
                                                <input type="number" name="pengalaman" id="pengalaman"
                                                    class="form-control">
                                                <div class="invalid-feedback errorPengalaman">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="form-label" for="jam_masuk">Jam Masuk</label>
                                                <input type="time" name="jam_masuk" id="jam_masuk"
                                                    class="form-control">
                                                <div class="invalid-feedback errorJamMasuk">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label" for="jam_pulang">Jam Pulang</label>
                                                <input type="time" name="jam_pulang" id="jam_pulang"
                                                    class="form-control">
                                                <div class="invalid-feedback errorJamPulang">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" id="deskripsi" rows="1" class="form-control"></textarea>
                                        <div class="invalid-feedback errorDeskripsi">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pilih Hari</label><br>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="hari"
                                                name="hari[]" value="Senin">
                                            <label class="form-check-label">Senin</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="hari"
                                                name="hari[]" value="Selasa">
                                            <label class="form-check-label">Selasa</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="hari"
                                                name="hari[]" value="Rabu">
                                            <label class="form-check-label">Rabu</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="hari"
                                                name="hari[]" value="Kamis">
                                            <label class="form-check-label">Kamis</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="hari"
                                                name="hari[]" value="Jumat">
                                            <label class="form-check-label">Jumat</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="hari"
                                                name="hari[]" value="Sabtu">
                                            <label class="form-check-label">Sabtu</label>
                                        </div>
                                        <div class="text-danger errorHari text-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary mb-2"
                                        onclick="window.location='{{ route('dokter.index') }}'">Kembali</button>
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

            $('#provinsi').on('change', function() {
                let id_provinsi = $('#provinsi').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('user.get-kabupaten') }}",
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
                    url: "{{ route('user.get-kecamatan') }}",
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
                    url: "{{ route('user.get-desa') }}",
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

            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('dokter.store') }}",
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
                            if (response.errors.nip) {
                                $('#nip').addClass('is-invalid');
                                $('.errorNIP').html(response.errors.nip);
                            } else {
                                $('#nip').removeClass('is-invalid');
                                $('.errorNIP').html('');
                            }

                            if (response.errors.name) {
                                $('#name').addClass('is-invalid');
                                $('.errorName').html(response.errors.name);
                            } else {
                                $('#name').removeClass('is-invalid');
                                $('.errorName').html('');
                            }

                            if (response.errors.email) {
                                $('#email').addClass('is-invalid');
                                $('.errorEmail').html(response.errors.email);
                            } else {
                                $('#email').removeClass('is-invalid');
                                $('.errorEmail').html('');
                            }

                            if (response.errors.no_telepon) {
                                $('#no_telepon').addClass('is-invalid');
                                $('.errorNoTelepon').html(response.errors.no_telepon);
                            } else {
                                $('#no_telepon').removeClass('is-invalid');
                                $('.errorNoTelepon').html('');
                            }

                            if (response.errors.jabatan) {
                                $('#jabatan').addClass('is-invalid');
                                $('.errorJabatan').html(response.errors.jabatan);
                            } else {
                                $('#jabatan').removeClass('is-invalid');
                                $('.errorJabatan').html('');
                            }

                            if (response.errors.spesialis) {
                                $('#spesialis').addClass('is-invalid');
                                $('.errorSpesialis').html(response.errors.spesialis);
                            } else {
                                $('#spesialis').removeClass('is-invalid');
                                $('.errorSpesialis').html('');
                            }

                            if (response.errors.pengalaman) {
                                $('#pengalaman').addClass('is-invalid');
                                $('.errorPengalaman').html(response.errors.pengalaman);
                            } else {
                                $('#pengalaman').removeClass('is-invalid');
                                $('.errorPengalaman').html('');
                            }

                            if (response.errors.jam_masuk) {
                                $('#jam_masuk').addClass('is-invalid');
                                $('.errorJamMasuk').html(response.errors.jam_masuk);
                            } else {
                                $('#jam_masuk').removeClass('is-invalid');
                                $('.errorJamMasuk').html('');
                            }

                            if (response.errors.jam_pulang) {
                                $('#jam_pulang').addClass('is-invalid');
                                $('.errorJamPulang').html(response.errors.jam_pulang);
                            } else {
                                $('#jam_pulang').removeClass('is-invalid');
                                $('.errorJamPulang').html('');
                            }

                            if (response.errors.deskripsi) {
                                $('#deskripsi').addClass('is-invalid');
                                $('.errorDeskripsi').html(response.errors.deskripsi);
                            } else {
                                $('#deskripsi').removeClass('is-invalid');
                                $('.errorDeskripsi').html('');
                            }

                            if (response.errors.hari) {
                                $('.errorHari').html(response.errors.hari);
                            } else {
                                $('.errorHari').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href = "{{ route('dokter.index') }}";
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
