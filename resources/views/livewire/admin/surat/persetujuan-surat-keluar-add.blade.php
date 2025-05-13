<div>

@if ($showSuratSelection)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Pilih Surat Keluar</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Surat Keluar Untuk Persetujuan</h4>
                    </div>
                    
                    <div class="card-body">
                        <div class="float-right">
                            <div class="d-flex jutstify-content-end column-gap-1">
                                <div class="input-group">
                                   <input wire:model.live.debounce.500ms="searchSurat" type="text" class="form-control" placeholder="cari surat ...">
                                   <div class="input-group-append">
                                       <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                   </div>
                               </div>
                            </div>
                        </div>
    
                        <div class="clearfix mb-3"></div>

                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>No. Surat</th>
                                        <th>Kode Surat</th>
                                        <th>Perihal</th>
                                        <th>Tujuan</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listSurat as $surat)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + ($listSurat->firstItem() - 1) }}</td>
                                        <td>{{ $surat->no_surat ?? '-' }}</td>
                                        <td>{{ $surat->kodeSurat->kode ?? '-' }}</td>
                                        <td>{{ $surat->perihal }}</td>
                                        <td>{{ $surat->tujuan }}</td>
                                        <td>{{ $surat->formatted_tanggal_pengajuan }}</td>
                                        <td>{!! $surat->getStatusBadge() !!}</td>
                                        <td>
                                            <button wire:click="selectSurat({{ $surat->id }})" class="btn btn-primary">
                                                <i class="fas fa-check-circle"></i> Pilih
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada surat yang tersedia untuk persetujuan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            {{ $listSurat->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if ($formAdd)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{ $persetujuan_id ? 'Edit Persetujuan Surat Keluar' : 'Tambah Persetujuan Surat Keluar' }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Form Persetujuan</h4></div>
                    <div class="card-body">
                        <form wire:submit.prevent="{{ $persetujuan_id ? 'update' : 'add' }}" autocomplete="off">
                            <!-- Surat Info -->
                            @if ($surat_keluar_id)
                                @php
                                    $surat = App\Models\SuratKeluar::with(['kodeSurat', 'pegawai'])->find($surat_keluar_id);
                                @endphp
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Detail Surat</label>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>No. Surat</th>
                                                    <td>{{ $surat->no_surat ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kode Surat</th>
                                                    <td>{{ $surat->kodeSurat->kode ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Perihal</th>
                                                    <td>{{ $surat->perihal }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tujuan</th>
                                                    <td>{{ $surat->tujuan }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Pengirim</th>
                                                    <td>{{ $surat->pegawai->nama_lengkap ?? '-' }} ({{ $surat->pegawai->nip ?? '-' }})</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Pengajuan</th>
                                                    <td>{{ $surat->formatted_tanggal_pengajuan }}</td>
                                                </tr>
                                                @if($surat->file)
                                                <tr>
                                                    <th>Lampiran</th>
                                                    <td><a href="/surat/{{ $surat->file }}" target="_blank"><i class="fas fa-download"></i> download</a></td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Status -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                                <div class="col-sm-12 col-md-7">
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="disetujui" class="selectgroup-input" wire:model.defer="status" checked>
                                            <span class="selectgroup-button">Disetujui</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="ditolak" class="selectgroup-input" wire:model.defer="status">
                                            <span class="selectgroup-button">Ditolak</span>
                                        </label>
                                    </div>
                                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Catatan -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Catatan</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea class="form-control" wire:model.defer="catatan" rows="5" placeholder="Berikan catatan persetujuan/penolakan (opsional)"></textarea>
                                    @error('catatan') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button wire:loading.remove wire:target="add,update" type="submit" class="btn btn-primary">
                                        {{ $persetujuan_id ? 'Update' : 'Simpan' }}
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
            <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Persetujuan Surat Keluar</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Persetujuan</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $persetujuan = App\Models\PersetujuanSuratKeluar::with(['suratKeluar', 'pegawai'])->find($persetujuan_id);
                            $surat = $persetujuan->suratKeluar;
                        @endphp
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Detail Surat</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>No. Surat</th>
                                            <td>{{ $surat->no_surat ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kode Surat</th>
                                            <td>{{ $surat->kodeSurat->kode ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Perihal</th>
                                            <td>{{ $surat->perihal }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tujuan</th>
                                            <td>{{ $surat->tujuan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pengirim</th>
                                            <td>{{ $surat->pegawai->nama_lengkap ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pengajuan</th>
                                            <td>{{ $surat->formatted_tanggal_pengajuan }}</td>
                                        </tr>
                                        @if($surat->file)
                                        <tr>
                                            <th>Lampiran</th>
                                            <td><a href="/surat/{{ $surat->file }}" target="_blank"><i class="fas fa-download"></i> download</a></td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Detail Persetujuan</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Pegawai</th>
                                            <td>{{ $persetujuan->pegawai->nama_lengkap ?? '-' }} ({{ $persetujuan->pegawai->nip ?? '-' }})</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($persetujuan->status == 'disetujui')
                                                    <span class="badge badge-success">Disetujui</span>
                                                @else
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Persetujuan</th>
                                            <td>{{ \Carbon\Carbon::parse($persetujuan->tanggal_persetujuan)->locale('id')->translatedFormat('d F Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Catatan</th>
                                            <td>{{ $persetujuan->catatan ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right mt-4">
                            <button wire:click="edit({{ $persetujuan_id }})" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> Edit</button>
                            <button wire:click="resetForm" class="btn btn-secondary">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if (!$formAdd && !$formDetail && !$showSuratSelection)
<section class="section">
    <div class="section-header">
        <h1>Data Persetujuan Surat Keluar</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start">
                        <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                            <h4 class="mb-0">List Persetujuan Surat Keluar</h4>
                            <button wire:click="showSuratSelectionForm" class="btn btn-primary rounded-lg">Tambah Persetujuan</button>
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
                                        <th>No. Surat</th>
                                        <th>Perihal</th>
                                        <th>Tujuan</th>
                                        <th>Pengirim</th>
                                        <th>Status</th>
                                        <th>Tanggal Persetujuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listPersetujuan as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + ($listPersetujuan->firstItem() - 1) }}</td>
                                        <td>{{ $item->suratKeluar->no_surat ?? '-' }}</td>
                                        <td>{{ $item->suratKeluar->perihal }}</td>
                                        <td>{{ $item->suratKeluar->tujuan }}</td>
                                        <td>{{ $item->suratKeluar->pegawai->nama_lengkap ?? '-' }}</td>
                                        <td>
                                            @if($item->status == 'disetujui')
                                                <span class="badge badge-success">Disetujui</span>
                                            @else
                                                <span class="badge badge-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_persetujuan)->locale('id')->translatedFormat('d F Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button wire:click="showDetail({{ $item->id }})" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button wire:click="edit({{ $item->id }})" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Data persetujuan surat keluar tidak ditemukan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3">
                            {{ $listPersetujuan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Confirmation Modal -->
@if($confirmingDelete)
<div class="modal fade show" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" wire:click="$set('confirmingDelete', false)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data persetujuan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Batal</button>
                <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endif

@if ($showFilterModal)
<div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Persetujuan Surat Keluar</h5>
                <button type="button" class="close" wire:click="closeFilterModal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Status Persetujuan</label>
                    <select class="form-control" wire:model="filterStatus">
                        <option value="">-- Semua Status --</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Persetujuan (Awal)</label>
                    <input type="date" class="form-control" wire:model="filterTanggalAwal">
                </div>

                <div class="form-group">
                    <label>Tanggal Persetujuan (Akhir)</label>
                    <input type="date" class="form-control" wire:model="filterTanggalAkhir">
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