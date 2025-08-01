<?php

namespace App\Livewire\Admin\Pegawai;

use App\Models\Pegawai;
use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Golongan;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class PegawaiList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $search = '';
    public $pegawai_id, $nama, $jabatan_id, $bidang_id, $golongan_id, $username, $email, $nama_lengkap, $nip,$tempat_lahir, $tanggal_lahir, $no_hp, $alamat;
    public $selectedPegawaiId;
    public $formDetail = false;
    public $pegawaiDetail;
    public $showFilterModal = false;
    public $filterBidang = '';
    public $filterJabatan = '';
    public $filterGolongan = '';


    // protected $paginationTheme = 'bootstrap';

    public function updatedSearch()
    {
        $this->resetPage(); 
    }

    public function render()
    {
        $query = Pegawai::with(['user', 'jabatan', 'bidang', 'golongan'])
                    ->search($this->search);

        if ($this->filterBidang) {
            $query->where('bidang_id', $this->filterBidang);
        }
        if ($this->filterJabatan) {
            $query->where('jabatan_id', $this->filterJabatan);
        }
        if ($this->filterGolongan) {
            $query->where('golongan_id', $this->filterGolongan);
        }

        $listPegawai = $query->latest()->paginate(10);

        $bidang = Bidang::orderBy('nama')->get(); 
        $jabatan = Jabatan::orderBy('nama')->get(); 
        $golongan = Golongan::orderBy('nama')->get(); 

        return view('livewire.admin.pegawai.pegawai-list', compact('listPegawai','bidang','jabatan','golongan'));
    }

    public function add()
    {
        $this->validate([
            'nip' => 'required',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|max:255|unique:users,email',
            'jabatan_id' => 'required',
            'bidang_id' => 'required',
            'golongan_id' => 'required',
            'nama_lengkap' => 'required|string|max:255|unique:pegawai,nama_lengkap',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'no_hp' => 'required|max:13',
            'alamat' => 'required',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
            'jabatan_id.required' => 'Jabatan wajib diisi.',
            'bidang_id.required' => 'Bidang wajib diisi.',
            'golongan_id.required' => 'golongan wajib diisi.',
            'nama_lengkap.required' => 'Nama wajib diisi.',
            'nama_lengkap.unique' => 'Nama sudah digunakan.',
            'tempat_lahir.required' => 'Tempat Lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggl Lahir wajib diisi.',
            'no_hp.required' => 'No HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);
        
        DB::beginTransaction();
        
        try {

            $user = User::create([
                'role_id' => '4',
                'username' => $this->username,
                'email' => $this->email,
                'password' => bcrypt('12345678'),
            ]);
    
            Pegawai::create([
                'user_id' => $user->id,
                'jabatan_id' => $this->jabatan_id,
                'bidang_id' => $this->bidang_id,
                'golongan_id' => $this->golongan_id,
                'nip' => $this->nip,
                'nama_lengkap' => $this->nama_lengkap,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'no_hp' => $this->no_hp,
                'alamat' => $this->alamat,
            ]);

            DB::commit();
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $this->formEdit = true;
        $pegawai = Pegawai::findOrFail($id);
        $this->pegawai_id = $pegawai->id;
        $this->jabatan_id = $pegawai->jabatan_id;
        $this->golongan_id = $pegawai->golongan_id;
        $this->bidang_id = $pegawai->bidang_id;
        $this->username = $pegawai->user->username;
        $this->email = $pegawai->user->email;
        $this->nip = $pegawai->nip;
        $this->nama_lengkap = $pegawai->nama_lengkap;
        $this->tempat_lahir = $pegawai->tempat_lahir;
        $this->tanggal_lahir = $pegawai->tanggal_lahir;
        $this->no_hp = $pegawai->no_hp;
        $this->alamat = $pegawai->alamat;
    }

    public function update()
    {
        $this->validate([
           'nip' => 'required',
           'username' => 'required|string|max:255|unique:users,username,' . $this->pegawai_id,
           'email' => 'required|string|max:255|unique:users,email,' . $this->pegawai_id,
           'jabatan_id' => 'required',
           'bidang_id' => 'required',
           'golongan_id' => 'required',
           'nama_lengkap' => 'required|string|max:255|unique:pegawai,nama_lengkap,' . $this->pegawai_id,
           'tempat_lahir' => 'required',
           'tanggal_lahir' => 'required',
           'no_hp' => 'required|max:13',
           'alamat' => 'required',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
            'jabatan_id.required' => 'Jabatan wajib diisi.',
            'bidang_id.required' => 'Bidang wajib diisi.',
            'golongan_id.required' => 'Golongan wajib diisi.',
            'nama_lengkap.required' => 'Nama wajib diisi.',
            'nama_lengkap.unique' => 'Nama sudah digunakan.',
            'tempat_lahir.required' => 'Tempat Lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggl Lahir wajib diisi.',
            'no_hp.required' => 'No HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);
        
        DB::beginTransaction();

        try {
    
            $pegawai = Pegawai::findOrFail($this->pegawai_id);

            $pegawai->user()->update([
                'username' => $this->username,
                'email' => $this->email,
            ]);

            $pegawai->update([
                'jabatan_id' => $this->jabatan_id,
                'bidang_id' => $this->bidang_id,
                'golongan_id' => $this->golongan_id,
                'nip' => $this->nip,
                'nama_lengkap' => $this->nama_lengkap,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'no_hp' => $this->no_hp,
                'alamat' => $this->alamat,  
            ]);

            DB::commit();
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data berhasil diubah.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedPegawaiId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $pegawai = Pegawai::findOrFail($this->selectedPegawaiId);
            $pegawai->delete();
    
            $this->dispatch('success-message', 'Data berhasil dihapus.');
            $this->confirmingDelete = false;
            
        }catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function detail($id)
    {
        $this->resetForm(); 
        $this->formDetail = true;
        $this->pegawaiDetail = Pegawai::with(['user', 'jabatan', 'bidang', 'golongan'])->findOrFail($id);
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
        $this->filterBidang = '';
        $this->filterJabatan = '';
        $this->filterGolongan = '';
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->formDetail = false;
        $this->pegawai_id = '';
        $this->jabatan_id = '';
        $this->bidang_id = '';
        $this->golongan_id = '';
        $this->username = '';
        $this->email = '';
        $this->nip = '';
        $this->nama_lengkap = '';
        $this->tempat_lahir = '';
        $this->tanggal_lahir = '';
        $this->no_hp = '';
        $this->alamat = '';
        $this->resetErrorBag();
    }
}
