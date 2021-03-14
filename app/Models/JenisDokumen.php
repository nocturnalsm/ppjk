<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class JenisDokumen extends Model
{
    protected $table  = 'ref_jenis_dokumen';
    protected $primaryKey = 'JENISDOKUMEN_ID';
    protected $guarded = ['JENISDOKUMEN_ID'];
    public $timestamps = false;

    public static function add($fields)
  	{
        $check = JenisDokumen::select("KODE")
                                ->where("KODE", $fields["input-kode"])
                                ->orWhere("URAIAN", $fields["input-uraian"]);
    		if ($check->count() > 0){
    			throw new \Exception("Jenis Dokumen sudah ada");
    		}
    		$data = Array("KODE" => strtoupper($fields["input-kode"]),
    					  "URAIAN" => strtoupper($fields["input-uraian"]));
    		JenisDokumen::create($data);
  	}
  	public static function edit($fields)
  	{
        $check = JenisDokumen::select("KODE")
                              ->where(function($query) use ($fields){
                                  $query->where("KODE", $fields["input-kode"])
                                        ->orWhere("URAIAN", $fields["input-uraian"]);
                              })
                              ->where("JENISDOKUMEN_ID" ,"<>", $fields["input-id"]);
    		if ($check->count() > 0){
    			throw new \Exception("Jenis Dokumen sudah ada");
    		}
    		$data = Array( "KODE" => strtoupper(trim($fields["input-kode"])),
    					   "URAIAN" => trim($fields["input-uraian"]));
    		JenisDokumen::where("JENISDOKUMEN_ID", $fields["input-id"])->update($data);
  	}
  	public static function drop($id)
  	{
    		$checkStat = Transaksi::select("ID")->firstWhere("JENIS_DOKUMEN", $id);
    		if ($checkStat){
    			throw new \Exception("Jenis Dokumen tidak dapat dihapus karena sudah dipakai di transaksi");
    		}
    		else {
    			$data = JenisDokumen::find($id);
    			if ($data){
    				$data->delete();
    			}
    		}
  	}
}
