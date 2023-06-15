@extends('layouts.backend_main')
@section('title', 'Edit Rekam Medis')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">@yield('title')</h4>
                </div>
                <form action="{{ route('rekam-medis.update', $rekamMedis->id) }}" method="post" id="form">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <input type="hidden" name="id" id="id"
                                                value="{{ $rekamMedis->id }}">
                                            <label for="pasien" class="form-label">Pasien</label>
                                            <select class="form-control select2" data-toggle="select2" name="pasien"
                                                id="pasien">
                                                <option value="">-- Pilih Pasien --</option>
                                                @foreach ($pasien as $row)
                                                    <option value="{{ $row->id }}"
                                                        {{ $row->id == $rekamMedis->user_id ? 'selected' : '' }}>
                                                        {{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback errorPasien">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="dokter" class="form-label">Dokter</label>
                                            <input type="hidden" name="dokter" id="dokter"
                                                value="{{ $rekamMedis->dokter->id }}">
                                            <input type="text" name="dokter_name" id="dokter_name" class="form-control"
                                                value="{{ $rekamMedis->dokter->user->name }}" readonly>
                                            <div class="invalid-feedback errorDokter">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="dokter" class="form-label">Tanggal Kunjungan</label>
                                            <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan"
                                                class="form-control" value="{{ $rekamMedis->tanggal_kunjungan }}">
                                            <div class="invalid-feedback errorTanggalKunjungan">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="keluhan" class="form-label">Keluhan</label>
                                            <textarea name="keluhan" id="keluhan" rows="2" class="form-control">{{ $rekamMedis->keluhan }}</textarea>
                                            <div class="invalid-feedback errorKeluhan">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="diagnosa" class="form-label">Diagnosa</label>
                                            <textarea name="diagnosa" id="diagnosa" rows="2" class="form-control">{{ $rekamMedis->diagnosa }}</textarea>
                                            <div class="invalid-feedback errorDiagnosa">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="resep_obat" class="form-label">Resep Obat</label>
                                            <textarea name="resep_obat" id="resep_obat" rows="2" class="form-control">{{ $rekamMedis->resep_obat }}</textarea>
                                            <div class="invalid-feedback errorResepObat">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="catatan_tambahan" class="form-label">Catatan Tambahan</label>
                                            <textarea name="catatan_tambahan" id="catatan_tambahan" rows="2" class="form-control">{{ $rekamMedis->catatan_tambahan }}</textarea>
                                            <div class="invalid-feedback errorCatatanTambahan">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary mb-2"
                                        onclick="window.location='{{ route('rekam-medis.index') }}'">Kembali</button>
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

            $('#form').submit(function(e) {
                e.preventDefault();
                let id = $('#id').val();
                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ url('rekam-medis/"+id+"') }}",
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
                            if (response.errors.pasien) {
                                $('#pasien').addClass('is-invalid');
                                $('.errorPasien').html(response.errors.pasien);
                            } else {
                                $('#pasien').removeClass('is-invalid');
                                $('.errorPasien').html('');
                            }

                            if (response.errors.tanggal_kunjungan) {
                                $('#tanggal_kunjungan').addClass('is-invalid');
                                $('.errorTanggalKunjungan').html(response.errors
                                    .tanggal_kunjungan);
                            } else {
                                $('#tanggal_kunjungan').removeClass('is-invalid');
                                $('.errorTanggalKunjungan').html('');
                            }

                            if (response.errors.keluhan) {
                                $('#keluhan').addClass('is-invalid');
                                $('.errorKeluhan').html(response.errors.keluhan);
                            } else {
                                $('#keluhan').removeClass('is-invalid');
                                $('.errorKeluhan').html('');
                            }

                            if (response.errors.diagnosa) {
                                $('#diagnosa').addClass('is-invalid');
                                $('.errorDiagnosa').html(response.errors.diagnosa);
                            } else {
                                $('#diagnosa').removeClass('is-invalid');
                                $('.errorDiagnosa').html('');
                            }

                            if (response.errors.resep_obat) {
                                $('#resep_obat').addClass('is-invalid');
                                $('.errorResepObat').html(response.errors.resep_obat);
                            } else {
                                $('#resep_obat').removeClass('is-invalid');
                                $('.errorResepObat').html('');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil disimpan',
                            }).then(function() {
                                top.location.href = "{{ route('rekam-medis.index') }}";
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
