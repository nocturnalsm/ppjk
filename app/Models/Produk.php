<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;
use App\Models\KonversiBarang;

class Produk extends Model
{
    protected $table  = 'produk';
    protected $guarded = ['id'];
    public $timestamps = false;

    public static function add($fields)
	  {
        $check = Produk::select("id")
                            ->where("kode", $fields["input-kode"])
                            ->orWhere("nama", $fields["input-nama"]);
    		if ($check->count() > 0){
    			throw new \Exception("Produk sudah ada");
    		}
    		$data = Array("kode" => strtoupper(trim($fields["input-kode"])),
                          "nama" => strtoupper(trim($fields["input-nama"])),
                          "hscode" => strtoupper(trim($fields["input-hscode"])),
                          "harga" => str_replace(",","",(trim($fields["input-harga"]))),
                          "satuan_id" => $fields["input-satuan"],
                          "spesifikasi" => trim($fields["input-spesifikasi"]),
                          "tgl_rekam" => Date("Y-m-d", strtotime($fields["input-tglrekam"]))
                        );
    		Produk::create($data);
  	}
  	public static function edit($fields)
  	{
        $check = Produk::select("id")
                                ->where(function($query) use ($fields) {
                                    $query->where("kode", $fields["input-kode"])
                                           ->orWhere("nama", $fields["input-nama"]);
                                })
                                ->where("id" ,"<>", $fields["input-id"]);
    		if ($check->count() > 0){
    			throw new \Exception("Produk sudah ada");
    		}
    		$data = Array("kode" => strtoupper(trim($fields["input-kode"])),
                          "nama" => strtoupper(trim($fields["input-nama"])),
                          "hscode" => strtoupper(trim($fields["input-hscode"])),
                          "harga" => str_replace(",","",(trim($fields["input-harga"]))),
                          "satuan_id" => $fields["input-satuan"],
                          "spesifikasi" => trim($fields["input-spesifikasi"]),
                          "tgl_rekam" => Date("Y-m-d", strtotime($fields["input-tglrekam"]))
                        );
    		Produk::where("id", $fields["input-id"])->update($data);
  	}
  	public static function drop($id)
  	{
    		$checkStat = KonversiBarang::select("ID")->firstWhere("PRODUK_ID", $id);
    		if ($checkStat){
    			throw new \Exception("Produk tidak dapat dihapus karena sudah dipakai di transaksi");
    		}
    		else {
    			$data = Produk::find($id);
    			if ($data){
    				$data->delete();
    			}
  		  }
  	}
}
