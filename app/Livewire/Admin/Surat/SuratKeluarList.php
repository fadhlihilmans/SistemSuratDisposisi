<?php

namespace App\Livewire\Admin\Surat;

use App\Models\SuratKeluar;
use App\Models\KodeSurat;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SuratKeluarList extends Component
{
    use WithPagination, WithFileUploads;

    public $formDetail = false;
    public $surat_keluar_id;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false, $selectedId;
    public $search = '';

    public $kode_surat_id, $pegawai_id, $perihal, $no_surat, $tujuan, $file, $tanggal_pengajuan, $nip, $nama_lengkap, $status = 'menunggu';
    public $old_file;

    public $showFilterModal = false;
    public $filterStatus = '';
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';


    public function mount()
    {
        $pegawai = Pegawai::where('user_id', Auth::id())->first();
        if ($pegawai) {
            $this->pegawai_id = $pegawai->id;
            $this->nip = $pegawai->nip;
            $this->nama_lengkap = $pegawai->nama_lengkap;
        }

    }
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // $listSuratKeluar = SuratKeluar::with(['kodeSurat', 'pegawai'])->search($this->search)->latest()->paginate(10);
        $pegawai = Pegawai::where('id', $this->pegawai_id)->first();
        $kodeSurat = KodeSurat::orderBy('kode')->get();

        $query = SuratKeluar::with(['kodeSurat', 'pegawai'])->search($this->search);

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterTanggalAwal && $this->filterTanggalAkhir) {
            $query->whereBetween('tanggal_pengajuan', [$this->filterTanggalAwal, $this->filterTanggalAkhir]);
        }

        $listSuratKeluar = $query->latest()->paginate(10);

        return view('livewire.admin.surat.surat-keluar-list', compact('listSuratKeluar', 'kodeSurat', 'pegawai'));
    }

    public function add()
    {
        try {
            $this->validate([
                'kode_surat_id' => 'required|exists:kode_surat,id',
                'perihal' => 'required|string|max:255',
                'tujuan' => 'required|string|max:255',
                'tanggal_pengajuan' => 'required|date',
                'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,xls,xlsx',
            ],[
                'kode_surat_id.required' => 'Kode Surat wajib diisi',
                'perihal.required' => 'Perihal Surat wajib diisi',
                'tujuan.required' => 'Tujuan Surat wajib diisi',
                'tanggal_pengajuan.required' => 'Tanggal Pengajuan wajib diisi',
                'file.mimes' => 'File harus berupa pdf, doc, docx, jpg, jpeg, png, webp, xls, xlsx',
            ]);
    
            // $kode = KodeSurat::find($this->kode_surat_id)->kode;
            // $bulan = \Carbon\Carbon::parse($this->tanggal_pengajuan)->locale('id')->translatedFormat('F');
            // $tahun = date('Y', strtotime($this->tanggal_pengajuan));
            // $no_surat = "{$kode}/{$bulan}/" . Str::slug($this->perihal, '-') . "/{$tahun}";
    
            $fileName = null;
            if ($this->file) {
                $fileName = 'surat_keluar_' . Str::random(10) . '.' . $this->file->getClientOriginalExtension();
                $destination = public_path('surat/' . $fileName);
    
                if (!File::exists(public_path('surat'))) {
                    File::makeDirectory(public_path('surat'), 0755, true);
                }
    
                File::copy($this->file->getRealPath(), $destination);
            }
    
            SuratKeluar::create([
                'kode_surat_id' => $this->kode_surat_id,
                'pegawai_id' => $this->pegawai_id,
                'perihal' => $this->perihal,
                'tujuan' => $this->tujuan,
                'tanggal_pengajuan' => $this->tanggal_pengajuan,
                'no_surat' => $this->no_surat,
                'file' => $fileName,
                'status' => $this->status,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data Berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $this->formEdit = true;
        $this->formDetail = false;
        $data = SuratKeluar::findOrFail($id);

        $this->selectedId = $id;
        $this->kode_surat_id = $data->kode_surat_id;
        $this->pegawai_id = $data->pegawai_id;
        $this->perihal = $data->perihal;
        $this->tujuan = $data->tujuan;
        $this->no_surat = $data->no_surat;
        $this->tanggal_pengajuan = $data->tanggal_pengajuan;
        $this->status = $data->status;
        $this->old_file = $data->file;
    }

    public function update()
    {
        try {
            $this->validate([
                'kode_surat_id' => 'required|exists:kode_surat,id',
                'perihal' => 'required|string|max:255',
                'tujuan' => 'required|string|max:255',
                'tanggal_pengajuan' => 'required|date',
                'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,xls,xlsx',
            ],[
                'kode_surat_id.required' => 'Kode Surat wajib diisi',
                'perihal.required' => 'Perihal Surat wajib diisi',
                'tujuan.required' => 'Tujuan Surat wajib diisi',
                'tanggal_pengajuan.required' => 'Tanggal Pengajuan wajib diisi',   
                'file.mimes' => 'File harus berupa pdf, doc, docx, jpg, jpeg, png, webp, xls, xlsx',
            ]);
    
            $data = SuratKeluar::findOrFail($this->selectedId);
    
            $kode = KodeSurat::find($this->kode_surat_id)->kode;
            $bulan = \Carbon\Carbon::parse($this->tanggal_pengajuan)->locale('id')->translatedFormat('F');
            $tahun = date('Y', strtotime($this->tanggal_pengajuan));
            $no_surat = "{$kode}/{$bulan}/" . Str::slug($this->perihal, '-') . "/{$tahun}";
    
            $fileName = $this->old_file;
            if ($this->file) {
                if ($this->old_file && file_exists(public_path('surat/' . $this->old_file))) {
                    unlink(public_path('surat/' . $this->old_file));
                }
    
                $fileName = 'surat_keluar_' . Str::random(10) . '.' . $this->file->getClientOriginalExtension();
                $destination = public_path('surat/' . $fileName);
    
                if (!File::exists(public_path('surat'))) {
                    File::makeDirectory(public_path('surat'), 0755, true);
                }
    
                File::copy($this->file->getRealPath(), $destination);
            }
    
            $data->update([
                'kode_surat_id' => $this->kode_surat_id,
                'pegawai_id' => $this->pegawai_id,
                'perihal' => $this->perihal,
                'tujuan' => $this->tujuan,
                'tanggal_pengajuan' => $this->tanggal_pengajuan,
                'no_surat' => $this->no_surat,
                'file' => $fileName,
                'status' => $this->status,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data Berhasil diperbarui.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->selectedId = $id;
    }

    public function deleteConfirmed()
    {
        $data = SuratKeluar::findOrFail($this->selectedId);
        // if ($data->file && file_exists(public_path('surat/' . $data->file))) {
        //     unlink(public_path('surat/' . $data->file));
        // }
        $data->delete();

        $this->confirmingDelete = false;
        $this->dispatch('success-message', 'Data Berhasil dihapus.');
    }

    public function detail($id)
    {
        $this->resetForm(); // bersihkan form lain
        $this->formDetail = true;
        $this->surat_keluar_id = $id;
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
        $this->formEdit = false;
        $this->formDetail = false;
        $this->confirmingDelete = false;
        $this->selectedId = null;
        $this->kode_surat_id = '';
        $this->pegawai_id = '';
        $this->perihal = '';
        $this->tujuan = '';
        $this->no_surat = '';
        $this->file = null;
        $this->old_file = '';
        $this->tanggal_pengajuan = '';
        $this->status = 'menunggu';
    }
}
