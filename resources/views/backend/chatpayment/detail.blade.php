@extends('layouts.backend_main')
@section('title', 'Detail Dokter')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">@yield('title')</h4>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td>Pasien</td>
                                <td>:</td>
                                <td>{{ $chatpayment->user->name }}</td>
                            </tr>
                            <tr>
                                <td>Dokter</td>
                                <td>:</td>
                                <td>{{ $chatpayment->dokter->user->name }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td>{{ $chatpayment->user->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td>Spesialis</td>
                                <td>:</td>
                                <td>{{ $chatpayment->dokter->spesialis }}</td>
                            </tr>
                            <tr>
                                <td>Pengalaman</td>
                                <td>:</td>
                                <td>{{ $chatpayment->dokter->pengalaman_tahun }} Tahun</td>
                            </tr>
                            <tr>
                                <td>Status Chat</td>
                                <td>:</td>
                                <td>{{ $chatpayment->status == '0' ? 'Aktif' : 'Tidak Aktif' }}</td>
                            </tr>
                            <tr>
                                <td>Waktu Selesai</td>
                                <td>:</td>
                                <td><span id="countdown">{{ $chatpayment->waktu_selesai }}</span></td>
                            </tr>
                        </table>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-secondary mb-2"
                                onclick="window.location='{{ route('chatpayment.index') }}'">Kembali</button>
                            @if ($selisihWaktu >= 0 && $selisihWaktu <= $waktuSelesai->diffInSeconds($waktuMulai))
                                @if (auth()->user()->type != 'Dokter')
                                    <a href="{{ url('chat-RSJuanda/' . $chatpayment->dokter->user->id) }}"
                                        class="btn btn-success mb-2" target="_blank">Chat</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <script>
        $(document).ready(function() {
            var countdown = $('#countdown').text().trim();
            var countdownDate = new Date(countdown).getTime();

            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countdownDate - now;

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                $('#countdown').text(hours + " Jam " + minutes + " Menit " + seconds + " Detik ");

                if (distance < 0) {
                    clearInterval(x);
                    $('#countdown').text("Waktu Chat Sudah Habis");
                }
            }, 1000);
        });
    </script>

@endsection
