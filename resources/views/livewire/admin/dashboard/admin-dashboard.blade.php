<div>
<section class="section">
<div class="section-header">
    <h1>Dashboard</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h4>Hai, {{ auth()->user()->username }} 👋</h4>
            </div>
            <div class="card-body">
                <h6 class="fs-5 mb-0 text-center">
                    Selamat Datang Di Sistem Manajemen Surat Masuk Dan Keluar<br>
                    <strong>"DINKOP UKM DAN NAKER KABUPATEN PEKALONGAN"</strong>
                </h6>
            </div>
        </div>
    </div>
</div>


@if (Auth::user()->role->name != 'pegawai')
<div class="row">
    <div class="col-4">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Jumlah Pegawai</h4>
                </div>
                <div class="card-body">
                    {{ $totalPegawai }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
                <i class="fas fa-inbox"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Surat Masuk</h4>
                </div>
                <div class="card-body">
                    {{ $totalSuratMasuk }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Surat Keluar</h4>
                </div>
                <div class="card-body">
                    {{ $totalSuratKeluar }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Surat Masuk Belum Disposisi</h4>
                </div>
                <div class="card-body">
                    {{ $totalSuratMasukMenunggu }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Surat Keluar Menunggu Persetujuan</h4>
                </div>
                <div class="card-body">
                    {{ $totalSuratKeluarMenunggu }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card card-statistic-2">
        <div class="card-stats">
            <div class="card-stats-title">Surat Keluar Saya
            </div>
            <div class="card-stats-items">
            <div class="card-stats-item">
                <div class="card-stats-item-count">{{ $suratMenunggu }}</div>
                <div class="card-stats-item-label">Menunggu</div>
            </div>
            <div class="card-stats-item">
                <div class="card-stats-item-count">{{ $suratDisetujui }}</div>
                <div class="card-stats-item-label">Disetujui</div>
            </div>
            <div class="card-stats-item">
                <div class="card-stats-item-count">{{ $suratDitolak }}</div>
                <div class="card-stats-item-label">Ditolak</div>
            </div>
            </div>
        </div>
        <div class="card-icon shadow-primary bg-primary">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <div class="card-wrap">
            <div class="card-header">
            <h4>Total</h4>
            </div>
            <div class="card-body">
            {{ $totalSuratSaya }}
            </div>
        </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-icon bg-info">
                <i class="fas fa-share-square"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Disposisi Untuk Saya</h4>
                </div>
                <div class="card-body">
                    {{ $disposisiUntukSaya }}
                </div>
            </div>
        </div>
    </div>
</div>

</section>
</div>
