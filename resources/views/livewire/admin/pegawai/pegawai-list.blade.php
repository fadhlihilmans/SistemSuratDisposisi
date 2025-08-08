<div>

@if ($formAdd || $formEdit)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{ $formAdd ? 'Tambah Pegawai' : 'Edit Pegawai' }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Form Pegawai</h4></div>
                    <div class="card-body">
                        <form wire:submit.prevent="{{ $formAdd ? 'add' : 'update' }}" autocomplete="off">
                            
                            <!-- Nama Lengkap -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Lengkap</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" wire:model.defer="nama_lengkap">
                                    @error('nama_lengkap') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- NIP -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">NIP</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="number" class="form-control" wire:model.defer="nip">
                                    @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- email -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" wire:model.defer="email">
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- username -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">username</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" wire:model.defer="username">
                                    @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Golongan -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Golongan</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control selectric" wire:model.defer="golongan_id">
                                        <option value="">-- Pilih Golongan --</option>
                                        @foreach ($golongan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('golongan_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Jabatan -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jabatan</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control selectric" wire:model.defer="jabatan_id">
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach ($jabatan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('jabatan_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Bidang -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Bidang</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control selectric" wire:model.defer="bidang_id">
                                        <option value="">-- Pilih Bidang --</option>
                                        @foreach ($bidang as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('bidang_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Tempat Lahir -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat Lahir</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" wire:model.defer="tempat_lahir">
                                    @error('tempat_lahir') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggal Lahir</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="date" class="form-control" wire:model.defer="tanggal_lahir">
                                    @error('tanggal_lahir') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- No HP -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">No HP</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" wire:model.defer="no_hp">
                                    @error('no_hp') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- keterangan -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">alamat</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea class="form-control" wire:model.defer="alamat" rows="5"></textarea>
                                    @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Info password -->
                            @if ($formAdd)
                                <div class="form-group row mb-2">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <p class="text-muted">Password default: <code>12345678</code></p>
                                    </div>
                                </div>
                            @endif

                            <!-- Tombol -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button wire:loading.remove wire:target="add,update" type="submit" class="btn btn-primary">
                                        {{ $formAdd ? 'Tambah' : 'Update' }}
                                    </button>
                                    <button wire:loading wire:target="add,update" class="btn btn-primary">
                                        Loading ...
                                    </button>
                                    <button wire:click="resetForm" type="button" class="btn btn-secondary">Kembali</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if ($formDetail)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Pegawai</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Informasi Pegawai</h4></div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>{{ $pegawaiDetail->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <th>NIP</th>
                                <td>{{ $pegawaiDetail->nip }}</td>
                            </tr>
                            <tr>
                                <th>Tempat, Tanggal Lahir</th>
                                <td>{{ $pegawaiDetail->tempat_lahir }}, {{ $pegawaiDetail->formatted_tanggal_lahir }}</td>
                            </tr>
                            <tr>
                                <th>Nomor HP</th>
                                <td>{{ $pegawaiDetail->no_hp }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $pegawaiDetail->alamat }}</td>
                            </tr>
                            <tr>
                                <th>Bidang</th>
                                <td>{{ $pegawaiDetail->bidang->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $pegawaiDetail->jabatan->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Golongan</th>
                                <td>{{ $pegawaiDetail->golongan->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>{{ $pegawaiDetail->user->username }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $pegawaiDetail->user->email }}</td>
                            </tr>
                        </table>

                        <div class="text-right mt-3">
                            <button wire:click="resetForm" class="btn btn-secondary">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if (!$formAdd && !$formEdit && !$formDetail)
<section class="section">
    <div class="section-header">
        <h1>Data Pegawai</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start">
                        <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                            <h4 class="mb-0">List Pegawai</h4>
                            <button wire:click="$set('formAdd', true)" class="btn btn-primary rounded-lg">Tambah Data</button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="float-right">
                            <div class="d-flex jutstify-content-end column-gap-1">
                                <div class="input-group">
                                   <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="cari ...">
                                   <div class="input-group-append">
                                       <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                   </div>
                               </div>
                               <button wire:click="openFilterModal" class="btn btn-primary">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
    
                        <div class="clearfix mb-3"></div>

                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama Lengkap</th>
                                        <th>NIP</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Golongan</th>
                                        <th>Bidang</th>
                                        <th>Jabatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listPegawai as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + ($listPegawai->firstItem() - 1) }}</td>
                                        <td>{{ $item->nama_lengkap }}</td>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->user->username }}</td>
                                        <td>{{ $item->user->email }}</td>
                                        <td>{{ $item->golongan->nama }}</td>
                                        <td>{{ $item->bidang->nama }}</td>
                                        <td>{{ $item->jabatan->nama ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button wire:click="detail({{ $item->id }})" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                                <button wire:click="edit({{ $item->id }})" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></button>
                                                <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            {{ $listPegawai->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Modal Konfirmasi Hapus -->
@if ($confirmingDelete)
<div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pegawai ini?</p>
            </div>
            <div class="modal-footer">
                <button wire:click="deleteConfirmed" class="btn btn-danger">Ya, Hapus</button>
                <button wire:click="$set('confirmingDelete', false)" class="btn btn-secondary">Batal</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- modal filter -->
@if ($showFilterModal)
<div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Pegawai</h5>
                <button type="button" class="close" wire:click="closeFilterModal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Bidang</label>
                    <select class="form-control" wire:model="filterBidang">
                        <option value="">-- Semua Bidang --</option>
                        @foreach ($bidang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Jabatan</label>
                    <select class="form-control" wire:model="filterJabatan">
                        <option value="">-- Semua Jabatan --</option>
                        @foreach ($jabatan as $j)
                            <option value="{{ $j->id }}">{{ $j->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Golongan</label>
                    <select class="form-control" wire:model="filterGolongan">
                        <option value="">-- Semua Golongan --</option>
                        @foreach ($golongan as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button wire:click="resetFilters" class="btn btn-secondary">Reset</button>
                <button wire:click="closeFilterModal" class="btn btn-primary">Terapkan</button>
            </div>
        </div>
    </div>
</div>
@endif

</div>

