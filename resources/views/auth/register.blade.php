@extends('layouts.auth_app')
@section('title', 'Registrasi')
@section('content')

    <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-8">
            <div class="card">
                <!-- Logo -->
                <div class="card-header pt-3 pb-3 text-center bg-rs">
                    <a href="index.html">
                        <span><img src="{{ asset('assets') }}/images/juanda_header.png" alt="" height="40"></span>
                    </a>
                </div>

                <div class="card-body p-3">
                    <div class="text-center w-75 m-auto">
                        <h4 class="text-dark-50 text-center pb-0 fw-bold">@yield('title')</h4>
                    </div>

                    @if (session('message'))
                        <div class="alert alert-danger">{{ session('message') }}</div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <input type="hidden" name="_csrf_token" value="{{ csrf_token('token_name') }}">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                        id="name" name="name" value="{{ old('name') }}" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                        id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="no_telepon" class="form-label">No Telepon</label>
                                    <input class="form-control @error('no_telepon') is-invalid @enderror" type="number"
                                        id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}">
                                    @error('no_telepon')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="gender">Jenis Kelamin</label>
                                    <select name="gender" id="gender"
                                        class="form-control @error('gender') is-invalid @enderror">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <select class="form-control @error('provinsi') is-invalid @enderror select2"
                                        data-toggle="select2" name="provinsi" id="provinsi">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach ($provinces as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('provinsi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="kabupaten" class="form-label">Kabupaten</label>
                                    <select class="form-control @error('kabupaten') is-invalid @enderror select2"
                                        data-toggle="select2" name="kabupaten" id="kabupaten">
                                        <option value="">-- Pilih Kabupaten --</option>
                                    </select>
                                    @error('kabupaten')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <select class="form-control @error('kecamatan') is-invalid @enderror select2"
                                        data-toggle="select2" name="kecamatan" id="kecamatan">
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                    @error('kecamatan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="desa" class="form-label">Desa</label>
                                    <select class="form-control @error('desa') is-invalid @enderror select2"
                                        data-toggle="select2" name="desa" id="desa">
                                        <option value="">-- Pilih Desa --</option>
                                    </select>
                                    @error('desa')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-0 text-center">
                            <button class="btn btn-primary" type="submit"> Register </button>
                        </div>
                    </form>
                </div>
                <!-- end card-body -->
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted">Punya akun? <a href="{{ route('login') }}"
                                class="text-muted ms-1"><b>Login</b></a>
                        </p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
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

            $('#provinsi').on('change', function() {
                let id_provinsi = $('#provinsi').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('register.get-kabupaten') }}",
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
                    url: "{{ route('register.get-kecamatan') }}",
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
                    url: "{{ route('register.get-desa') }}",
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
        });
    </script>
@endsection
