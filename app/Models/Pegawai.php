<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pegawai';
    protected $guarded = [];

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nip', 'like', '%' . $keyword . '%')
                ->orWhere('nama_lengkap', 'like', '%' . $keyword . '%')
                ->orWhere('tempat_lahir', 'like', '%' . $keyword . '%')
                ->orWhere('no_hp', 'like', '%' . $keyword . '%')
                ->orWhere('alamat', 'like', '%' . $keyword . '%')
                ->orWhereHas('user', function ($uq) use ($keyword) {
                    $uq->where('username', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('jabatan', function ($jq) use ($keyword) {
                    $jq->where('nama', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('bidang', function ($bq) use ($keyword) {
                    $bq->where('nama', 'like', '%' . $keyword . '%');
                });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }

    public function disposisi()
    {
        return $this->hasMany(Disposisi::class, 'tujuan_pegawai');
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }

    public function getFormattedTanggalLahirAttribute()
    {
        return $this->tanggal_lahir
            ? \Carbon\Carbon::parse($this->tanggal_lahir)->locale('id')->translatedFormat('d F Y')
            : '-';
    }


}
