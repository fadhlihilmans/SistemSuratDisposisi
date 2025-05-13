<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $confirmingResetPassword = false;
    public $search = '';
    public $user_id, $username, $password, $email, $role_id;
    public $selectedUserId;

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
        $listUser = User::search($this->search)->orderBy('username')->paginate(10);
        $roles = Role::orderBy('name')->get();
        return view('livewire.admin.user.user-list', compact('listUser', 'roles'));
    }

    public function add()
    {
        try {
            $this->validate([
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|string|max:255|unique:users,email',
                'role_id' => 'required',
            ], [
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'email.required' => 'Email wajib diisi.',
                'email.unique' => 'Email sudah digunakan.',
                'role_id.required' => 'Role wajib diisi.',
            ]);
    
            User::create([
                'username' => $this->username,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'password' => bcrypt('12345678'),
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
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
    }

    public function update()
    {
        try {
            $this->validate([
                'username' => 'required|string|max:255|unique:users,username,' . $this->user_id,
                'email' => 'required|string|max:255|unique:users,email,' . $this->user_id,
                'role_id' => 'required',
            ], [
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'email.required' => 'Email wajib diisi.',
                'email.unique' => 'Email sudah digunakan.',
                'role_id.required' => 'Role wajib diisi.',
            ]);
    
            $user = User::findOrFail($this->user_id);
            $user->update([
                'username' => $this->username,
                'email' => $this->email,
                'role_id' => $this->role_id,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedUserId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $user = User::findOrFail($this->selectedUserId);
            $user->delete();
    
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
        $this->user_id = '';
        $this->username = '';
        $this->email = '';
        $this->role_id = '';
    }

    public function confirmResetPassword()
    {
        $this->confirmingResetPassword = true;
    }
    public function resetPasswordConfirmed()
    {
        // dd($this->password);
        try {
            $user = User::findOrFail($this->user_id);
            $user->password = bcrypt('12345678');
            $user->save();

            $this->confirmingResetPassword = false;
            $this->dispatch('success-message', 'Password berhasil direset.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
}
