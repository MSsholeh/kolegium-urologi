<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role = Role::firstOrCreate(['name' => 'Superadmin', 'guard_name' => 'admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin Universitas', 'guard_name' => 'admin']);

        $permissions = Permission::get();
        $role->syncPermissions($permissions);

        $admin->givePermissionTo(['Universitas: Lihat', 'Periode: Lihat', 'Pengguna: Lihat', 'Peserta Ujian: Lihat', 'Jadwal Ujian: Lihat', 'Persyaratan: Lihat, Tambah, Ubah, Hapus', 'Pendaftar: Lihat', 'Pendaftar: Validasi']);
    }
}
