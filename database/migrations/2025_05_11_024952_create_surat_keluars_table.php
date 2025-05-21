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
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kode_surat_id')->references('id')->on('kode_surat')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->references('id')->on('pegawai')->cascadeOnDelete(); //pengirim surat
            $table->string('no_surat')->nullable();
            $table->string('perihal');
            $table->string('tujuan');
            $table->string('file')->nullable();
            $table->date('tanggal_pengajuan');
            $table->enum('status', ['menunggu','disetujui','ditolak'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
