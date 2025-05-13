<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Jabatan;
use App\Models\Golongan;
use App\Models\Bidang;
use App\Models\Pegawai;
use App\Models\KodeSurat;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['name' => 'super-admin', 'desc' => 'semua hak akses'],
            ['name' => 'kepala', 'desc' => 'hak kepala'],
            ['name' => 'sekretariat', 'desc' => 'hak sekretariat'],
            ['name' => 'pegawai', 'desc' => 'hak pegawai'],
        ];

        foreach ($roles as $data) {
            Role::create($data);
        }

        // 2. Jabatan
        $jabatans = [
            ['nama' => 'Kepala', 'keterangan' => 'Pimpinan tertinggi'],
            ['nama' => 'Sekretariat', 'keterangan' => 'Mengelola administrasi'],
            ['nama' => 'Staf', 'keterangan' => 'Pelaksana teknis'],
        ];

        foreach ($jabatans as $data) {
            Jabatan::create($data);
        }

        // 3. Golongan
        $golongans = [
            ['nama' => 'I/A - Juru Muda', 'keterangan' => 'Juru Muda'],
            ['nama' => 'I/B - Juru Muda Tingkat I', 'keterangan' => 'Juru Muda Tingkat I'],
            ['nama' => 'I/C - Juru', 'keterangan' => 'Juru'],
            ['nama' => 'I/D - Juru Tingkat I', 'keterangan' => 'Juru Tingkat I'],
            ['nama' => 'II/A - Pengatur Muda', 'keterangan' => 'Pengatur Muda'],
            ['nama' => 'II/B - Pengatur Muda Tingkat I', 'keterangan' => 'Pengatur Muda Tingkat I'],
            ['nama' => 'II/C - Pengatur', 'keterangan' => 'Pengatur'],
            ['nama' => 'II/D - Pengatur Tingkat I', 'keterangan' => 'Pengatur Tingkat I'],
            ['nama' => 'III/A - Penata Muda', 'keterangan' => 'Penata Muda'],
            ['nama' => 'III/B - Penata Muda Tingkat I', 'keterangan' => 'Penata Muda Tingkat I'],
            ['nama' => 'III/C - Penata', 'keterangan' => 'Penata'],
            ['nama' => 'III/D - Penata Tingkat I', 'keterangan' => 'Penata Tingkat I'],
            ['nama' => 'IV/A - Pembina', 'keterangan' => 'Pembina'],
            ['nama' => 'IV/B - Pembina Tingkat I', 'keterangan' => 'Pembina Tingkat I'],
            ['nama' => 'IV/C - Pembina Utama Muda', 'keterangan' => 'Pembina Utama Muda'],
            ['nama' => 'IV/D - Pembina Utama Madya', 'keterangan' => 'Pembina Utama Madya'],
            ['nama' => 'IV/E - Pembina Utama', 'keterangan' => 'Pembina Utama'],
        ];

        foreach ($golongans as $data) {
            Golongan::create($data);
        }

        // 4. Bidang
        $bidangs = [
            ['nama' => 'Sekretariat', 'keterangan' => 'Urusan politik dan pemerintahan'],
            ['nama' => 'Tenaga Kerja', 'keterangan' => 'Urusan ketenagakerjaan'],
            ['nama' => 'Koperasi', 'keterangan' => 'Urusan pengelolaan koperasi'],
            ['nama' => 'UMKM', 'keterangan' => 'Urusan pengembangan usaha mikro'],
        ];

        foreach ($bidangs as $data) {
            Bidang::create($data);
        }

        // 5. Users dan Pegawai
        $users = [
            ['username' => 'admin', 'email' => 'admin@gmail.com', 'role' => 'super-admin'],
            ['username' => 'kepala', 'email' => 'kepala@gmail.com', 'role' => 'kepala'],
            ['username' => 'sekretariat', 'email' => 'sekretariat@gmail.com', 'role' => 'sekretariat'],
            ['username' => 'pegawai_1', 'email' => 'pegawai_1@gmail.com', 'role' => 'pegawai'],
            ['username' => 'pegawai_2', 'email' => 'pegawai_2@gmail.com', 'role' => 'pegawai'],
        ];

        foreach ($users as $index => $data) {
            $role = Role::where('name', $data['role'])->first();

            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => bcrypt('12345678'),
                'role_id' => $role->id,
            ]);

            Pegawai::create([
                'user_id' => $user->id,
                'jabatan_id' => match ($data['role']) {
                    'kepala' => 1,
                    'sekretariat' => 2,
                    default => 3,
                },
                'bidang_id' => match ($data['role']) {
                    'kepala' => 1,
                    'sekretariat' => 1,
                    default => 2 + ($index % 3), // agar pegawai menyebar ke bidang 2-4
                },
                'golongan_id' => 9 + $index, // mulai dari III/A dan variasi
                'nip' => mt_rand(1000000000, 9999999999),
                'nama_lengkap' => ucfirst(str_replace('_', ' ', $data['username'])),
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => now()->subYears(30)->toDateString(),
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Contoh Alamat No. ' . ($index + 1),
            ]);
        }

        // 6. Kode Surat
        $kodeSurats = [
            ['kode' => '133', 'keterangan' => 'Pemberitahuan'],
            ['kode' => '341', 'keterangan' => 'Undangan'],
            ['kode' => '900', 'keterangan' => 'Keuangan'],
        ];

        foreach ($kodeSurats as $data) {
            KodeSurat::create($data);
        }
    }

}
