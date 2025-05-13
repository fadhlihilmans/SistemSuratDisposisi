<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;

class AdminDashboard extends Component
{
    public $totalPegawai;
    public $totalSuratMasuk;
    public $totalSuratKeluar;
    public $totalSuratMasukMenunggu;
    public $totalSuratKeluarMenunggu;

    public $suratDisetujui;
    public $suratDitolak;
    public $suratMenunggu;
    public $totalSuratSaya;

    public $disposisiUntukSaya;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;

        $this->totalPegawai = Pegawai::count();
        $this->totalSuratMasuk = SuratMasuk::count();
        $this->totalSuratKeluar = SuratKeluar::count();
        
        $this->totalSuratMasukMenunggu = SuratMasuk::where('status', 'belum_disposisi')->count();
        $this->totalSuratKeluarMenunggu = SuratKeluar::where('status', 'menunggu')->count();



        if ($pegawaiId) {
            $this->suratMenunggu = SuratKeluar::where('pegawai_id', $pegawaiId)->where('status', 'menunggu')->count();
            $this->suratDisetujui = SuratKeluar::where('pegawai_id', $pegawaiId)->where('status', 'disetujui')->count();
            $this->suratDitolak = SuratKeluar::where('pegawai_id', $pegawaiId)->where('status', 'ditolak')->count();
            $this->totalSuratSaya = SuratKeluar::where('pegawai_id', $pegawaiId)->count();

            $this->disposisiUntukSaya = Disposisi::where('pegawai_tujuan', $pegawaiId)->count();
        } else {
            $this->suratDisetujui = 0;
            $this->suratDitolak = 0;
            $this->totalSuratSaya = 0;
            $this->disposisiUntukSaya = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard.admin-dashboard');
    }
}
