<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class Ekspedisi extends Model
{
    protected $table  = 'ekspedisi';
    protected $primaryKey = 'EKSPEDISI_ID';
    protected $guarded = ['EKSPEDISI_ID'];
    public $timestamps = false;

    public static function add($fields)
	  {
        $check = Ekspedisi::select("KODE")
							->where("KODE", $fields["input-kode"])
              ->orWhere("NAMA", $fields["input-nama"]);
    		if ($check->count() > 0){
    			throw new \Exception("Data Ekspedisi dengan kode tersebut sudah ada");
    		}
    		$data = Array("KODE" => strtoupper(trim($fields["input-kode"])),
    					  "NAMA" => trim($fields["input-nama"]),
    					  "ALAMAT" => trim($fields["input-alamat"]),
    					  "TELEPON" => trim($fields["input-telepon"])
    					);
    		Ekspedisi::create($data);
  	}
  	public static function edit($fields)
  	{
        $check = Ekspedisi::select("KODE")
                              ->where(function($query) use ($fields){
                                  $query->where("KODE", $fields["input-kode"])
                                        ->orWhere("NAMA", $fields["input-nama"]);
                              })
                              ->where("EKSPEDISI_ID" ,"<>", $fields["input-id"]);
    		if ($check->count() > 0){
    			throw new \Exception("Data Ekspedisi dengan kode tersebut sudah ada");
    		}
    		$data = Array("KODE" => strtoupper(trim($fields["input-kode"])),
    					  "NAMA" => trim($fields["input-nama"]),
    					  "ALAMAT" => trim($fields["input-alamat"]),
    					  "TELEPON" => trim($fields["input-telepon"])
    					);
    		Ekspedisi::where("EKSPEDISI_ID", $fields["input-id"])->update($data);
  	}
  	public static function drop($id)
  	{
        $checkStat = DB::table("tbl_detail_pengeluaran")
                   ->select("ID")->where("EKSPEDISI_ID", $id);
    		if ($checkStat->exists()){
    			throw new \Exception("Ekspedisi tidak dapat dihapus karena sudah dipakai di transaksi");
    		}
    		else {
    			$data = Ekspedisi::find($id);
    			if ($data){
    				$data->delete();
    			}
    		}
  	}
}
