<?php

namespace App\Livewire\Admin\Jabatan;

use App\Models\Jabatan;
use Livewire\Component;
use Livewire\WithPagination;

class JabatanList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $search = '';
    public $jabatan_id, $nama, $keterangan;
    public $selectedJabatanId;

    // protected $paginationTheme = 'bootstrap';

    public function updatedSearch()
    {
        $this->resetPage(); 
    }

    public function render()
    {
        $listJabatan = Jabatan::search($this->search)->orderBy('nama')->paginate(10);
        return view('livewire.admin.jabatan.jabatan-list', compact('listJabatan'));
    }

    public function add()
    {
        try {
            $this->validate([
                'nama' => 'required|string|max:255|unique:jabatan,nama',
                'keterangan' => 'required',
            ], [
                'nama.required' => 'Nama wajib diisi.',
                'nama.unique' => 'Nama sudah digunakan.',
                'keterangan.required' => 'Keterangan wajib diisi.',
            ]);
    
            Jabatan::create([
                'nama' => $this->nama,
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
        $jabatan = Jabatan::findOrFail($id);
        $this->jabatan_id = $jabatan->id;
        $this->nama = $jabatan->nama;
        $this->keterangan = $jabatan->keterangan;
    }

    public function update()
    {
        try {
            $this->validate([
                'nama' => 'required|string|max:255|unique:jabatan,nama,' . $this->jabatan_id,
                'keterangan' => 'required',
            ], [
                'nama.required' => 'Nama wajib diisi.',
                'nama.unique' => 'Nama sudah digunakan.',
                'keterangan.required' => 'Keterangan wajib diisi.',
            ]);
    
            $jabatan = Jabatan::findOrFail($this->jabatan_id);
            $jabatan->update([
                'nama' => $this->nama,
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
        $this->selectedJabatanId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $jabatan = Jabatan::findOrFail($this->selectedJabatanId);
            $jabatan->delete();
    
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
        $this->jabatan_id = '';
        $this->nama = '';
        $this->keterangan = '';
    }
}
