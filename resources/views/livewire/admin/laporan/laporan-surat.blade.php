<div>
<div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Filter Laporan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Tipe Surat Filter -->
                            <div class="col-md-3 mb-3">
                                <label>Tipe Surat</label>
                                <select wire:model.live="tipeSurat" class="form-control">
                                    <option value="surat_masuk">Surat Masuk</option>
                                    <option value="surat_keluar">Surat Keluar</option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="col-md-3 mb-3">
                                <label>Status</label>
                                @if($tipeSurat === 'surat_masuk')
                                <select wire:model.live="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="disposisi">Disposisi</option>
                                    <option value="belum_disposisi">Belum Disposisi</option>
                                </select>
                                @else
                                <select wire:model.live="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="disetujui">Disetujui</option>
                                    <option value="ditolak">Ditolak</option>
                                    <option value="menunggu">Menunggu</option>
                                </select>
                                @endif
                            </div>

                            <!-- Tanggal Awal Filter -->
                            <div class="col-md-3 mb-3">
                                <label>Tanggal Awal</label>
                                <input type="date" wire:model.live="tanggalAwal" class="form-control">
                            </div>

                            <!-- Tanggal Akhir Filter -->
                            <div class="col-md-3 mb-3">
                                <label>Tanggal Akhir</label>
                                <input type="date" wire:model.live="tanggalAkhir" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Cari surat...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <button wire:click="resetFilters" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Reset Filter
                                </button>
                                <button wire:click="printReport" class="btn btn-success">
                                    <i class="fas fa-print"></i> Cetak Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            @if($tipeSurat === 'surat_masuk')
                                Data Surat Masuk
                            @else
                                Data Surat Keluar
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if($tipeSurat === 'surat_masuk')
                            <!-- Tabel Surat Masuk -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>No. Surat</th>
                                        <th>Kode</th>
                                        <th>Asal Surat</th>
                                        <th>Perihal</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($surats as $index => $surat)
                                    <tr>
                                        <td>{{ $surats->firstItem() + $index }}</td>
                                        <td>{{ $surat->no_surat }}</td>
                                        <td>{{ $surat->kodeSurat->kode ?? '-' }}</td>
                                        <td>{{ $surat->asal_surat }}</td>
                                        <td>{{ $surat->perihal }}</td>
                                        <td>{{ $surat->formatted_tanggal_masuk }}</td>
                                        <td>{!! $surat->getStatusBadge() !!}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data surat masuk</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @else
                            <!-- Tabel Surat Keluar -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>No. Surat</th>
                                        <th>Kode</th>
                                        <th>Perihal</th>
                                        <th>Tujuan</th>
                                        <th>Pengirim</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($surats as $index => $surat)
                                    <tr>
                                        <td>{{ $surats->firstItem() + $index }}</td>
                                        <td>{{ $surat->no_surat ?? '-' }}</td>
                                        <td>{{ $surat->kodeSurat->kode ?? '-' }}</td>
                                        <td>{{ $surat->perihal }}</td>
                                        <td>{{ $surat->tujuan }}</td>
                                        <td>{{ $surat->pegawai->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $surat->formatted_tanggal_pengajuan }}</td>
                                        <td>{!! $surat->getStatusBadge() !!}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data surat keluar</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @endif
                        </div>
                        <div class="mt-4">
                            {{ $surats->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Print Preview Modal -->
@if($showPrintPreview)
<div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mx-2">Preview Laporan</h5>
                <button type="button" class="btn btn-primary mx-2" wire:click="cetakPdf">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button type="button" class="close" wire:click="closePrintPreview">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="print-area" class="p-3">
                    <div class="text-center mb-4">
                        <h3>LAPORAN {{ strtoupper($printData['tipe']) }}</h3>
                        <p>Periode: {{ $printData['periode'] }}</p>
                        <p>Status: {{ $printData['status'] }}</p>
                    </div>
                    
                    <div class="table-responsive">
                        @if($tipeSurat === 'surat_masuk')
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>No. Surat</th>
                                    <th>Kode</th>
                                    <th>Asal Surat</th>
                                    <th>Perihal</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($printData['data'] as $index => $surat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $surat->no_surat }}</td>
                                    <td>{{ $surat->kodeSurat->kode ?? '-' }}</td>
                                    <td>{{ $surat->asal_surat }}</td>
                                    <td>{{ $surat->perihal }}</td>
                                    <td>{{ $surat->formatted_tanggal_masuk }}</td>
                                    <td>{{ $surat->status }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data surat masuk</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>No. Surat</th>
                                    <th>Kode</th>
                                    <th>Perihal</th>
                                    <th>Tujuan</th>
                                    <th>Pengirim</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($printData['data'] as $index => $surat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $surat->no_surat ?? '-' }}</td>
                                    <td>{{ $surat->kodeSurat->kode ?? '-' }}</td>
                                    <td>{{ $surat->perihal }}</td>
                                    <td>{{ $surat->tujuan }}</td>
                                    <td>{{ $surat->pegawai->nama_lengkap ?? '-' }}</td>
                                    <td>{{ $surat->formatted_tanggal_pengajuan }}</td>
                                    <td>{{ $surat->status }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data surat keluar</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @endif
                    </div>
                    
                    <!-- Signature section -->
                    <div class="row mt-5">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 text-center">
                            <p>........................., {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                            <p>Mengetahui,</p>
                            <br><br><br>
                            <p>_________________________</p>
                            <p>Kepala Bagian</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closePrintPreview">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printReport()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function printReport() {
        const printContents = document.getElementById('print-area').innerHTML;
        const originalContents = document.body.innerHTML;
        
        document.body.innerHTML = `
            <html>
            <head>
                <title>Laporan Surat</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    table, th, td { border: 1px solid #ddd; }
                    th, td { padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    h3 { text-align: center; }
                    .text-center { text-align: center; }
                    @media print {
                        @page { margin: 2cm; }
                    }
                </style>
            </head>
            <body>
                ${printContents}
            </body>
            </html>
        `;
        
        window.print();
        document.body.innerHTML = originalContents;
        
        // Re-initialize Livewire after printing
        window.Livewire.rescan();
    }
</script>
@endif
</div>