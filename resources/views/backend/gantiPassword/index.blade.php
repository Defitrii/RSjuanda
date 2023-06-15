@extends('layouts.backend_main')
@section('title', 'Ganti Password')
@section('content')
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('ganti-password.update') }}" method="post" id="form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="old_password">Password Lama</label>
                            <input type="password" class="form-control" id="old_password" name="old_password">
                            <div class="invalid-feedback errorOldPassword">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="invalid-feedback errorPassword">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                            <div class="invalid-feedback errorConfirmationPassword">
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary" id="simpan">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card -->

        </div><!-- end col -->
    </div>

    <script>
        $(document).ready(function() {
            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('ganti-password.update') }}",
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
                            if (response.errors.old_password) {
                                $('#old_password').addClass('is-invalid');
                                $('.errorOldPassword').html(response.errors.old_password);
                            } else {
                                $('#old_password').removeClass('is-invalid');
                                $('.errorOldPassword').html('');
                            }
                            if (response.errors.password) {
                                $('#password').addClass('is-invalid');
                                $('.errorPassword').html(response.errors.password);
                            } else {
                                $('#password').removeClass('is-invalid');
                                $('.errorPassword').html('');
                            }
                            if (response.errors.password_confirmation) {
                                $('#password_confirmation').addClass('is-invalid');
                                $('.errorConfirmationPassword').html(response.errors
                                    .password_confirmation);
                            } else {
                                $('#password_confirmation').removeClass('is-invalid');
                                $('.errorConfirmationPassword').html('');
                            }
                        } else {
                            if (response.error_password) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.error_password,
                                })
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: response.success,
                                }).then(function() {
                                    top.location.href =
                                        "{{ route('ganti-password.index') }}";
                                });
                            }
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
