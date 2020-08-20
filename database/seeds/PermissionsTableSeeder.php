<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
//        Permission::truncate();
//        Permission::firstOrCreate(['name' => 'Dashboard: View', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Pengelolaan Admin: Lihat, Tambah, Ubah, Hapus', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Hak Akses: Lihat, Tambah, Ubah, Hapus', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Universitas: Lihat', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Universitas: Tambah, Ubah, Hapus', 'guard_name' => 'admin']);

        Permission::firstOrCreate(['name' => 'Periode: Lihat', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Periode: Tambah, Ubah, Hapus', 'guard_name' => 'admin']);

        Permission::firstOrCreate(['name' => 'Pengguna: Lihat', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Pengguna: Tambah, Ubah, Hapus', 'guard_name' => 'admin']);

        Permission::firstOrCreate(['name' => 'Jadwal Ujian: Lihat Semua', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Jadwal Ujian: Lihat', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Jadwal Ujian: Ubah, Hapus', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Jadwal Ujian: Tambah', 'guard_name' => 'admin']);

        Permission::firstOrCreate(['name' => 'Peserta Ujian: Lihat', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Peserta Ujian: Tambah, Ubah, Hapus', 'guard_name' => 'admin']);

//        Permission::firstOrCreate(['name' => 'Ujian: Lihat, Tambah, Ubah, Hapus', 'guard_name' => 'admin']);
//        Permission::firstOrCreate(['name' => 'Ujian: Lihat Semua', 'guard_name' => 'admin']);
//        Permission::firstOrCreate(['name' => 'Ujian: Verifikasi Dokumen', 'guard_name' => 'admin']);
//        Permission::firstOrCreate(['name' => 'Ujian: Peserta Lulus', 'guard_name' => 'admin']);
//
//        Permission::firstOrCreate(['name' => 'Kursus: Lihat, Tambah, Ubah, Hapus', 'guard_name' => 'admin']);
//        Permission::firstOrCreate(['name' => 'Kursus: Lihat Semua', 'guard_name' => 'admin']);
//        Permission::firstOrCreate(['name' => 'Kursus: Verifikasi Dokumen', 'guard_name' => 'admin']);
//        Permission::firstOrCreate(['name' => 'Kursus: Peserta Lulus', 'guard_name' => 'admin']);

        Permission::firstOrCreate(['name' => 'Persyaratan: Lihat, Tambah, Ubah, Hapus', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Persyaratan: Lihat Semua', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Pendaftar: Lihat', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Pendaftar: Tambah, Ubah, Hapus', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Pendaftar: Validasi', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Persyaratan Lulus: Lihat, Tambah, Ubah, Hapus', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'Persyaratan Lulus: Lihat Semua', 'guard_name' => 'admin']);

    }
}
