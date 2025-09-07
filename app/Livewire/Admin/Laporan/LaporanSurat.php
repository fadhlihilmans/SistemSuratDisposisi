<?php

namespace App\Livewire\Admin\Laporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Carbon\Carbon;

class LaporanSurat extends Component
{
    use WithPagination;

    public $search = '';
    public $tipeSurat = 'surat_masuk'; 
    
    public $status = '';
    public $tanggalAwal = '';
    public $tanggalAkhir = '';
    public $showPrintPreview = false;
    public $printData = [];
    
    protected $queryString = ['tipeSurat', 'status', 'tanggalAwal', 'tanggalAkhir', 'search'];

    public function mount()
    {
        $this->resetFilters();
        
        
        if (empty($this->tanggalAwal)) {
            $this->tanggalAwal = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        
        if (empty($this->tanggalAkhir)) {
            $this->tanggalAkhir = Carbon::now()->format('Y-m-d');
        }
    }

    public function updatedTipeSurat()
    {
        
        
        $this->status = '';
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedTanggalAwal()
    {
        if ($this->tanggalAwal && $this->tanggalAkhir && $this->tanggalAwal > $this->tanggalAkhir) {
            $this->tanggalAkhir = $this->tanggalAwal;
        }
        $this->resetPage();
    }

    public function updatedTanggalAkhir()
    {
        if ($this->tanggalAwal && $this->tanggalAkhir && $this->tanggalAkhir < $this->tanggalAwal) {
            $this->dispatch('failed-message', 'Tanggal akhir tidak boleh sebelum tanggal awal');
            $this->tanggalAkhir = $this->tanggalAwal;
        }
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->tipeSurat = 'surat_masuk';
        $this->tanggalAwal = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = Carbon::now()->format('Y-m-d');
        $this->resetPage();
    }

    public function printReport()
    {
        if ($this->tipeSurat === 'surat_masuk') {
            $query = $this->getSuratMasukQuery();
            $data = $query->get();
            
            $this->printData = [
                'tipe' => 'Surat Masuk',
                'periode' => Carbon::parse($this->tanggalAwal)->locale('id')->translatedFormat('d F Y') . ' - ' . 
                            Carbon::parse($this->tanggalAkhir)->locale('id')->translatedFormat('d F Y'),
                'status' => $this->getStatusLabel('surat_masuk', $this->status),
                'data' => $data
            ];
        } else {
            $query = $this->getSuratKeluarQuery();
            $data = $query->get();
            
            $this->printData = [
                'tipe' => 'Surat Keluar',
                'periode' => Carbon::parse($this->tanggalAwal)->locale('id')->translatedFormat('d F Y') . ' - ' . 
                            Carbon::parse($this->tanggalAkhir)->locale('id')->translatedFormat('d F Y'),
                'status' => $this->getStatusLabel('surat_keluar', $this->status),
                'data' => $data
            ];
        }
        
        $this->showPrintPreview = true;
    }

    public function closePrintPreview()
    {
        $this->showPrintPreview = false;
    }

    public function cetakPdf()
    {
        return redirect()->route('admin.laporan.pdf', [
            'tipe_surat' => $this->tipeSurat,
            'status' => $this->status,
            'tanggal_awal' => $this->tanggalAwal,
            'tanggal_akhir' => $this->tanggalAkhir,
        ]);
    }

    private function getStatusLabel($type, $status)
    {
        if (empty($status)) {
            return 'Semua Status';
        }
        
        if ($type === 'surat_masuk') {
            return $status === 'disposisi' ? 'Disposisi' : 'Belum Disposisi';
        } else {
            switch ($status) {
                case 'disetujui': return 'Disetujui';
                case 'ditolak': return 'Ditolak';
                case 'menunggu': return 'Menunggu';
                default: return 'Semua Status';
            }
        }
    }
    
    private function getSuratMasukQuery()
    {
        $query = SuratMasuk::with(['kodeSurat', 'disposisi'])
            ->search($this->search);
        
        
            
        if ($this->tanggalAwal && $this->tanggalAkhir) {
            $query->whereBetween('tanggal_masuk', [$this->tanggalAwal, $this->tanggalAkhir]);
        }
        
        
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        return $query->orderBy('tanggal_masuk', 'desc');
    }
    
    private function getSuratKeluarQuery()
    {
        $query = SuratKeluar::with(['kodeSurat', 'pegawai', 'persetujuan'])
            ->search($this->search);
        
        
            
        if ($this->tanggalAwal && $this->tanggalAkhir) {
            $query->whereBetween('tanggal_pengajuan', [$this->tanggalAwal, $this->tanggalAkhir]);
        }
        
        
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        return $query->orderBy('tanggal_pengajuan', 'desc');
    }

    public function render()
    {
        $data = [];
        
        if ($this->tipeSurat === 'surat_masuk') {
            $query = $this->getSuratMasukQuery();
            $data['surats'] = $query->paginate(10);
        } else {
            $query = $this->getSuratKeluarQuery();
            $data['surats'] = $query->paginate(10);
        }
        
        return view('livewire.admin.laporan.laporan-surat', $data);
    }
}
