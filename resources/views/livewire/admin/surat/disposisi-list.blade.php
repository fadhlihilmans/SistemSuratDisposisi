<div>

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
                                            <td>
                                                @if ($surat->file)
                                                    <a href="/surat/{{ $surat->file }}" target="_blank">
                                                        <i class="fas fa-download"></i> download
                                                    </a>
                                                @else
                                                    <span class="text-muted">Tidak ada file</span>
                                                @endif
                                            </td>
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
                                                @else
                                                    <span class="badge badge-info">Biasa</span>
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
                            <button wire:click="resetForm" class="btn btn-secondary">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if (!$formDetail)
<section class="section">
    <div class="section-header">
        <h1>Daftar Disposisi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>List Disposisi</h4>
                    </div>
                    <div class="card-body">
                        <div class="float-right mb-3">
                            <div class="input-group">
                                <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Cari ...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Asal Surat</th>
                                        <th>Tanggal Disposisi</th>
                                        <th>Sifat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listDisposisi as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ($listDisposisi->firstItem() - 1) }}</td>
                                        <td>{{ $item->suratMasuk->no_surat }}</td>
                                        <td>{{ $item->suratMasuk->perihal }}</td>
                                        <td>{{ $item->suratMasuk->asal_surat }}</td>
                                        <td>{{ $item->formatted_tanggal_disposisi }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item->sifat == 'segera' ? 'danger' : 'secondary' }}">
                                                {{ ucfirst($item->sifat) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button wire:click="detail({{ $item->id }})" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data.</td>
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

</div>
