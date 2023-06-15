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
        Schema::create('homecare', function (Blueprint $table) {
            $table->id();
            $table->string('kode_homecare', 100)->nullable();
            $table->string('name', 100)->nullable();
            $table->unsignedBigInteger('bayar_id');
            $table->foreign('bayar_id')->references('id')->on('bayar');
            $table->unsignedBigInteger('kategori_id');
            $table->foreign('kategori_id')->references('id')->on('kategori');
            $table->unsignedBigInteger('poli_id');
            $table->foreign('poli_id')->references('id')->on('poli');
            $table->text('deskripsi');
            $table->string('foto')->default('default.png');
            $table->string('paket_obat')->nullable();
            $table->string('kso')->nullable();
            $table->string('jasa_medis_dokter')->nullable();
            $table->string('jasa_medis_perawat')->nullable();
            $table->string('jasa_rumah_sakit')->nullable();
            $table->string('menejemen')->nullable();
            $table->string('total_biaya_dokter')->nullable();
            $table->string('total_biaya_perawat')->nullable();
            $table->string('total_biaya_perawat_dokter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homecare');
    }
};
