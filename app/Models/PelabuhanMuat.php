<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class PelabuhanMuat extends Model
{
    protected $table  = 'ref_pelmuat';
    protected $primaryKey = 'PELMUAT_ID';
    protected $guarded = ['PELMUAT_ID'];
    public $timestamps = false;

    public static function add($fields)
	{		
        $check = PelabuhanMuat::select("KODE")
                            ->where("KODE", $fields["input-kode"])
                            ->orWhere("URAIAN", $fields["input-uraian"]);
		if ($check->count() > 0){
			throw new \Exception("Pelabuhan Muat sudah ada");
		}		
		$data = Array("KODE" => strtoupper($fields["input-kode"]),
					  "URAIAN" => strtoupper($fields["input-uraian"]));				
		PelabuhanMuat::create($data);		
	}
	public static function edit($fields)
	{
        $check = PelabuhanMuat::select("KODE")
                                ->where(function($query) use ($fields){
                                    $query->where("KODE", $fields["input-kode"])
                                          ->orWhere("URAIAN", $fields["input-uraian"]);
                                })
                                ->where("PELMUAT_ID" ,"<>", $fields["input-id"]);
		if ($check->count() > 0){
			throw new \Exception("Pelabuhan Muat sudah ada");
		}		
		$data = Array( "KODE" => strtoupper(trim($fields["input-kode"])),
					   "URAIAN" => trim($fields["input-uraian"]));
		PelabuhanMuat::where("PELMUAT_ID", $fields["input-id"])->update($data);		
	}
	public static function drop($id)
	{		
		$checkStat = Transaksi::select("ID")->firstWhere("PEL_MUAT", $id);
		if ($checkStat){
			throw new \Exception("Pelabuhan Muat tidak dapat dihapus karena sudah dipakai di transaksi");
		}
		else {
			$data = PelabuhanMuat::find($id);
			if ($data){
				$data->delete();
			}			
		}
	}
}
