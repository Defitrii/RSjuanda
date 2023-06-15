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
        Schema::table('users', function (Blueprint $table) {
            $table->char('provinsi_id', '10')->after('address')->nullable();
            $table->char('kabupaten_id', '10')->after('provinsi_id')->nullable();
            $table->char('kecamatan_id', '10')->after('kabupaten_id')->nullable();
            $table->char('desa_id', '10')->after('kecamatan_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('provinsi_id');
            $table->dropColumn('kabupaten_id');
            $table->dropColumn('kecamatan_id');
            $table->dropColumn('desa_id');
        });
    }
};
