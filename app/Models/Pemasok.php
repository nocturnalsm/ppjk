<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    protected $table  = 'plbbandu_app15.tb_pemasok';
    protected $primaryKey = 'id_pemasok';
    protected $guarded = ['id_pemasok'];
    public $timestamps = false;

    public static function add($fields)
	  {
      $check = Pemasok::select("nama_pemasok")
							->where("nama_pemasok", $fields["input-nama"]);
  		if ($check->count() > 0){
  			throw new \Exception("Data Pemasok dengan nama tersebut sudah ada");
  		}
  		$pemasok = new Pemasok;
      $pemasok->nama_pemasok = trim($fields["input-nama"]);
      $pemasok->alamat_pemasok = trim($fields["input-alamat"]);
      $pemasok->telp_pemasok = trim($fields["input-telepon"]);
      $pemasok->fax_pemasok = trim($fields["input-fax"]);
      $pemasok->negara_pemasok = intval($fields["input-negara"]);
      $pemasok->link_pemasok = trim($fields["input-link"]);
      $pemasok->status_pemasok = 1;
      $pemasok->save();
  	}
  	public static function edit($fields)
  	{
      $check = Pemasok::select("nama_pemasok")
                              ->where("nama_pemasok", $fields["input-nama"])
                              ->where("id_pemasok" ,"<>", $fields["input-id"]);
  		if ($check->count() > 0){
  			throw new \Exception("Data Pemasok dengan nama tersebut sudah ada");
  		}
  		$data = Array("nama_pemasok" => trim($fields["input-nama"]),
  					  "alamat_pemasok" => trim($fields["input-alamat"]),
  					  "telp_pemasok" => trim($fields["input-telepon"]),
              "fax_pemasok" => trim($fields["input-fax"]),
              "negara_pemasok" => $fields["input-negara"],
              "link_pemasok" => trim($fields["input-link"])
  					);
  		Pemasok::where("id_pemasok", $fields["input-id"])->update($data);
  	}
  	public static function drop($id)
  	{
  		$checkStat = Transaksi::select("ID")->firstWhere("SHIPPER", $id);
  		if ($checkStat){
  			throw new \Exception("Pemasok tidak dapat dihapus karena sudah dipakai di transaksi");
  		}
  		else {
  			$data = Pemasok::find($id);
  			if ($data){
  				$data->delete();
  			}
  		}
  	}
}
