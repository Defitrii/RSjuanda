@extends('layouts.auth_app')
@section('title', 'Login')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-5">
            <div class="card">
                <!-- Logo -->
                <div class="card-header pt-3 pb-3 text-center bg-rs">
                    <a href="index.html">
                        <span><img src="{{ asset('assets') }}/images/juanda_header.png" alt="" height="40"></span>
                    </a>
                </div>

                <div class="card-body p-3">
                    <div class="text-center w-75 m-auto">
                        <h4 class="text-dark-50 text-center pb-0 fw-bold">Log In</h4>
                    </div>

                    @if (session('message'))
                        <div class="alert alert-danger">{{ session('message') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Email / No Telepon</label>
                            <input class="form-control @error('username') is-invalid @enderror" type="text"
                                id="username" name="username" autofocus>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('password.request') }}" class="text-muted float-end"><small>Lupa
                                    password?</small></a>
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                        </div>

                        <div class="mb-0 text-center">
                            <button class="btn btn-primary" type="submit"> Log In </button>
                        </div>
                    </form>
                </div>
                <!-- end card-body -->
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted">Tidak punya akun? <a href="{{ route('register') }}"
                                class="text-muted ms-1"><b>Register</b></a></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
