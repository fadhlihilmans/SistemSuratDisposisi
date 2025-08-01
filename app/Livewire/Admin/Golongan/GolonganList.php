<?php

namespace App\Livewire\Admin\Golongan;

use App\Models\Golongan;
use Livewire\Component;
use Livewire\WithPagination;

class GolonganList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $search = '';
    public $golongan_id, $nama, $keterangan;
    public $selectedGolonganId;

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
        $listGolongan = Golongan::search($this->search)->orderBy('nama')->paginate(10);
        return view('livewire.admin.golongan.golongan-list', compact('listGolongan'));
    }

    public function add()
    {
        $this->validate([
            'nama' => 'required|string|max:255|unique:golongan,nama',
            'keterangan' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.unique' => 'Nama sudah digunakan.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ]);
        
        try {
    
            Golongan::create([
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
        $golongan = Golongan::findOrFail($id);
        $this->golongan_id = $golongan->id;
        $this->nama = $golongan->nama;
        $this->keterangan = $golongan->keterangan;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required|string|max:255|unique:golongan,nama,' . $this->golongan_id,
            'keterangan' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.unique' => 'Nama sudah digunakan.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ]);
        try {
    
            $golongan = Golongan::findOrFail($this->golongan_id);
            $golongan->update([
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
        $this->selectedGolonganId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $golongan = Golongan::findOrFail($this->selectedGolonganId);
            $golongan->delete();
    
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
        $this->golongan_id = '';
        $this->nama = '';
        $this->keterangan = '';
        $this->resetErrorBag();
    }
}
