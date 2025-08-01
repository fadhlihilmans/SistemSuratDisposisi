<?php

namespace App\Livewire\Admin\Surat;

use App\Models\Pegawai;
use App\Models\SuratKeluar;
use App\Models\PersetujuanSuratKeluar;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersetujuanSuratKeluarAdd extends Component
{
    use WithPagination;

    public $formAdd = false, $formDetail = false, $confirmingDelete = false;
    public $search = '';
    public $selectedPersetujuanId;
    
    public $persetujuan_id;
    public $surat_keluar_id;
    public $pegawai_id;
    public $status = 'disetujui';
    public $catatan;
    public $tanggal_persetujuan;
    
    public $showSuratSelection = false;
    public $searchSurat = '';

    public $filterStatus = '';
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';
    public $showFilterModal = false;


    public function mount()
    {
        $this->tanggal_persetujuan = Carbon::now()->format('Y-m-d H:i:s');
        $this->pegawai_id = Auth::user()->pegawai->id;
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
        // $listPersetujuan = PersetujuanSuratKeluar::with(['suratKeluar', 'pegawai'])
        //     ->whereHas('suratKeluar', function($query) {
        //         $query->search($this->search);
        //     })
        //     ->orWhereHas('pegawai', function($query) {
        //         $query->where('nama_lengkap', 'LIKE', '%' . $this->search . '%')
        //             ->orWhere('nip', 'LIKE', '%' . $this->search . '%');
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);
        
        $query = PersetujuanSuratKeluar::with(['suratKeluar', 'pegawai']);

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterTanggalAwal && $this->filterTanggalAkhir) {
            $query->whereBetween('tanggal_persetujuan', [$this->filterTanggalAwal . ' 00:00:00', $this->filterTanggalAkhir . ' 23:59:59']);
        }

        $query->where(function ($q) {
            $q->whereHas('suratKeluar', function($query) {
                $query->search($this->search);
            })
            ->orWhereHas('pegawai', function($query) {
                $query->where('nama_lengkap', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('nip', 'LIKE', '%' . $this->search . '%');
            });
        });

        $listPersetujuan = $query->orderBy('created_at', 'desc')->paginate(10);


        $data = [
            'listPersetujuan' => $listPersetujuan
        ];
        
        if ($this->showSuratSelection) {
            $listSurat = SuratKeluar::search($this->searchSurat)
                ->where('status', 'menunggu')
                ->orderBy('tanggal_pengajuan', 'desc')
                ->paginate(10);
            $data['listSurat'] = $listSurat;
        }
        
        return view('livewire.admin.surat.persetujuan-surat-keluar-add', $data);
    }

    public function selectSurat($id)
    {
        $this->showSuratSelection = false;
        $this->formAdd = true;
        $this->surat_keluar_id = $id;
        $this->pegawai_id = Auth::user()->pegawai->id;
    }
    
    public function showSuratSelectionForm()
    {
        $this->resetForm();
        $this->showSuratSelection = true;
    }

    public function add()
    {
        $this->validate([
            'surat_keluar_id' => 'required|exists:surat_keluar,id',
            'pegawai_id' => 'required|exists:pegawai,id',
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string',
        ], [
            'surat_keluar_id.required' => 'Surat wajib dipilih.',
            'surat_keluar_id.exists' => 'Surat tidak ditemukan.',
            'pegawai_id.required' => 'Pegawai wajib dipilih.',
            'pegawai_id.exists' => 'Pegawai tidak ditemukan.',
            'status.required' => 'Status persetujuan wajib dipilih.',
            'status.in' => 'Status persetujuan tidak valid.',
        ]);

        DB::beginTransaction();

        try {
            
            PersetujuanSuratKeluar::create([
                'surat_keluar_id' => $this->surat_keluar_id,
                'pegawai_id' => $this->pegawai_id,
                'status' => $this->status,
                'catatan' => $this->catatan,
                'tanggal_persetujuan' => now(),
            ]);
            
            
            $surat = SuratKeluar::findOrFail($this->surat_keluar_id);
            $surat->update(['status' => $this->status]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data persetujuan berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function showDetail($id)
    {
        $this->formDetail = true;
        $persetujuan = PersetujuanSuratKeluar::with(['suratKeluar', 'pegawai'])->findOrFail($id);
        $this->persetujuan_id = $persetujuan->id;
        $this->surat_keluar_id = $persetujuan->surat_keluar_id;
        $this->pegawai_id = $persetujuan->pegawai_id;
        $this->status = $persetujuan->status;
        $this->catatan = $persetujuan->catatan;
        $this->tanggal_persetujuan = $persetujuan->tanggal_persetujuan;
    }

    public function edit($id)
    {
        $this->formAdd = true;
        $this->formDetail = false;
        $persetujuan = PersetujuanSuratKeluar::findOrFail($id);
        $this->persetujuan_id = $persetujuan->id;
        $this->surat_keluar_id = $persetujuan->surat_keluar_id;
        $this->pegawai_id = $persetujuan->pegawai_id;
        $this->status = $persetujuan->status;
        $this->catatan = $persetujuan->catatan;
        $this->tanggal_persetujuan = $persetujuan->tanggal_persetujuan;
    }

    public function update()
    {
        $this->validate([
            'surat_keluar_id' => 'required|exists:surat_keluar,id',
            'pegawai_id' => 'required|exists:pegawai,id',
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string',
        ], [
            'surat_keluar_id.required' => 'Surat wajib dipilih.',
            'surat_keluar_id.exists' => 'Surat tidak ditemukan.',
            'pegawai_id.required' => 'Pegawai wajib dipilih.',
            'pegawai_id.exists' => 'Pegawai tidak ditemukan.',
            'status.required' => 'Status persetujuan wajib dipilih.',
            'status.in' => 'Status persetujuan tidak valid.',
        ]);

        DB::beginTransaction();

        try {
    
            $persetujuan = PersetujuanSuratKeluar::findOrFail($this->persetujuan_id);
            $persetujuan->update([
                'status' => $this->status,
                'catatan' => $this->catatan,
            ]);
            
            $surat = SuratKeluar::findOrFail($this->surat_keluar_id);
            $surat->update(['status' => $this->status]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data persetujuan berhasil diubah.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedPersetujuanId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $persetujuan = PersetujuanSuratKeluar::findOrFail($this->selectedPersetujuanId);
            
            $suratKeluar = SuratKeluar::findOrFail($persetujuan->surat_keluar_id);
            
            $persetujuan->delete();
            
            
            $suratKeluar->update(['status' => 'menunggu']);
    
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'Data persetujuan berhasil dihapus.');
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
        $this->filterStatus = '';
        $this->filterTanggalAwal = '';
        $this->filterTanggalAkhir = '';
    }

    public function updated($property)
    {
        if ($this->filterTanggalAwal && $this->filterTanggalAkhir && $this->filterTanggalAkhir < $this->filterTanggalAwal) {
            $this->dispatch('failed-message', 'Tanggal akhir tidak boleh sebelum tanggal awal');
            $this->filterTanggalAkhir = '';
        }
    }


    public function resetForm()
    {
        $this->formAdd = false;
        $this->formDetail = false;
        $this->showSuratSelection = false;
        $this->persetujuan_id = '';
        $this->surat_keluar_id = '';
        $this->pegawai_id = '';
        $this->status = 'disetujui';
        $this->catatan = '';
        $this->tanggal_persetujuan = Carbon::now()->format('Y-m-d H:i:s');
        $this->resetErrorBag();
        // $this->resetValidation();
    }
}