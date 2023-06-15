@extends('layouts.backend_main')
@section('title', 'Profile')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="page-title-box">
            <h4 class="page-title">@yield('title')</h4>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card text-center">
                    <div class="card">
                        <div class="card-body">
                            <img src="{{ asset('storage/users-avatar/' . auth()->user()->avatar) }}"
                                class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                            <h4 class="mb-0 mt-2">{{ auth()->user()->name }}</h4>
                            <p class="text-muted font-14">{{ auth()->user()->type }}</p>
                            <div class="text-start mt-3">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Email</th>
                                        <th>:</th>
                                        <td>{{ auth()->user()->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>No Telepon</th>
                                        <th>:</th>
                                        <td>{{ auth()->user()->no_telepon }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Kelamin</th>
                                        <th>:</th>
                                        <td>{{ auth()->user()->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <th>:</th>
                                        <td>{{ auth()->user()->address }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-5">
                <div class="card">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('user.updateProfile', auth()->user()->id) }}" method="post"
                                id="form" enctype="multipart/form-data">
                                @csrf
                                <h5 class="mb-2 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Personal Info
                                </h5>
                                <div class="mb-3">
                                    <input type="hidden" name="id" id="id" value="{{ auth()->user()->id }}">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ auth()->user()->name }}">
                                    <div class="invalid-feedback errorName">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                value="{{ auth()->user()->email }}">
                                            <div class="invalid-feedback errorEmail">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="no_telepon" class="form-label">No Telepon</label>
                                            <input type="number" id="no_telepon" name="no_telepon" class="form-control"
                                                value="{{ auth()->user()->no_telepon }}">
                                            <div class="invalid-feedback errorNoTelepon">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Jenis Kelamin</label>
                                            <select name="gender" id="gender" class="form-control">
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="L"
                                                    {{ auth()->user()->gender == 'L' ? 'selected' : '' }}>
                                                    Laki-laki
                                                </option>
                                                <option value="P"
                                                    {{ auth()->user()->gender == 'P' ? 'selected' : '' }}>
                                                    Perempuan
                                                </option>
                                            </select>
                                            <div class="invalid-feedback errorGender">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Alamat</label>
                                            <textarea name="address" id="address" rows="1" class="form-control">{{ auth()->user()->address }}</textarea>
                                            <div class="invalid-feedback errorAddress">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="provinsi" class="form-label">Provinsi</label>
                                            <select class="form-control select2" data-toggle="select2" name="provinsi"
                                                id="provinsi">
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach ($provinces as $row)
                                                    <option value="{{ $row->id }}">
                                                        {{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback errorProvinsi"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="kabupaten" class="form-label">Kabupaten</label>
                                            <select class="form-control select2" data-toggle="select2" name="kabupaten"
                                                id="kabupaten">
                                                <option value="">-- Pilih Kabupaten --</option>
                                            </select>
                                            <div class="invalid-feedback errorKabupaten"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="kecamatan" class="form-label">Kecamatan</label>
                                            <select class="form-control select2" data-toggle="select2" name="kecamatan"
                                                id="kecamatan">
                                                <option value="">-- Pilih Kecamatan --</option>
                                            </select>
                                            <div class="invalid-feedback errorKecamatan"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
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
                                    <label class="form-label" for="avatar">Foto</label>
                                    <input type="file" name="avatar" id="avatar" class="form-control"
                                        accept="image/*">
                                    <div class="invalid-feedback errorAvatar">
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary mb-2" id="simpan">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
            let provinsiId = {{ auth()->user()->provinsi_id ?? 'null' }};

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
                        let kabupatenId = {{ auth()->user()->kabupaten_id ?? 'null' }};

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
                                        {{ auth()->user()->kecamatan_id ?? 'null' }};

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
                                                    {{ auth()->user()->desa_id ?? 'null' }}
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
                    url: "{{ url('pengguna/profile/"+id+"') }}",
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

                            if (response.errors.gender) {
                                $('#gender').addClass('is-invalid');
                                $('.errorGender').html(response.errors.gender);
                            } else {
                                $('#gender').removeClass('is-invalid');
                                $('.errorGender').html('');
                            }

                            if (response.errors.type) {
                                $('#type').addClass('is-invalid');
                                $('.errorType').html(response.errors.type);
                            } else {
                                $('#type').removeClass('is-invalid');
                                $('.errorType').html('');
                            }

                            if (response.errors.address) {
                                $('#address').addClass('is-invalid');
                                $('.errorAddress').html(response.errors.address);
                            } else {
                                $('#address').removeClass('is-invalid');
                                $('.errorAddress').html('');
                            }

                            if (response.errors.avatar) {
                                $('#avatar').addClass('is-invalid');
                                $('.errorAvatar').html(response.errors.avatar);
                            } else {
                                $('#avatar').removeClass('is-invalid');
                                $('.errorAvatar').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href = "{{ route('user.profile') }}";
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
