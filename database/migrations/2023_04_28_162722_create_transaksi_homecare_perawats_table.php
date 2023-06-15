<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_homecare_perawat', function (Blueprint $table) {
            $table->id();
            $table->uuid('pasien_id');
            $table->foreign('pasien_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('perawat_id');
            $table->foreign('perawat_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('homecare', 100);
            $table->text('riwayat_penyakit');
            $table->dateTime('waktu');
            $table->dateTime('waktu_selesai')->nullable();
            $table->char('provinsi_id', '10');
            $table->char('kabupaten_id', '10');
            $table->char('kecamatan_id', '10');
            $table->char('desa_id', '10');
            $table->integer('jarak')->default(0);
            $table->string('metode_pembayaran', '20');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('biaya_tambahan', 100)->default(0);
            $table->string('total_biaya', 100);
            $table->text('deskripsi_kegiatan')->nullable();
            $table->tinyInteger('status')->default('2')->comment('0 = Aktif, 1 = Pending, 2 = Tidak Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_homecare_perawat');
    }
};
