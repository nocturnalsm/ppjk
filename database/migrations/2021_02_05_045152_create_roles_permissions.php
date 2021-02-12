<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRolesPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = Array(
                  //['name' => 'Super Admin'],
                  ['name' => 'admin'],
                  ['name' => 'do'],
                  ['name' => 'vo'],
                  ['name' => 'accounting'],
                  ['name' => 'company'],
                  ['name' => 'inventory'],
                  ['name' => 'gudang'],
                  ['name' => 'quota']
                );
        $permissions = Array(
                //['name' => 'customer.view'],
                ['name' => 'schedule.transaksi'],
                ['name' => 'schedule.hapus'],
                ['name' => 'vo.browse'],
                ['name' => 'vo.transaksi'],
                ['name' => 'dokumen.browse'],
                ['name' => 'dokumen.transaksi'],
                ['name' => 'barang.browse'],
                ['name' => 'barang.transaksi'],
                ['name' => 'sptnp.browse'],
                ['name' => 'quota.transaksi'],
                ['name' => 'quota.browse'],
                ['name' => 'schedule.browse'],
                ['name' => 'schedule.cari'],
                ['name' => 'schedule.carikontainer'],
                ['name' => 'konversi.transaksi'],
                ['name' => 'konversi.browse'],
                ['name' => 'cari_produk'],
                ['name' => 'deliveryorder'],
                ['name' => 'pembayaran.tranksasi'],
                ['name' => 'pembayaran.browse'],
                ['name' => 'kartu_hutang'],
                ['name' => 'stokperproduk'],
                ['name' => 'stokperbarang'],
                ['name' => 'master.produk.browse'],
                ['name' => 'master.satuan.browse'],
                ['name' => 'master.importir.browse'],
                ['name' => 'master.jenisbarang.browse'],
                ['name' => 'master.pelmuat.browse'],
                ['name' => 'master.jeniskemasan.browse'],
                ['name' => 'master.jenisdokumen.browse'],
                ['name' => 'master.kantor.browse'],
                ['name' => 'master.dpp'],
                ['name' => 'master.bank'],
                ['name' => 'master.rekening'],
                ['name' => 'penerima'],
                ['name' => 'pembeli'],
                ['name' => 'users.list'],
                ['name' => 'roles.list'],
                ['name' => 'profile']
        );
        foreach($roles as $role){
            Role::create($role);
        }
        foreach($permissions as $perm){
            Permission::create($perm);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_permissions');
    }
}
