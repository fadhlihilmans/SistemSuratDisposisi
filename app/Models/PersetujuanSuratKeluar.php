<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PersetujuanSuratKeluar extends Model
{
    use HasFactory;
    protected $table = 'persetujuan_surat_keluar';
    protected $guarded = [];

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
