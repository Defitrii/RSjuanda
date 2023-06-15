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
        Schema::table('transaksi_fisioterapi', function (Blueprint $table) {
            $table->char('provinsi_id', 10)->nullable()->change();
            $table->char('kabupaten_id', 10)->nullable()->change();
            $table->char('kecamatan_id', 10)->nullable()->change();
            $table->char('desa_id', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_fisioterapi', function (Blueprint $table) {
            $table->char('provinsi_id', 10)->change();
            $table->char('kabupaten_id', 10)->change();
            $table->char('kecamatan_id', 10)->change();
            $table->char('desa_id', 10)->change();
        });
    }
};
