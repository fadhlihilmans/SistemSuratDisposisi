<?php

namespace App\Livewire\Admin\Surat;

use App\Models\Disposisi;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DisposisiList extends Component
{
    use WithPagination;

    public $search = '';
    public $formDetail = false;
    public $disposisi_id;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function detail($id)
    {
        $this->formDetail = true;
        $this->disposisi_id = $id;
    }

    public function resetForm()
    {
        $this->formDetail = false;
        $this->disposisi_id = null;
    }

    public function render()
    {
        $pegawaiId = Auth::user()?->pegawai?->id;

        $listDisposisi = Disposisi::with(['suratMasuk', 'bidangTujuan', 'pegawaiTujuan'])
            ->where('pegawai_tujuan', $pegawaiId)
            ->whereHas('suratMasuk', function ($q) {
                $q->where('status', 'disposisi');
            })
            ->search($this->search)
            ->latest()
            ->paginate(10);

        return view('livewire.admin.surat.disposisi-list', compact('listDisposisi'));
    }
}
