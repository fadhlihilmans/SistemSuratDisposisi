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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kode_surat_id')->references('id')->on('kode_surat')->cascadeOnDelete();
            $table->string('asal_surat');
            $table->string('no_surat');
            $table->date('tanggal_masuk');
            $table->string('perihal');
            $table->string('file')->nullable();
            $table->enum('status', ['disposisi','belum_disposisi'])->default('belum_disposisi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
