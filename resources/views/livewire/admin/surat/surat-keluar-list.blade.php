<div>

@if ($formDetail)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Surat Keluar</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Informasi Surat Keluar</h4></div>
                    <div class="card-body">

                        @php
                            $surat = \App\Models\SuratKeluar::with(['kodeSurat', 'pegawai', 'persetujuan'])->find($surat_keluar_id);
                        @endphp

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Detail Surat</h5>
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
                                        <th>Tanggal Pengajuan</th>
                                        <td>{{ $surat->formatted_tanggal_pengajuan }}</td>
                                    </tr>
                                    <tr>
                                        <th>File</th>
                                        <td>
                                            @if ($surat->file)
                                                <a href="/surat/{{ $surat->file }}" target="_blank"><i class="fas fa-download"></i> download</a>
                                            @else
                                                Tidak ada file
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{!! $surat->getStatusBadge() !!}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <h5>Informasi Pengirim</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Nama Pegawai</th>
                                        <td>{{ $surat->pegawai->nama_lengkap ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIP</th>
                                        <td>{{ $surat->pegawai->nip ?? '-' }}</td>
                                    </tr>
                                </table>

                                @if ($surat->persetujuan && $surat->persetujuan->count())
                                <h5 class="mt-4">Riwayat Persetujuan</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Pegawai</th>
                                            <th>Status</th>
                                            <th>Catatan</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($surat->persetujuan as $item)
                                        <tr>
                                            <td>{{ $item->pegawai->nama_lengkap }} ({{ $item->pegawai->nip }})</td>
                                            <td>
                                                <span class="badge badge-{{ $item->status == 'disetujui' ? 'success' : 'danger' }}">{{ ucfirst($item->status) }}</span>
                                            </td>
                                            <td>{{ $item->catatan ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_persetujuan)->locale('id')->translatedFormat('d F Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button wire:click="edit({{ $surat_keluar_id }})" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> Edit</button>
                            <button wire:click="resetForm" class="btn btn-secondary">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if ($formAdd || $formEdit)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{ $formAdd ? 'Tambah Surat Keluar' : 'Edit Surat Keluar' }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Form Surat Keluar</h4></div>
                    <div class="card-body">
                        <form wire:submit.prevent="{{ $formAdd ? 'add' : 'update' }}" autocomplete="off">
                            <div class="form-group row mb-4">
                                <label class="col-form-label col-md-3">Kode Surat</label>
                                <div class="col-md-7">
                                    <select class="form-control" wire:model.defer="kode_surat_id">
                                        <option value="">-- pilih --</option>
                                        @foreach ($kodeSurat as $kode)
                                            <option value="{{ $kode->id }}">{{ $kode->kode }} - {{ $kode->keterangan }}</option>
                                        @endforeach
                                    </select>
                                    @error('kode_surat_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            
                            @if ($status == 'disetujui')
                                <div class="form-group row mb-4">
                                    <label class="col-form-label col-md-3">Nomor Surat</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" wire:model.defer="no_surat" placeholder="Tambahkan Nomor Surat" autofocus>
                                        @error('no_surat') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row mb-4">
                                <label class="col-form-label col-md-3">Pengirim</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control mb-2" value="{{ $nip }} - {{ $nama_lengkap }}" readonly>
                                    @error('pegawai_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>


                            <div class="form-group row mb-4">
                                <label class="col-form-label col-md-3">Tanggal Pengajuan</label>
                                <div class="col-md-7">
                                    <input type="date" class="form-control" wire:model.defer="tanggal_pengajuan">
                                    @error('tanggal_pengajuan') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label col-md-3">Perihal</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" wire:model.defer="perihal">
                                    @error('perihal') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label col-md-3">Tujuan</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" wire:model.defer="tujuan">
                                    @error('tujuan') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label col-md-3">File</label>
                                <div class="col-md-7">
                                    <label for="file-upload" class="d-flex flex-column align-items-center justify-content-center border border-dashed rounded-lg p-4 text-center cursor-pointer" style="border-style: dashed; border-width: 2px;">
                                        <i wire:loading.remove wire:target="file" class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                        <i wire:loading wire:target="file" class="fas fa-spin fa-spinner fa-2x text-primary mb-2"></i>
                                        @if ($file)
                                            <span class="font-weight-bold text-success">{{ $file->getClientOriginalName() }}</span>
                                        @else
                                            <span class="text-muted">Klik di sini untuk mengunggah file</span>
                                        @endif
                                        <input id="file-upload" type="file" class="d-none" wire:model="file">
                                    </label>

                                    @if ($old_file)
                                        <small class="text-muted d-block mt-2">File sebelumnya: <a href="/surat/{{ $old_file }}" target="_blank">{{ $old_file }}</a></small>
                                    @endif
                                    @error('file') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <div class="col-md-7 offset-md-3">
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

@if (!$formAdd && !$formEdit && !$formDetail)
<section class="section">
    <div class="section-header">
        <h1>Data Surat Keluar</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start">
                        <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                            <h4 class="mb-0">List Surat Keluar</h4>
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
                                        <th>#</th>
                                        <th class="text-center">No Surat</th>
                                        <th>Perihal</th>
                                        <th>Tujuan</th>
                                        <th>Pengirim</th>
                                        <th>Tgl Pengajuan</th>
                                        <th>Status</th>
                                        <th class="text-center" colspan="2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listSuratKeluar as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ($listSuratKeluar->firstItem() - 1) }}</td>
                                        <td class="text-center">{{ $item->no_surat ?? '-' }}</td>
                                        <td>{{ $item->perihal }}</td>
                                        <td>{{ $item->tujuan }}</td>
                                        <td>{{ $item->pegawai->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $item->formatted_tanggal_pengajuan }}</td>
                                        <td>
                                            {!! $item->getStatusBadge() !!}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if ($item->status == 'disetujui')
                                                    <button wire:click="edit({{ $item->id }})" class="btn btn-sm btn-light"><i class="fas fa-paperclip"></i> No Surat</button>
                                                @endif
                                            </div>
                                        </td>
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
                                        <td colspan="9" class="text-center">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            {{ $listSuratKeluar->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if ($confirmingDelete)
<div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus surat ini?</p>
            </div>
            <div class="modal-footer">
                <button wire:click="deleteConfirmed" class="btn btn-danger">Ya, Hapus</button>
                <button wire:click="$set('confirmingDelete', false)" class="btn btn-secondary">Batal</button>
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
                <h5 class="modal-title">Filter Surat Keluar</h5>
                <button type="button" class="close" wire:click="closeFilterModal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" wire:model="filterStatus">
                        <option value="">-- Semua Status --</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Pengajuan (Awal)</label>
                    <input type="date" class="form-control" wire:model="filterTanggalAwal">
                </div>

                <div class="form-group">
                    <label>Tanggal Pengajuan (Akhir)</label>
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
