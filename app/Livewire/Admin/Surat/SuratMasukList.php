<?php

namespace App\Livewire\Admin\Surat;

use App\Models\SuratMasuk;
use App\Models\KodeSurat;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SuratMasukList extends Component
{
    use WithPagination, WithFileUploads;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false, $selectedId;
    public $search = '';

    public $kode_surat_id, $asal_surat, $tanggal_masuk, $perihal, $file, $no_surat,  $status = 'belum_disposisi';
    public $old_file;

    public $filterStatus = '';
    public $filterTanggalAwal = '';
    public $filterTanggalAkhir = '';
    public $showFilterModal = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = SuratMasuk::with('kodeSurat')->search($this->search);

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterTanggalAwal && $this->filterTanggalAkhir) {
            $query->whereBetween('tanggal_masuk', [$this->filterTanggalAwal, $this->filterTanggalAkhir]);
        }

        $listSuratMasuk = $query->latest()->paginate(10);

        return view('livewire.admin.surat.surat-masuk-list', compact('listSuratMasuk'));
    }


    public function add()
    {
        try {
            $this->validate([
                'kode_surat_id' => 'required|exists:kode_surat,id',
                'asal_surat' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255',
                'tanggal_masuk' => 'required|date',
                'perihal' => 'required|string|max:255',
                'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,csv,xls,xlsx',
            ],[
                'kode_surat_id.required' => 'Kode Surat wajib diisi',
                'asal_surat.required' => 'Asal Surat wajib diisi',
                'no_surat.required' => 'Nomor Surat wajib diisi',
                'tanggal_masuk.required' => 'Tanggal Masuk wajib diisi',
                'perihal.required' => 'Perihal Surat wajib diisi',
                'file.mimes' => 'File harus berupa pdf, doc, docx, jpg, jpeg, png, webp, xls, xlsx',
            ]);
    
            // $kode = KodeSurat::find($this->kode_surat_id)->kode;
            // $bulan = \Carbon\Carbon::parse($this->tanggal_masuk)->locale('id')->translatedFormat('F');
            // $tahun = date('Y', strtotime($this->tanggal_masuk));
            // $no_surat = "{$kode}/{$bulan}/" . Str::slug($this->perihal, '-') . "/{$tahun}";
    
            $fileName = null;
            if ($this->file) {
                $fileName = 'surat_masuk_' . Str::random(10) . '.' . $this->file->getClientOriginalExtension();
                $destination = public_path('surat/' . $fileName);
    
                if (!File::exists(public_path('surat'))) {
                    File::makeDirectory(public_path('surat'), 0755, true);
                }
    
                File::copy($this->file->getRealPath(), $destination);
            }
    
    
            SuratMasuk::create([
                'kode_surat_id' => $this->kode_surat_id,
                'asal_surat' => $this->asal_surat,
                'tanggal_masuk' => $this->tanggal_masuk,
                'perihal' => $this->perihal,
                'no_surat' => $this->no_surat,
                'file' => $fileName,
                'status' => 'belum_disposisi',
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $this->formEdit = true;
        $data = SuratMasuk::findOrFail($id);

        $this->selectedId = $id;
        $this->kode_surat_id = $data->kode_surat_id;
        $this->asal_surat = $data->asal_surat;
        $this->no_surat = $data->no_surat;
        $this->tanggal_masuk = $data->tanggal_masuk;
        $this->perihal = $data->perihal;
        $this->old_file = $data->file;
    }

    public function update()
    {
        try {
            $this->validate([
                'kode_surat_id' => 'required|exists:kode_surat,id',
                'asal_surat' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255',
                'tanggal_masuk' => 'required|date',
                'perihal' => 'required|string|max:255',
                'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,csv,xls,xlsx',
            ],[
                'kode_surat_id.required' => 'Kode Surat wajib diisi',
                'asal_surat.required' => 'Asal Surat wajib diisi',
                'no_surat.required' => 'Nomor Surat wajib diisi',
                'tanggal_masuk.required' => 'Tanggal Masuk wajib diisi',
                'perihal.required' => 'Perihal Surat wajib diisi',
                'file.mimes' => 'File harus berupa pdf, doc, docx, jpg, jpeg, png, webp, xls, xlsx',
            ]);
    
            $data = SuratMasuk::findOrFail($this->selectedId);
    
            // $kode = KodeSurat::find($this->kode_surat_id)->kode;
            // $bulan = \Carbon\Carbon::parse($this->tanggal_masuk)->locale('id')->translatedFormat('F');
            // $tahun = date('Y', strtotime($this->tanggal_masuk));
            // $no_surat = "{$kode}/{$bulan}/" . Str::slug($this->perihal, '-') . "/{$tahun}";
    
            if($this->old_file){
                $fileName = $this->old_file;
            }else{
                $fileName = null;
            }

            if ($this->file) {
                if ($this->old_file && file_exists(public_path('surat/' . $this->old_file))) {
                    unlink(public_path('surat/' . $this->old_file));
                }
    
                $fileName = 'surat_masuk_' . Str::random(10) . '.' . $this->file->getClientOriginalExtension();
                $destination = public_path('surat/' . $fileName);
    
                if (!File::exists(public_path('surat'))) {
                    File::makeDirectory(public_path('surat'), 0755, true);
                }
    
                File::copy($this->file->getRealPath(), $destination);
            }
    
            $data->update([
                'kode_surat_id' => $this->kode_surat_id,
                'asal_surat' => $this->asal_surat,
                'tanggal_masuk' => $this->tanggal_masuk,
                'perihal' => $this->perihal,
                'no_surat' => $this->no_surat,
                'file' => $fileName,
                'status' => 'belum_disposisi',
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data berhasil diupdate.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = true;
        $this->selectedId = $id;
    }

    public function deleteConfirmed()
    {
        $data = SuratMasuk::findOrFail($this->selectedId);
        $data->delete();
        $this->confirmingDelete = false;
        $this->dispatch('success-message', 'Data berhasil dihapus.');
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
        $this->confirmingDelete = false;
        $this->selectedId = null;
        $this->kode_surat_id = '';
        $this->asal_surat = '';
        $this->no_surat = '';
        $this->tanggal_masuk = '';
        $this->perihal = '';
        $this->file = null;
        $this->old_file = '';
        $this->status = 'belum_disposisi';
    }
}
