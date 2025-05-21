<div>

@if ($showSuratSelection)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Pilih Surat Masuk</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Surat Masuk Untuk Disposisi</h4>
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
                                        <th>Asal Surat</th>
                                        <th>Perihal</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listSurat as $surat)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + ($listSurat->firstItem() - 1) }}</td>
                                        <td>{{ $surat->no_surat }}</td>
                                        <td>{{ $surat->asal_surat }}</td>
                                        <td>{{ $surat->perihal }}</td>
                                        <td>{{ $surat->formatted_tanggal_masuk }}</td>
                                        <td>{!! $surat->getStatusBadge() !!}</td>
                                        <td>
                                            <button wire:click="selectSurat({{ $surat->id }})" class="btn btn-primary">
                                                <i class="fas fa-check-circle"></i> Pilih
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada surat yang tersedia untuk disposisi</td>
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
        <h1>{{ $disposisi_id ? 'Edit Disposisi' : 'Tambah Disposisi' }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Form Disposisi</h4></div>
                    <div class="card-body">
                        <form wire:submit.prevent="{{ $disposisi_id ? 'update' : 'add' }}" autocomplete="off">
                            <!-- Surat Info -->
                            @if ($surat_masuk_id)
                                @php
                                    $surat = App\Models\SuratMasuk::with('kodeSurat')->find($surat_masuk_id);
                                @endphp
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Detail Surat</label>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>No. Surat</th>
                                                    <td>{{ $surat->no_surat }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kode Surat</th>
                                                    <td>{{ $surat->kodeSurat->kode }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Asal Surat</th>
                                                    <td>{{ $surat->asal_surat }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Masuk</th>
                                                    <td>{{ $surat->formatted_tanggal_masuk }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Perihal</th>
                                                    <td>{{ $surat->perihal }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Lampiran</th>
                                                    <td><a href="/surat/{{ $surat->file }}" target="_blank"><i class="fas fa-download"></i> download</a></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Bidang Tujuan -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Bidang Tujuan</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control" wire:model.live="bidang_tujuan">
                                        <option value="">-- Pilih Bidang --</option>
                                        @foreach($listBidang as $bidang)
                                            <option value="{{ $bidang->id }}">{{ $bidang->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('bidang_tujuan') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Pegawai Tujuan -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pegawai Tujuan</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control" wire:model.defer="pegawai_tujuan" {{ empty($pegawaiList) ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Pegawai --</option>
                                        @foreach($pegawaiList as $pegawai)
                                            <option value="{{ $pegawai->id }}">{{ $pegawai->nama_lengkap }} ({{ $pegawai->nip }})</option>
                                        @endforeach
                                    </select>
                                    @if(empty($pegawaiList) && $bidang_tujuan)
                                        <small class="text-info">Tidak ada pegawai di bidang ini</small>
                                    @elseif(empty($bidang_tujuan))
                                        <small class="text-info">Pilih bidang terlebih dahulu</small>
                                    @endif
                                    @error('pegawai_tujuan') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Sifat -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Sifat</label>
                                <div class="col-sm-12 col-md-7">
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sifat" value="biasa" class="selectgroup-input" wire:model.defer="sifat" checked>
                                            <span class="selectgroup-button">Biasa</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sifat" value="segera" class="selectgroup-input" wire:model.defer="sifat">
                                            <span class="selectgroup-button">Segera</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sifat" value="sangat_segera" class="selectgroup-input" wire:model.defer="sifat">
                                            <span class="selectgroup-button">Sangat Segera</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sifat" value="rahasia" class="selectgroup-input" wire:model.defer="sifat" checked>
                                            <span class="selectgroup-button">Rahasia</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="sifat" value="sangat_rahasia" class="selectgroup-input" wire:model.defer="sifat">
                                            <span class="selectgroup-button">Sangat Rahasia</span>
                                        </label>
                                    </div>
                                    @error('sifat') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Tanggal Disposisi -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggal Disposisi</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="date" class="form-control" wire:model.defer="tanggal_disposisi">
                                    @error('tanggal_disposisi') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Catatan -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Catatan</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea class="form-control" wire:model.defer="catatan" rows="5"></textarea>
                                    @error('catatan') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button wire:loading.remove wire:target="add,update" type="submit" class="btn btn-primary">
                                        {{ $disposisi_id ? 'Update' : 'Simpan' }}
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
        <h1>Detail Disposisi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Disposisi</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $disposisi = App\Models\Disposisi::with(['suratMasuk', 'bidangTujuan', 'pegawaiTujuan'])->find($disposisi_id);
                            $surat = $disposisi->suratMasuk;
                        @endphp
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Detail Surat</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>No. Surat</th>
                                            <td>{{ $surat->no_surat }}</td>
                                        </tr>
                                        <tr>
                                            <th>Asal Surat</th>
                                            <td>{{ $surat->asal_surat }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Masuk</th>
                                            <td>{{ $surat->formatted_tanggal_masuk }}</td>
                                        </tr>
                                        <tr>
                                            <th>Perihal</th>
                                            <td>{{ $surat->perihal }}</td>
                                        </tr>
                                        <tr>
                                            <th>Lampiran</th>
                                            <td><a href="/surat/{{ $surat->file }}" target="_blank"><i class="fas fa-download"></i> download</a></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Detail Disposisi</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Bidang Tujuan</th>
                                            <td>{{ $disposisi->bidangTujuan->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pegawai Tujuan</th>
                                            <td>{{ $disposisi->pegawaiTujuan->nama_lengkap }} ({{ $disposisi->pegawaiTujuan->nip }})</td>
                                        </tr>
                                        <tr>
                                            <th>Sifat</th>
                                            <td>
                                                @if($disposisi->sifat == 'segera')
                                                    <span class="badge badge-danger">Segera</span>
                                                @elseif($disposisi->sifat == 'biasa')
                                                    <span class="badge badge-info">Biasa</span>
                                                @elseif($disposisi->sifat == 'sangat_segera')
                                                    <span class="badge badge-info">Sangat Segera</span>
                                                @elseif($disposisi->sifat == 'rahasia')
                                                    <span class="badge badge-info">Rahasia</span>
                                                @elseif($disposisi->sifat == 'sangat_rahasia')
                                                    <span class="badge badge-info">Sangat Rahasia</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Disposisi</th>
                                            <td>{{ \Carbon\Carbon::parse($disposisi->tanggal_disposisi)->locale('id')->translatedFormat('d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Catatan</th>
                                            <td>{{ $disposisi->catatan }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right mt-4">
                            <button wire:click="edit({{ $disposisi_id }})" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> Edit</button>
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
        <h1>Data Disposisi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start">
                        <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                            <h4 class="mb-0">List Disposisi</h4>
                            <button wire:click="showSuratSelectionForm" class="btn btn-primary rounded-lg">Tambah Disposisi</button>
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
                                        <th>Bidang Tujuan</th>
                                        <th>Pegawai Tujuan</th>
                                        <th>Sifat</th>
                                        <th>Tanggal Disposisi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listDisposisi as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + ($listDisposisi->firstItem() - 1) }}</td>
                                        <td>{{ $item->suratMasuk->no_surat ?? '-' }}</td>
                                        <td>{{ $item->suratMasuk->perihal ?? '-' }}</td>
                                        <td>{{ $item->bidangTujuan->nama ?? '-' }}</td>
                                        <td>{{ $item->pegawaiTujuan->nama_lengkap ?? '-' }}</td>
                                        <td>
                                            @if($item->sifat == 'segera')
                                                <span class="badge badge-danger">Segera</span>
                                            @elseif($item->sifat == 'biasa')
                                                <span class="badge badge-info">Biasa</span>
                                            @elseif($item->sifat == 'sangat_segera')
                                                <span class="badge badge-info">Sangat Segera</span>
                                            @elseif($item->sifat == 'rahasia')
                                                <span class="badge badge-info">Rahasia</span>
                                            @elseif($item->sifat == 'sangat_rahasia')
                                                <span class="badge badge-info">Sangat Rahasia</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_disposisi)->locale('id')->translatedFormat('d F Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button wire:click="share({{ $item->id }})" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
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
                                        <td colspan="8" class="text-center">Data disposisi tidak ditemukan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="p-3">
                            {{ $listDisposisi->links() }}
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
                <p>Apakah Anda yakin ingin menghapus data ini?</p>
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
                <h5 class="modal-title">Filter Disposisi</h5>
                <button type="button" class="close" wire:click="closeFilterModal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Sifat</label>
                    <select class="form-control" wire:model="filterSifat">
                        <option value="">-- Semua Sifat --</option>
                        <option value="segera">Segera</option>
                        <option value="biasa">Biasa</option>
                        <option value="sangat_segera">Sangat Segera</option>
                        <option value="rahasia">Rahasia</option>
                        <option value="sangat_rahasia">Sangat Rahasia</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Disposisi (Awal)</label>
                    <input type="date" class="form-control" wire:model="filterTanggalAwal">
                </div>

                <div class="form-group">
                    <label>Tanggal Disposisi (Akhir)</label>
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