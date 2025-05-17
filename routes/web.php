<?php

// use App\Http\Controllers\Admin\PrintPdfController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard.admin-dashboard');
    }
    return redirect()->route('login');
});
Route::get('/login', \App\Livewire\Auth\Login::class)
    ->name('login')->middleware('guest');
Route::get('/logout', function () {
    Auth::logout();
    return redirect(route('login'));
})->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    
    //semua role
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard\AdminDashboard::class)->name('dashboard.admin-dashboard'); 
    Route::get('/user/profile', \App\Livewire\Admin\User\Profile::class)->name('dashboard.user.profile'); 

    // tab user (role: super-admin, sekretariat)
    Route::get('/user/list', \App\Livewire\Admin\User\UserList::class)->name('user.list')
        ->middleware(RoleMiddleware::class . ':super-admin,sekretariat');
    Route::get('/role/list', \App\Livewire\Admin\User\RoleList::class)->name('role.list')
        ->middleware(RoleMiddleware::class . ':super-admin,sekretariat');

    // tab kepegawaian (role: super-admin, sekretariat)
    Route::get('/jabatan/list', \App\Livewire\Admin\Jabatan\JabatanList::class)->name('jabatan.list')
        ->middleware(RoleMiddleware::class . ':super-admin,sekretariat');
    Route::get('/bidang/list', \App\Livewire\Admin\Bidang\BidangList::class)->name('bidang.list')
        ->middleware(RoleMiddleware::class . ':super-admin,sekretariat');
    Route::get('/golongan/list', \App\Livewire\Admin\Golongan\GolonganList::class)->name('golongan.list')
        ->middleware(RoleMiddleware::class . ':super-admin,sekretariat');
    Route::get('/pegawai/list', \App\Livewire\Admin\Pegawai\PegawaiList::class)->name('pegawai.list')
        ->middleware(RoleMiddleware::class . ':super-admin,sekretariat');

    // tab surat
    Route::get('/kode-surat/list', \App\Livewire\Admin\Surat\KodeSuratList::class)->name('kode-surat.list')
        ->middleware(RoleMiddleware::class . ':super-admin,sekretariat');
    Route::get('/surat-masuk/list', \App\Livewire\Admin\Surat\SuratMasukList::class)->name('surat-masuk.list')
        ->middleware(RoleMiddleware::class . ':super-admin,sekretariat'); 
    Route::get('/surat-keluar/list', \App\Livewire\Admin\Surat\SuratKeluarList::class)->name('surat-keluar.list')
        ->middleware(RoleMiddleware::class . ':super-admin,sekretariat,pegawai');
    
    Route::get('/disposisi/list', \App\Livewire\Admin\Surat\DisposisiList::class)->name('disposisi.list')
        ->middleware(RoleMiddleware::class . ':super-admin,sekretariat,pegawai');
    Route::get('/disposisi/add', \App\Livewire\Admin\Surat\DisposisiAdd::class)->name('disposisi.add')
        ->middleware(RoleMiddleware::class . ':super-admin,kepala'); //role: super-admin, kepala
    Route::get('/persetujuan-surat-keluar/add', \App\Livewire\Admin\Surat\PersetujuanSuratKeluarAdd::class)->name('persetujuan-surat-keluar.add')
        ->middleware(RoleMiddleware::class . ':super-admin,kepala'); //role: super-admin, kepala

    //tab surat 
    Route::get('/laporan/laporan-surat', \App\Livewire\Admin\Laporan\LaporanSurat::class)->name('laporan.laporan-surat')
        ->middleware(RoleMiddleware::class . ':super-admin,kepala,sekretariat'); //role: super-admin, kepala, sekretariat
    Route::get('/laporan/pdf', [\App\Http\Controllers\Admin\PrintPdfController::class, 'cetakLaporan'])->name('laporan.pdf')
        ->middleware(RoleMiddleware::class . ':super-admin,kepala,sekretariat'); //role: super-admin, kepala, sekretariat
});
