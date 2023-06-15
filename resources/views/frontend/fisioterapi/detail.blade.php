@extends('layouts.frontend_main')
@section('title', 'Detail Perawat')
@section('content')
    <!-- detail section -->
    <div class="section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-12 col-lg-4">
                    <img src="{{ asset('storage/users-avatar/' . $perawat->user->avatar) }}" alt="">
                    <!-- Image source -->
                </div>

                <div class="col-12 col-lg-8">
                    <h6 class="font-family-tertiary font-small fw-normal uppercase">Hari Kerja :
                        {{ $perawat->hari }}</h6>
                    <h3>{{ $perawat->user->name }}</h3>

                    <h6 class="font-family-tertiary font-small fw-normal uppercase">
                        Jam Kerja : {{ $perawat->jam_masuk }} -
                        {{ $perawat->jam_pulang }}</h6>
                    <div class="d-inline-block text-yellow">
                        <!-- Tampilkan bintang berdasarkan rata-rata rating -->
                        @php
                            $averageRating = $averageRatings; // Contoh nilai rata-rata rating
                            $fullStars = floor($averageRating);
                            $halfStar = false;
                            if ($averageRating - $fullStars >= 0.25 && $averageRating - $fullStars <= 0.75) {
                                $halfStar = true;
                            }
                            $extraStar = false;
                            if ($averageRating - $fullStars > 0.75) {
                                $extraStar = true;
                            }
                        @endphp

                        <!-- Tampilkan bintang sesuai dengan logika -->
                        @for ($i = 0; $i < $fullStars; $i++)
                            <i class="stars bi bi-star-fill"></i>
                        @endfor

                        @if ($halfStar)
                            <i class="stars bi bi-star-half"></i>
                        @endif

                        @if ($extraStar)
                            <i class="stars bi bi-star-fill"></i>
                        @endif
                    </div>
                    <p>{{ $perawat->deskripsi }}</p>
                    <a class="button button-reveal-right-dark button-lg margin-top-10"
                        href="{{ route('chatpayment.index') }}"><i class="bi bi-arrow-right"></i><span>CHAT</span></a>

                </div>
            </div><!-- end row -->
        </div><!-- end container -->
    </div>
    <!-- end detail section -->
    <hr>
    <div class="section padding-top-0">
        <div class="container">
            <h3 class="fw-normal text-center margin-bottom-10">Ulasan</h3>
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row g-4">
                <div class="col-12 col-md-12">
                    <!-- Snippet -->
                    <form class="form-style-5" action="{{ route('frontend.fisioterapiRating') }}" method="post">
                        @csrf
                        <style>
                            .rating-container {
                                display: flex;
                                justify-content: center;
                            }

                            .star {
                                font-size: 40px;
                                margin: 5px;
                                margin-bottom: 20px;
                            }
                        </style>

                        <div class="rating-container">
                            <input type="hidden" name="rating" id="rating" required>
                            <i class="star bi bi-star-fill" data-star="1"></i>
                            <i class="star bi bi-star-fill" data-star="2"></i>
                            <i class="star bi bi-star-fill" data-star="3"></i>
                            <i class="star bi bi-star-fill" data-star="4"></i>
                            <i class="star bi bi-star-fill" data-star="5"></i>
                        </div>
                        <input type="hidden" name="perawat_id" id="perawat_id" value="{{ $perawat->id }}">
                        <textarea name="komen" id="komen" placeholder="Pesan" required></textarea>

                        <!-- end Snippet -->
                        <div class="text-end">
                            <button type="submit" class="button button-reveal-right-dark button-lg margin-top-10"
                                id="kirim"><i class="bi bi-arrow-right"></i><span>Kirim</span></button>
                        </div>
                    </form>

                </div>
            </div><!-- end row -->

        </div><!-- end container -->
    </div>
    <script>
        let stars = document.querySelectorAll('.star');
        let ratingInput = document.getElementById('rating');

        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                let starValue = this.getAttribute('data-star');
                for (let i = 0; i < stars.length; i++) {
                    if (i < starValue) {
                        stars[i].classList.add('text-yellow');
                    } else {
                        stars[i].classList.remove('text-yellow');
                    }
                }
            });

            star.addEventListener('mouseout', function() {
                stars.forEach(star => {
                    star.classList.remove('text-yellow');
                });
                let currentValue = ratingInput.value;
                for (let i = 0; i < stars.length; i++) {
                    if (i < currentValue) {
                        stars[i].classList.add('text-yellow');
                    }
                }
            });

            star.addEventListener('click', function() {
                let starValue = this.getAttribute('data-star');
                ratingInput.value = starValue;
            });
        });
    </script>


@endsection
