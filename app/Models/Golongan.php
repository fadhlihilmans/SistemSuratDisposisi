<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Golongan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'golongan';
    protected $guarded = [];

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'LIKE', '%' . $keyword . '%')
                ->orWhere('keterangan', 'LIKE', '%' . $keyword . '%');
        });
    }
    
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
