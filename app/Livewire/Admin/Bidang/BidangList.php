<?php

namespace App\Livewire\Admin\Bidang;

use App\Models\Bidang;
use Livewire\Component;
use Livewire\WithPagination;

class BidangList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $search = '';
    public $bidang_id, $nama, $keterangan;
    public $selectedBidangId;

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
        $listBidang = Bidang::search($this->search)->orderBy('nama')->paginate(10);
        return view('livewire.admin.bidang.bidang-list', compact('listBidang'));
    }

    public function add()
    {
        $this->validate([
            'nama' => 'required|string|max:255|unique:bidang,nama',
            'keterangan' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.unique' => 'Nama sudah digunakan.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ]);
        try {
    
            Bidang::create([
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
        $bidang = Bidang::findOrFail($id);
        $this->bidang_id = $bidang->id;
        $this->nama = $bidang->nama;
        $this->keterangan = $bidang->keterangan;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required|string|max:255|unique:bidang,nama,' . $this->bidang_id,
            'keterangan' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.unique' => 'Nama sudah digunakan.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ]);
        try {
    
            $bidang = Bidang::findOrFail($this->bidang_id);
            $bidang->update([
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
        $this->selectedBidangId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $bidang = Bidang::findOrFail($this->selectedBidangId);
            $bidang->delete();
    
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
        $this->bidang_id = '';
        $this->nama = '';
        $this->keterangan = '';
        $this->resetErrorBag();
    }
}
