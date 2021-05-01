<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $table  = 'ref_gudang';
    protected $primaryKey = 'GUDANG_ID';
    protected $guarded = ['GUDANG_ID'];
    public $timestamps = false;

    public static function add($fields)
  	{
      $check = Gudang::select("KODE")
  							->where("KODE", $fields["input-kode"])
  							->orWhere("URAIAN", $fields["input-uraian"]);
  		if ($check->count() > 0){
  			   throw new \Exception("Data Gudang sudah ada");
  		}
  		$data = Array("KODE" => strtoupper($fields["input-kode"]),
  					  "URAIAN" => strtoupper($fields["input-uraian"]));
  		Gudang::create($data);
  	}
  	public static function edit($fields)
  	{
      $check = Gudang::select("KODE")
                          ->where(function($query) use ($fields){
                        			$query->where("KODE", $fields["input-kode"])
                        					  ->orWhere("URAIAN", $fields["input-uraian"]);
                        			}
                          )
                          ->where("GUDANG_ID" ,"<>", $fields["input-id"]);
  		if ($check->count() > 0){
  			throw new \Exception("Data Gudang sudah ada");
  		}
  		$data = Array( "KODE" => strtoupper(trim($fields["input-kode"])),
  					   "URAIAN" => trim($fields["input-uraian"]));
  		Gudang::where("GUDANG_ID", $fields["input-id"])->update($data);
  	}
  	public static function drop($id)
  	{
  		$checkStat = DB::table("kontainer_masuk")
                     ->select("ID")->firstWhere("GUDANG_ID", $id);
  		if ($checkStat){
  			throw new \Exception("Jenis Barang tidak dapat dihapus karena sudah dipakai di transaksi");
  		}
  		else {
  			$data = Gudang::find($id);
  			if ($data){
  				$data->delete();
  			}
  		}
  	}
}
