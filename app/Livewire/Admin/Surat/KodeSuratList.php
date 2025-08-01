<?php

namespace App\Livewire\Admin\Surat;

use App\Models\KodeSurat;
use Livewire\Component;
use Livewire\WithPagination;

class KodeSuratList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $search = '';
    public $kode_surat_id, $kode, $keterangan;
    public $selectedKodeSuratId;

    // protected $paginationTheme = 'bootstrap';

    public function updatedSearch()
    {
        $this->resetPage(); 
    }

    public function refresh()
    {
        $this->resetPage();
    }

    public function render()
    {
        $listKodeSurat = KodeSurat::search($this->search)->orderBy('kode')->paginate(10);
        return view('livewire.admin.surat.kode-surat-list', compact('listKodeSurat'));
    }

    public function add()
    {
        $this->validate([
            'kode' => 'required|string|max:255|unique:kode_surat,kode',
            'keterangan' => 'required',
        ], [
            'kode.required' => 'kode wajib diisi.',
            'kode.unique' => 'kode sudah digunakan.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ]);

        try {
            KodeSurat::create([
                'kode' => $this->kode,
                'keterangan' => $this->keterangan,
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
        $kode_surat = KodeSurat::findOrFail($id);
        $this->kode_surat_id = $kode_surat->id;
        $this->kode = $kode_surat->kode;
        $this->keterangan = $kode_surat->keterangan;
    }

    public function update()
    {
        $this->validate([
            'kode' => 'required|string|max:255|unique:kode_surat,kode,' . $this->kode_surat_id,
            'keterangan' => 'required',
        ], [
            'kode.required' => 'kode wajib diisi.',
            'kode.unique' => 'kode sudah digunakan.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ]);
    
        try {
            $kode_surat = KodeSurat::findOrFail($this->kode_surat_id);
            $kode_surat->update([
                'kode' => $this->kode,
                'keterangan' => $this->keterangan,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedKodeSuratId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $kode_surat = KodeSurat::findOrFail($this->selectedKodeSuratId);
            $kode_surat->delete();
    
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'Data berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->kode_surat_id = '';
        $this->kode = '';
        $this->keterangan = '';
        $this->resetErrorBag();
    }
}
