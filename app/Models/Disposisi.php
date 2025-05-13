<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Disposisi extends Model
{
    use HasFactory;
    protected $table = 'disposisi';
    protected $guarded = [];

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('sifat', 'like', '%' . $keyword . '%')
                ->orWhere('tanggal_disposisi', 'like', '%' . $keyword . '%')
                ->orWhere('catatan', 'like', '%' . $keyword . '%')

                ->orWhereHas('suratMasuk', function ($sq) use ($keyword) {
                    $sq->where('no_surat', 'like', '%' . $keyword . '%')
                    ->orWhere('asal_surat', 'like', '%' . $keyword . '%')
                    ->orWhere('perihal', 'like', '%' . $keyword . '%');
                })

                ->orWhereHas('bidangTujuan', function ($bq) use ($keyword) {
                    $bq->where('nama', 'like', '%' . $keyword . '%');
                })

                ->orWhereHas('pegawaiTujuan', function ($kq) use ($keyword) {
                    $kq->where('nama_lengkap', 'like', '%' . $keyword . '%')
                    ->orWhere('nip', 'like', '%' . $keyword . '%');
                });
        });
    }


    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function bidangTujuan()
    {
        return $this->belongsTo(Bidang::class, 'bidang_tujuan');
    }

    public function pegawaiTujuan()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_tujuan');
    }

    public function getFormattedTanggalDisposisiAttribute()
    {
        return $this->tanggal_disposisi
            ? \Carbon\Carbon::parse($this->tanggal_disposisi)->locale('id')->translatedFormat('d F Y')
            : '-';
    }
}
