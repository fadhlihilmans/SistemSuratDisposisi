<div>
    @if ($showEditModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-lg" role="document">
            <form wire:submit.prevent="updateProfile" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profil</h5>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label>Username</label>
                        <input type="text" wire:model.defer="username" class="form-control">
                        @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" wire:model.defer="email" class="form-control">
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Nama Lengkap</label>
                        <input type="text" wire:model.defer="nama_lengkap" class="form-control">
                        @error('nama_lengkap') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>NIP</label>
                        <input type="text" wire:model.defer="nip" class="form-control">
                        @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Tempat Lahir</label>
                        <input type="text" wire:model.defer="tempat_lahir" class="form-control">
                        @error('tempat_lahir') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tanggal Lahir</label>
                        <input type="date" wire:model.defer="tanggal_lahir" class="form-control">
                        @error('tanggal_lahir') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>No HP</label>
                        <input type="text" wire:model.defer="no_hp" class="form-control">
                        @error('no_hp') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Alamat</label>
                        <textarea wire:model.defer="alamat" class="form-control"></textarea>
                        @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" wire:target="updateProfile" wire:loading.remove class="btn btn-primary">Simpan Perubahan</button>
                    <button wire:target="updateProfile" wire:loading class="btn btn-primary"><i class="fas fa-spinner fa-spin"></i> Loading...</button>
                    <button type="button" wire:click="closeEditModal" class="btn btn-secondary">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <section class="section">
        <div class="section-header">
            <h1>Profil Saya</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <img alt="image" src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&size=100" class="rounded-circle profile-widget-picture">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Role</div>
                                    <div class="profile-widget-item-value">{{ ucfirst($user->role->name ?? '-') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-widget-description">
                            <div class="profile-widget-name">
                                {{ $user->username }} <div class="text-muted d-inline font-weight-normal">
                                    <div class="slash"></div> {{ $user->email }}
                                </div>
                            </div>
                            {{-- <p>Jika Anda ingin mengubah password, klik tombol di bawah.</p> --}}
                        </div>

                        <div class="card-footer text-center">
                            <button wire:click="openPasswordModal" class="btn btn-primary btn-lg btn-round">
                                Ubah Password
                            </button>
                        </div>
                    </div>
                </div>

                @if ($isEmployee)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Informasi Pegawai</h4>
                            <button wire:click="openEditModal" class="btn btn-primary btn-sm text-end"><i class="fas fa-pencil-alt"></i> Edit</button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Nama:</strong> {{ $user->pegawai->nama_lengkap ?? '-' }}</li>
                                <li class="list-group-item"><strong>NIP:</strong> {{ $user->pegawai->nip ?? '-' }}</li>
                                <li class="list-group-item"><strong>Jabatan:</strong> {{ $user->pegawai->jabatan->nama ?? '-' }}</li>
                                <li class="list-group-item"><strong>Bidang:</strong> {{ $user->pegawai->bidang->nama ?? '-' }}</li>
                                <li class="list-group-item"><strong>Golongan:</strong> {{ $user->pegawai->golongan->nama ?? '-' }}</li>
                                <li class="list-group-item"><strong>NIP:</strong> {{ $user->pegawai->tempat_lahir ?? '-' }}</li>
                                <li class="list-group-item"><strong>Tanggal Lahir:</strong> {{ $user->pegawai->formatted_tanggal_lahir ?? '-' }}</li>
                                <li class="list-group-item"><strong>No HP:</strong> {{ $user->pegawai->no_hp ?? '-' }}</li>
                                <li class="list-group-item"><strong>Alamat:</strong> {{ $user->pegawai->alamat ?? '-' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    @if ($showPasswordModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Password</h5>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updatePassword">
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input wire:model.defer="password" type="password" id="password" class="form-control">
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input wire:model.defer="password_confirmation" type="password" id="password_confirmation" class="form-control">
                            @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" wire:click="closePasswordModal" class="btn btn-secondary">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
