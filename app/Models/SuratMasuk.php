<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SuratMasuk extends Model
{
    use HasFactory;
    protected $table = 'surat_masuk';
    protected $guarded = [];

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('asal_surat', 'LIKE', '%' . $keyword . '%')
                ->orWhere('perihal', 'LIKE', '%' . $keyword . '%')
                ->orWhere('no_surat', 'LIKE', '%' . $keyword . '%')
                ->orWhere('status', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tanggal_masuk', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('kodeSurat', function ($uq) use ($keyword) {
                    $uq->where('kode', 'like', '%' . $keyword . '%');
                });
        });
    }
    
    public function kodeSurat()
    {
        return $this->belongsTo(KodeSurat::class, 'kode_surat_id');
    }

    public function disposisi()
    {
        return $this->hasOne(Disposisi::class);
    }

    public function getStatusBadge()
    {
        $status = $this->status;
        if($status == 'disposisi'){
           return '<span class="badge badge-success">disposisi</span>';
        }else{
            return '<span class="badge badge-warning">belum disposisi</span>';
        }
    }

    public function getFormattedTanggalMasukAttribute()
    {
        return $this->tanggal_masuk
            ? \Carbon\Carbon::parse($this->tanggal_masuk)->locale('id')->translatedFormat('d F Y')
            : '-';
    }

}
