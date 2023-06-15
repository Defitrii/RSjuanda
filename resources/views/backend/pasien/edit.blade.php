@extends('layouts.backend_main')
@section('title', 'Edit Pasien')
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">@yield('title')</h4>
                </div>
                <form action="{{ route('pasien.update', $pasien->pasien->id) }}" method="post" id="form"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="hidden" name="id" id="id" value="{{ $pasien->id }}">
                                        <input type="hidden" name="pasien_id" id="pasien_id"
                                            value="{{ $pasien->pasien->id }}">
                                        <input type="text" id="name" name="name" class="form-control"
                                            value="{{ $pasien->name }}">
                                        <div class="invalid-feedback errorName">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" id="email" name="email" class="form-control"
                                                    value="{{ $pasien->email }}">
                                                <div class="invalid-feedback errorEmail">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="no_telepon" class="form-label">No Telepon</label>
                                                <input type="number" id="no_telepon" name="no_telepon" class="form-control"
                                                    value="{{ $pasien->no_telepon }}">
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
                                                    <option value="L" {{ $pasien->gender == 'L' ? 'selected' : '' }}>
                                                        Laki-laki
                                                    </option>
                                                    <option value="P" {{ $pasien->gender == 'P' ? 'selected' : '' }}>
                                                        Perempuan
                                                    </option>
                                                </select>
                                                <div class="invalid-feedback errorGender">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label" for="gol_darah">Gol. Darah</label>
                                                <select name="gol_darah" id="gol_darah" class="form-control">
                                                    <option value="">-- Pilih Gol. Darah --</option>
                                                    <option value="A"
                                                        {{ $pasien->pasien->gol_darah == 'A' ? 'selected' : '' }}>A</option>
                                                    <option value="B"
                                                        {{ $pasien->pasien->gol_darah == 'B' ? 'selected' : '' }}>B</option>
                                                    <option value="AB"
                                                        {{ $pasien->pasien->gol_darah == 'AB' ? 'selected' : '' }}>AB
                                                    </option>
                                                    <option value="O"
                                                        {{ $pasien->pasien->gol_darah == 'O' ? 'selected' : '' }}>O
                                                    </option>
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
                                                    class="form-control" value="{{ $pasien->pasien->tempat_lahir }}">
                                                <div class="invalid-feedback errorTempatLahir">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                                    class="form-control" value="{{ $pasien->pasien->tanggal_lahir }}">
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
                                                    <option value="Islam"
                                                        {{ $pasien->pasien->agama == 'Islam' ? 'selected' : '' }}>Islam
                                                    </option>
                                                    <option value="Kristen"
                                                        {{ $pasien->pasien->agama == 'Kristen' ? 'selected' : '' }}>Kristen
                                                    </option>
                                                    <option value="Katolik"
                                                        {{ $pasien->pasien->agama == 'Katolik' ? 'selected' : '' }}>Katolik
                                                    </option>
                                                    <option value="Hindu"
                                                        {{ $pasien->pasien->agama == 'Hindu' ? 'selected' : '' }}>Hindu
                                                    </option>
                                                    <option value="Budha"
                                                        {{ $pasien->pasien->agama == 'Budha' ? 'selected' : '' }}>Budha
                                                    </option>
                                                    <option value="Konghucu"
                                                        {{ $pasien->pasien->agama == 'Konghucu' ? 'selected' : '' }}>
                                                        Konghucu
                                                    </option>
                                                </select>
                                                <div class="invalid-feedback errorAgama">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label" for="status_nikah">Status Nikah</label>
                                                <select name="status_nikah" id="status_nikah" class="form-control">
                                                    <option value="">-- Pilih Status Nikah --</option>
                                                    <option value="Belum Menikah"
                                                        {{ $pasien->pasien->status_nikah == 'Belum Menikah' ? 'selected' : '' }}>
                                                        Belum Menikah</option>
                                                    <option value="Menikah"
                                                        {{ $pasien->pasien->status_nikah == 'Menikah' ? 'selected' : '' }}>
                                                        Menikah</option>
                                                </select>
                                                <div class="invalid-feedback errorStatusNikah">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Alamat</label>
                                        <textarea name="address" id="address" rows="1" class="form-control">{{ $pasien->address }}</textarea>
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
                                                        <option value="{{ $row->id }}"
                                                            {{ $pasien->provinsi_id == $row->id ? 'selected' : '' }}>
                                                            {{ $row->name }}</option>
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
                                        <label class="form-label" for="pekerjaan">Pekerjaan</label>
                                        <input type="text" name="pekerjaan" id="pekerjaan" class="form-control"
                                            value="{{ $pasien->pasien->pekerjaan }}">
                                        <div class="invalid-feedback errorPekerjaan">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="mb-3">
                                            <label for="ktp" class="form-label">Upload KTP</label>
                                            <input type="file" class="form-control" name="ktp" id="ktp"
                                                value="{{ $pasien->pasien->ktp }}">
                                            <small class="text-danger errorKTP"></small>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="button" class="btn btn-secondary mb-2"
                                            onclick="window.location='{{ route('pasien.index') }}'">Kembali</button>
                                        <button type="submit" class="btn btn-primary mb-2"
                                            id="simpan">Simpan</button>
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

            // Mendapatkan nilai provinsi dari database
            let provinsiId = {{ $pasien->provinsi_id ?? 'null' }};

            // Jika nilai provinsi tersedia, memuat kabupaten
            if (provinsiId) {
                // Mengisi nilai provinsi yang tersimpan di database
                $('#provinsi').val(provinsiId).trigger('change');

                // Memuat daftar kabupaten berdasarkan provinsi yang dipilih
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.get-kabupaten') }}",
                    data: {
                        id_provinsi: provinsiId
                    },
                    success: function(response) {
                        // Mengisi daftar kabupaten berdasarkan provinsi yang dipilih
                        $('#kabupaten').html(response);

                        // Mendapatkan nilai kabupaten dari database
                        let kabupatenId = {{ $pasien->kabupaten_id ?? 'null' }};

                        // Jika nilai kabupaten tersedia, memuat kecamatan
                        if (kabupatenId) {
                            // Mengisi nilai kabupaten yang tersimpan di database
                            $('#kabupaten').val(kabupatenId).trigger('change');

                            // Memuat daftar kecamatan berdasarkan kabupaten yang dipilih
                            $.ajax({
                                type: "POST",
                                url: "{{ route('user.get-kecamatan') }}",
                                data: {
                                    id_kabupaten: kabupatenId
                                },
                                success: function(response) {
                                    // Mengisi daftar kecamatan berdasarkan kabupaten yang dipilih
                                    $('#kecamatan').html(response);

                                    // Mendapatkan nilai kecamatan dari database
                                    let kecamatanId =
                                        {{ $pasien->kecamatan_id ?? 'null' }};

                                    // Jika nilai kecamatan tersedia, memuat desa
                                    if (kecamatanId) {
                                        // Mengisi nilai kecamatan yang tersimpan di database
                                        $('#kecamatan').val(kecamatanId).trigger('change');

                                        // Memuat daftar desa berdasarkan kecamatan yang dipilih
                                        $.ajax({
                                            type: "POST",
                                            url: "{{ route('user.get-desa') }}",
                                            data: {
                                                id_kecamatan: kecamatanId
                                            },
                                            success: function(response) {
                                                // Mengisi daftar desa berdasarkan kecamatan yang dipilih
                                                $('#desa').html(response);

                                                // Mengisi nilai desa yang tersimpan di database
                                                $('#desa').val(
                                                    {{ $pasien->desa_id ?? 'null' }}
                                                );
                                            },
                                            error: function(xhr, ajaxOptions,
                                                thrownError) {
                                                console.error(xhr.status +
                                                    "\n" + xhr
                                                    .responseText + "\n" +
                                                    thrownError);
                                            }
                                        });
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    console.error(xhr.status + "\n" + xhr.responseText +
                                        "\n" +
                                        thrownError);
                                }
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }

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
                let id = $('#id').val();

                $.ajax({
                    data: new FormData(this),
                    url: "{{ url('pasien/"+id+"') }}",
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

                            if (response.errors.ktp) {
                                $('#ktp').addClass('is-invalid');
                                $('.errorKTP').html(response.errors.ktp);
                            } else {
                                $('#ktp').removeClass('is-invalid');
                                $('.errorKTP').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href = "{{ route('pasien.index') }}";
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
