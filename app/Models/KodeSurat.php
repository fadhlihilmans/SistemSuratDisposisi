<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class KodeSurat extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kode_surat';
    protected $guarded = [];
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('kode', 'LIKE', '%' . $keyword . '%')
                ->orWhere('keterangan', 'LIKE', '%' . $keyword . '%');
        });
    }
    public function suratMasuk()
    {
        return $this->hasMany(SuratMasuk::class, 'kode_surat_id');
    } 

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }
}
