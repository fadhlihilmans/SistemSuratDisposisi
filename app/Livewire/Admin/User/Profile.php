<?php

namespace App\Livewire\Admin\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;

class Profile extends Component
{
    public $username, $email;
    public $nama_lengkap, $nip, $tempat_lahir, $tanggal_lahir, $no_hp, $alamat;
    
    public $user;
    public $isEmployee = false;
    public $showEditModal = false;
    public $showPasswordModal = false;

    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'password' => ['required', 'confirmed', 'min:8'],
        'password_confirmation' => ['required']
    ];

    protected $messages = [
        'password.required' => 'Password tidak boleh kosong',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'password.min' => 'Password minimal 8 karakter',
        'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong',
    ];

    public function mount()
    {
        $this->getData();
    }

    public function getData()
    {
        $this->user = User::with(['role', 'pegawai.jabatan', 'pegawai.bidang', 'pegawai.golongan'])
            ->find(Auth::id());

        $this->isEmployee = optional($this->user->pegawai)->exists ?? false;
    }

    public function openPasswordModal()
    {
        $this->resetValidation();
        $this->password = '';
        $this->password_confirmation = '';
        $this->showPasswordModal = true;
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
    }

    public function updatePassword()
    {
        $this->validate();

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->closePasswordModal();
        $this->dispatch('success-message', 'Password berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.admin.user.profile');
    }

    public function openEditModal()
    {
        $this->resetValidation();
        $this->user = Auth::user();

        $this->username = $this->user->username;
        $this->email = $this->user->email;

        if ($this->user->pegawai) {
            $this->nama_lengkap = $this->user->pegawai->nama_lengkap;
            $this->nip = $this->user->pegawai->nip;
            $this->tempat_lahir = $this->user->pegawai->tempat_lahir;
            $this->tanggal_lahir = $this->user->pegawai->tanggal_lahir;
            $this->no_hp = $this->user->pegawai->no_hp;
            $this->alamat = $this->user->pegawai->alamat;
        }

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    public function updateProfile()
    {
        $this->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|max:50',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $this->user->update([
            'username' => $this->username,
            'email' => $this->email,
        ]);

        $this->user->pegawai->update([
            'nama_lengkap' => $this->nama_lengkap,
            'nip' => $this->nip,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
        ]);

        $this->closeEditModal();
        $this->getData();
        $this->dispatch('success-message', 'Profil berhasil diperbarui.');
    }

}
