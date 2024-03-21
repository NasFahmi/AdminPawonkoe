<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create (['name' => 'tambah-product']);
        Permission::create(['name' => 'tambah-transaksi']);
        Permission::create(['name' => 'tambah-preorder']);
        Permission::create(['name' => 'edit-preorder']);
        Permission::create(['name' => 'edit-transaksi']);
        Permission::create(['name' => 'edit-product']);
        Permission::create(['name' => 'cetak-transaksi']);
        Permission::create(['name' => 'hapus-product']);


        Role::create(['name' => 'admin']);
        Role::create(['name' => 'superadmin']);

        $admin = Role::findByName('admin');
        $admin->givePermissionTo('tambah-product');
        $admin->givePermissionTo('tambah-transaksi');
        $admin->givePermissionTo('tambah-preorder');

        $superAdmin = Role::findByName('superadmin');
        $superAdmin->givePermissionTo('edit-preorder');
        $superAdmin->givePermissionTo('edit-transaksi');
        $superAdmin->givePermissionTo('edit-product');
        $superAdmin->givePermissionTo('hapus-product');
        $superAdmin->givePermissionTo('cetak-transaksi');
    }
}
