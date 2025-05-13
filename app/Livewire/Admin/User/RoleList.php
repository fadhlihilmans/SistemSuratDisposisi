<?php

namespace App\Livewire\Admin\User;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class RoleList extends Component
{
    use WithPagination;

    public $formEdit = false;
    public $search = '';
    public $role_id, $name, $desc;
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
        $listRole = Role::search($this->search)->orderBy('name')->paginate(10);
        return view('livewire.admin.user.role-list', compact('listRole'));
    }

    public function edit($id)
    {
        $this->formEdit = true;
        $role = Role::findOrFail($id);
        $this->role_id = $role->id;
        $this->name = $role->name;
        $this->desc = $role->desc;
    }

    public function update()
    {
        try {
            $this->validate([
                'desc' => 'required|string|max:255|unique:roles,desc,' . $this->role_id,
            ], [
                'desc.required' => 'Deskripsi wajib diisi.',
                'desc.unique' => 'Deskripsi sudah digunakan.',
            ]);
    
            $role = Role::findOrFail($this->role_id);
            $role->update([
                'desc' => $this->desc,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
    public function resetForm()
    {
        $this->formEdit = false;
        $this->role_id = '';
        $this->name = '';
        $this->desc = '';
    }
}
