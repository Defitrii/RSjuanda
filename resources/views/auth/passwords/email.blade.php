@extends('layouts.auth_app')
@section('title', 'Reset Password')
@section('content')

    {{-- @if (session('message'))
        <div class="alert alert-danger">{{ session('message') }}</div>
    @endif --}}

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

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input class="form-control @error('email') is-invalid @enderror" type="email" id="email"
                    name="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-0 text-center">
                <button class="btn btn-primary" type="submit"> Kirim Email </button>
            </div>
        </form>
    </div>
    <!-- end card-body -->
    <div class="row">
        <div class="col-12 text-center">
            <p class="text-muted">Kembali ke ? <a href="{{ route('login') }}" class="text-muted ms-1"><b>Login</b></a>
            </p>
        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection
