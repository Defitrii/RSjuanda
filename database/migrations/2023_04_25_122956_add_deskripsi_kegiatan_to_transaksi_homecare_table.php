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
        Schema::table('transaksi_homecare', function (Blueprint $table) {
            $table->dateTime('waktu_selesai')->nullable()->after('waktu');
            $table->text('deskripsi_kegiatan')->nullable()->after('total_biaya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_homecare', function (Blueprint $table) {
            $table->dropColumn('deskripsi_kegiatan');
        });
    }
};
