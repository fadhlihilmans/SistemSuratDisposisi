<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SuratKeluar extends Model
{
    use HasFactory;
    protected $table = 'surat_keluar';
    protected $guarded = [];

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('perihal', 'LIKE', '%' . $keyword . '%')
                ->orWhere('no_surat', 'LIKE', '%' . $keyword . '%')
                ->orWhere('status', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tujuan', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tanggal_pengajuan', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('kodeSurat', function ($uq) use ($keyword) {
                    $uq->where('kode', 'like', '%' . $keyword . '%');
                })->orWhereHas('pegawai', function ($uq) use ($keyword) {
                    $uq->where('nip', 'like', '%' . $keyword . '%')
                        ->orWhere('nama_lengkap', 'like', '%' . $keyword . '%');
                });
        });
    }
        
    public function kodeSurat()
    {
        return $this->belongsTo(KodeSurat::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function persetujuan()
    {
        return $this->hasMany(PersetujuanSuratKeluar::class);
    }

    public function getStatusBadge()
    {
        $status = $this->status;
        if($status == 'menunggu'){
           return '<span class="badge badge-warning">menunggu</span>';
        }else if($status == 'disetujui'){
            return '<span class="badge badge-success">disetujui</span>';
        }else{
            return '<span class="badge badge-danger">ditolak</span>';
        }
    }
    public function getFormattedTanggalPengajuanAttribute()
    {
        return $this->tanggal_pengajuan
            ? \Carbon\Carbon::parse($this->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y')
            : '-';
    }

}
