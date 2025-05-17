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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->references('id')->on('surat_masuk')->cascadeOnDelete();
            $table->foreignId('bidang_tujuan')->references('id')->on('bidang')->cascadeOnDelete()->nullable(); //tujuan bidang
            $table->foreignId('pegawai_tujuan')->references('id')->on('pegawai')->cascadeOnDelete()->nullable(); //tujuan pegawai
            $table->enum('sifat', ['segera','biasa','rahasia','sangat_rahasia','sangat_segera'])->default('biasa');
            $table->date('tanggal_disposisi');
            $table->text('catatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};
