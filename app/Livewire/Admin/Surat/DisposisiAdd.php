<?php

namespace App\Livewire\Admin\Surat;

use App\Services\FonnteService;
use Illuminate\Support\Facades\App;
use App\Models\Bidang;
use App\Models\Disposisi;
use App\Models\Pegawai;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class DisposisiAdd extends Component
{
    use WithPagination;

    public $formAdd = false, $formDetail = false, $confirmingDelete = false;
    public $search = '';
    public $selectedDisposisiId;
    
    public $disposisi_id;
    public $surat_masuk_id;
    public $bidang_tujuan;
    public $pegawai_tujuan;
    public $sifat = 'biasa';
    public $tanggal_disposisi;
    public $catatan;
    
    public $showSuratSelection = false;
    public $searchSurat = '';
    
    public $filterSifat = '';
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';
    public $showFilterModal = false;

    public $pegawaiList = [];

    public function mount()
    {
        $this->tanggal_disposisi = Carbon::now()->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->resetPage(); 
    }

    public function updatedSearchSurat()
    {
        $this->resetPage();
    }

    public function refresh()
    {
        $this->resetPage();
    }

    public function render()
    {
        // $listDisposisi = Disposisi::search($this->search)->orderBy('created_at', 'desc')->paginate(10);
        
        $query = Disposisi::search($this->search);

        if ($this->filterSifat) {
            $query->where('sifat', $this->filterSifat);
        }

        if ($this->filterTanggalAwal && $this->filterTanggalAkhir) {
            $query->whereBetween('tanggal_disposisi', [$this->filterTanggalAwal, $this->filterTanggalAkhir]);
        }

        $listDisposisi = $query->orderBy('created_at', 'desc')->paginate(10);

        $data = [
            'listDisposisi' => $listDisposisi
        ];
        
        if ($this->showSuratSelection) {
            $listSurat = SuratMasuk::search($this->searchSurat)
                ->where('status', 'belum_disposisi')
                ->orderBy('tanggal_masuk', 'desc')
                ->paginate(10);
            $data['listSurat'] = $listSurat;
        }
        
        if ($this->formAdd) {
            $listBidang = Bidang::orderBy('nama')->get();
            $data['listBidang'] = $listBidang;
        }
        
        return view('livewire.admin.surat.disposisi-add', $data);
    }

    public function selectSurat($id)
    {
        $this->showSuratSelection = false;
        $this->formAdd = true;
        $this->surat_masuk_id = $id;
        $this->bidang_tujuan = null;
        $this->pegawai_tujuan = null;
        $this->pegawaiList = [];
    }
    
    public function showSuratSelectionForm()
    {
        $this->resetForm();
        $this->showSuratSelection = true;
    }

    public function updatedBidangTujuan()
    {
        $this->pegawai_tujuan = null;
        if ($this->bidang_tujuan) {
            $this->pegawaiList = Pegawai::where('bidang_id', $this->bidang_tujuan)
                ->orderBy('nama_lengkap')
                ->get();
        } else {
            $this->pegawaiList = [];
        }
    }

    public function add()
    {
        $this->validate([
            'surat_masuk_id' => 'required|exists:surat_masuk,id',
            'bidang_tujuan' => 'required|exists:bidang,id',
            'pegawai_tujuan' => 'required|exists:pegawai,id',
            'sifat' => 'required',
            'tanggal_disposisi' => 'required|date',
            'catatan' => 'required',
        ], [
            'surat_masuk_id.required' => 'Surat wajib dipilih.',
            'surat_masuk_id.exists' => 'Surat tidak ditemukan.',
            'bidang_tujuan.required' => 'Bidang tujuan wajib dipilih.',
            'bidang_tujuan.exists' => 'Bidang tujuan tidak ditemukan.',
            'pegawai_tujuan.required' => 'Pegawai tujuan wajib dipilih.',
            'pegawai_tujuan.exists' => 'Pegawai tujuan tidak ditemukan.',
            'sifat.required' => 'Sifat disposisi wajib dipilih.',
            'tanggal_disposisi.required' => 'Tanggal disposisi wajib diisi.',
            'tanggal_disposisi.date' => 'Format tanggal tidak valid.',
            'catatan.required' => 'Catatan wajib diisi.',
        ]);

        DB::beginTransaction();

        try {
            
            Disposisi::create([
                'surat_masuk_id' => $this->surat_masuk_id,
                'bidang_tujuan' => $this->bidang_tujuan,
                'pegawai_tujuan' => $this->pegawai_tujuan,
                'sifat' => $this->sifat,
                'tanggal_disposisi' => $this->tanggal_disposisi,
                'catatan' => $this->catatan,
            ]);
            
            $surat = SuratMasuk::findOrFail($this->surat_masuk_id);
            $surat->update(['status' => 'disposisi']);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function showDetail($id)
    {
        $this->formDetail = true;
        $disposisi = Disposisi::with(['suratMasuk', 'bidangTujuan', 'pegawaiTujuan'])->findOrFail($id);
        $this->disposisi_id = $disposisi->id;
        $this->surat_masuk_id = $disposisi->surat_masuk_id;
        $this->bidang_tujuan = $disposisi->bidang_tujuan;
        $this->pegawai_tujuan = $disposisi->pegawai_tujuan;
        $this->sifat = $disposisi->sifat;
        $this->tanggal_disposisi = $disposisi->tanggal_disposisi;
        $this->catatan = $disposisi->catatan;
    }

    public function edit($id)
    {
        $this->formAdd = true;
        $this->formDetail = false;
        $disposisi = Disposisi::findOrFail($id);
        $this->disposisi_id = $disposisi->id;
        $this->surat_masuk_id = $disposisi->surat_masuk_id;
        $this->bidang_tujuan = $disposisi->bidang_tujuan;
        $this->sifat = $disposisi->sifat;
        $this->tanggal_disposisi = $disposisi->tanggal_disposisi;
        $this->catatan = $disposisi->catatan;
        
        $this->updatedBidangTujuan();
        $this->pegawai_tujuan = $disposisi->pegawai_tujuan;
    }

    public function update()
    {
        $this->validate([
            'surat_masuk_id' => 'required|exists:surat_masuk,id',
            'bidang_tujuan' => 'required|exists:bidang,id',
            'pegawai_tujuan' => 'required|exists:pegawai,id',
            'sifat' => 'required',
            'tanggal_disposisi' => 'required|date',
            'catatan' => 'required',
        ], [
            'surat_masuk_id.required' => 'Surat wajib dipilih.',
            'surat_masuk_id.exists' => 'Surat tidak ditemukan.',
            'bidang_tujuan.required' => 'Bidang tujuan wajib dipilih.',
            'bidang_tujuan.exists' => 'Bidang tujuan tidak ditemukan.',
            'pegawai_tujuan.required' => 'Pegawai tujuan wajib dipilih.',
            'pegawai_tujuan.exists' => 'Pegawai tujuan tidak ditemukan.',
            'sifat.required' => 'Sifat disposisi wajib dipilih.',
            'tanggal_disposisi.required' => 'Tanggal disposisi wajib diisi.',
            'tanggal_disposisi.date' => 'Format tanggal tidak valid.',
            'catatan.required' => 'Catatan wajib diisi.',
        ]);

        DB::beginTransaction();

        try {
            $disposisi = Disposisi::findOrFail($this->disposisi_id);
            $disposisi->update([
                'bidang_tujuan' => $this->bidang_tujuan,
                'pegawai_tujuan' => $this->pegawai_tujuan,
                'sifat' => $this->sifat,
                'tanggal_disposisi' => $this->tanggal_disposisi,
                'catatan' => $this->catatan,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data berhasil diubah.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedDisposisiId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $disposisi = Disposisi::findOrFail($this->selectedDisposisiId);
            
            $suratMasuk = SuratMasuk::findOrFail($disposisi->surat_masuk_id);
            
            $disposisi->delete();
            
            $suratMasuk->update(['status' => 'belum_disposisi']);
    
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'Data berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function openFilterModal()
    {
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
    }

    public function resetFilters()
    {
        $this->filterSifat = '';
        $this->filterTanggalAwal = '';
        $this->filterTanggalAkhir = '';
    }

    public function updated($property)
    {
        if ($this->filterTanggalAwal && $this->filterTanggalAkhir && $this->filterTanggalAkhir < $this->filterTanggalAwal) {
            $this->dispatch('failed-message', 'Tanggal akhir tidak boleh lebih awal dari tanggal awal.');
            $this->filterTanggalAkhir = '';
        }
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formDetail = false;
        $this->showSuratSelection = false;
        $this->disposisi_id = '';
        $this->surat_masuk_id = '';
        $this->bidang_tujuan = '';
        $this->pegawai_tujuan = '';
        $this->sifat = 'biasa';
        $this->tanggal_disposisi = Carbon::now()->format('Y-m-d');
        $this->catatan = '';
        $this->pegawaiList = [];
        $this->resetErrorBag();
    }

    public function share($id)
    {
        $disposisi = Disposisi::with(['suratMasuk', 'pegawaiTujuan'])->findOrFail($id);
        $pegawai = $disposisi->pegawaiTujuan;
        $surat = $disposisi->suratMasuk;

        if (!$pegawai || !$pegawai->no_hp) {
            $this->dispatch('failed-message', 'Nomor HP pegawai tidak tersedia.');
            return;
        }

        $url = config('app.url') . '/surat/' . $surat->file;
        $tanggal = \Carbon\Carbon::parse($disposisi->tanggal_disposisi)->locale('id')->translatedFormat('d F Y');

        $message = 
            // "{$url}\n\n" .
            "📄 *DISPOSISI SURAT MASUK*\n\n" .
            "Halo *{$pegawai->nama_lengkap}*,\n\n" .
            "Anda menerima disposisi surat dengan detail:\n\n" .
            "• Perihal: {$surat->perihal}\n" .
            "• Tanggal Disposisi: {$tanggal}\n" .
            "• Sifat: {$disposisi->sifat}\n\n" .
            // "• File Surat:\n {$url}\n\n" .
            // "• File Surat:\n {$url}" .
            "File Surat:\n" .
            "{$url}\n\n" .
            "Silakan segera ditindaklanjuti.\n\n" .
            "Terima kasih."
        ;


        $fonnte = App::make(FonnteService::class);
        $sent = $fonnte->sendMessage($pegawai->no_hp, $message);

        if ($sent) {
            $this->dispatch('success-message', 'Notifikasi WA berhasil dikirim.');
        } else {
            $this->dispatch('failed-message', 'Gagal mengirim notifikasi WA.');
        }
    }
}