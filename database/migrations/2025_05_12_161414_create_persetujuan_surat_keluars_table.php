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
       Schema::create('persetujuan_surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_keluar_id')->references('id')->on('surat_keluar')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->references('id')->on('pegawai'); 
            $table->enum('status', ['disetujui', 'ditolak']);
            $table->text('catatan')->nullable(); 
            $table->timestamp('tanggal_persetujuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persetujuan_surat_keluar');
    }
};
