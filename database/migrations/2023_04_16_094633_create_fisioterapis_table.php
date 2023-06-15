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
        Schema::create('fisioterapi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_fisioterapi')->nullable();
            $table->string('name', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('harga')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fisioterapi');
    }
};
