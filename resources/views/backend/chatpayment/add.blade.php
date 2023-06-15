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
                <form method="post" id="form" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="pasien" class="form-label">Pasien</label>
                                        @if (auth()->user()->type != 'Pasien')
                                            <select name="pasien" id="pasien" class="form-control select2"
                                                data-toggle="select2">
                                                <option value="">-- Pilih Pasien --</option>
                                                @foreach ($pasien as $row)
                                                    <option value="{{ $row->user->id }}">{{ $row->user->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="pasien" value="{{ auth()->user()->id }}">
                                            <input type="text" name="nama" id="nama" class="form-control"
                                                value="{{ auth()->user()->name }}" readonly>
                                        @endif
                                        <div class="invalid-feedback errorPasien">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="dokter" class="form-label">Dokter</label>
                                        @if (auth()->user()->type != 'Dokter')
                                            <select name="dokter" id="dokter" class="form-control select2"
                                                data-toggle="select2">
                                                <option value="">-- Pilih Dokter --</option>
                                                @foreach ($dokter as $row)
                                                    <option value="{{ $row->id }}">{{ $row->user->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="pasien" value="{{ auth()->user()->id }}">
                                            <input type="text" name="nama" id="nama" class="form-control"
                                                value="{{ auth()->user()->name }}" readonly>
                                        @endif
                                        <div class="invalid-feedback errorDokter">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <label for="biaya_chat" class="form-label">Biaya yang akan dikeluarkan</label>
                                        <input type="number" name="biaya_chat" id="biaya_chat" class="form-control">
                                        <div class="invalid-feedback errorBiayaChat">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <label for="waktu_chat" class="form-label">Waktu Chat</label>
                                        <input type="text" name="waktu_chat" id="waktu_chat" class="form-control"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="mb-3">
                                        <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                                            class="form-control">
                                        <div class="invalid-feedback errorBuktiPembayaran">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary mb-2"
                                        onclick="window.location='{{ route('chatpayment.index') }}'">Kembali</button>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.2/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.2/vfs_fonts.js"></script>

    <script>
        $(document).ready(function() {
            var biayaChatInput = $('#biaya_chat');
            var waktuChatInput = $('#waktu_chat');

            // ketika nilai di input biaya chat berubah
            biayaChatInput.on('input', function() {
                var biayaChat = parseInt(biayaChatInput.val());
                if (!isNaN(biayaChat)) {
                    // hitung waktu chat berdasarkan biaya chat
                    var waktuChat = Math.ceil(biayaChat / 800); // 800 rupiah per menit
                    if (waktuChat > 0) {
                        var hours = Math.floor(waktuChat / 60);
                        var minutes = waktuChat % 60;
                        // format waktu chat dalam format HH:mm
                        var formattedWaktuChat = hours.toString().padStart(2, '0') + ':' + minutes
                            .toString().padStart(2, '0');
                        // set nilai input waktu chat
                        waktuChatInput.val(formattedWaktuChat);
                    } else {
                        // jika biaya chat kurang dari 800, maka waktu chat akan dianggap 1 menit
                        waktuChatInput.val('00:01');
                    }
                } else {
                    // jika input biaya chat tidak valid, kosongkan input waktu chat
                    waktuChatInput.val('');
                }
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    data: new FormData(this),
                    url: "{{ route('chatpayment.store') }}",
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
                            if (response.errors.pasien) {
                                $('#pasien').addClass('is-invalid');
                                $('.errorPasien').html(response.errors.pasien);
                            } else {
                                $('#pasien').removeClass('is-invalid');
                                $('.errorPasien').html('');
                            }
                            if (response.errors.dokter) {
                                $('#dokter').addClass('is-invalid');
                                $('.errorDokter').html(response.errors.dokter);
                            } else {
                                $('#dokter').removeClass('is-invalid');
                                $('.errorDokter').html('');
                            }
                            if (response.errors.biaya_chat) {
                                $('#biaya_chat').addClass('is-invalid');
                                $('.errorBiayaChat').html(response.errors.biaya_chat);
                            } else {
                                $('#biaya_chat').removeClass('is-invalid');
                                $('.errorBiayaChat').html('');
                            }
                            if (response.errors.bukti_pembayaran) {
                                $('#bukti_pembayaran').addClass('is-invalid');
                                $('.errorBuktiPembayaran').html(response.errors
                                    .bukti_pembayaran);
                            } else {
                                $('#bukti_pembayaran').removeClass('is-invalid');
                                $('.errorBuktiPembayaran').html('');
                            }
                        } else {
                            // Mendefinisikan string datetime
                            const datetimeString = response.chatpayment.created_at;

                            // Mengubah string datetime menjadi objek Date
                            const date = new Date(datetimeString);

                            // Mengubah objek Date menjadi objek Date tanpa waktu (format date)
                            const formatDate = (date) => {
                                const year = date.getFullYear();
                                const month = ('0' + (date.getMonth() + 1)).slice(-2);
                                const day = ('0' + date.getDate()).slice(-2);
                                return `${day}-${month}-${year}`;
                            };


                            var docDefinition = {
                                content: [{
                                        text: 'CHATPAYMENT RS.JUANDA',
                                        style: 'header'
                                    },
                                    {
                                        text: '-------------------------------------------------------',
                                        style: 'divider'
                                    },
                                    {
                                        text: 'Tanggal Transaksi: ' + formatDate(date),
                                        style: 'subheader'
                                    },
                                    {
                                        text: 'Nama: ' + response.user.name,
                                        margin: [0, 20, 0, 5]
                                    },
                                    {
                                        text: 'No Telepon: ' + response.user.no_telepon,
                                        margin: [0, 0, 0, 5]
                                    },
                                    {
                                        text: 'Dokter: ' + response.dokter.user.name,
                                        margin: [0, 0, 0, 5]
                                    },
                                    {
                                        text: 'Spesialis: ' + response.dokter.spesialis,
                                        margin: [0, 0, 0, 5]
                                    },
                                    {
                                        text: 'Biaya Chat: ' + response.biaya_chat,
                                        margin: [0, 0, 0, 20]
                                    },
                                    {
                                        text: 'Total: ' + response.biaya_chat,
                                        style: 'total'
                                    },
                                    {
                                        text: '-------------------------------------------------------',
                                        style: 'divider'
                                    },
                                    {
                                        text: 'Transaksi berhasil dilakukan, Terima kasih.',
                                        margin: [0, 20, 0, 0]
                                    }
                                ],
                                styles: {
                                    header: {
                                        fontSize: 16,
                                        bold: true,
                                        margin: [0, 0, 0, 10]
                                    },
                                    subheader: {
                                        fontSize: 14,
                                        bold: true,
                                        margin: [0, 10, 0, 10]
                                    },
                                    total: {
                                        fontSize: 16,
                                        bold: true,
                                        margin: [0, 10, 0, 0]
                                    },
                                    divider: {
                                        margin: [0, 5, 0, 5]
                                    }
                                }
                            };

                            pdfMake.createPdf(docDefinition).download(response.nameStruk);

                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan.',
                            }).then(function() {
                                top.location.href = "{{ route('chatpayment.index') }}";
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
