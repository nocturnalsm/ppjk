<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class JenisKemasan extends Model
{
    protected $table  = 'ref_jenis_kemasan';
    protected $primaryKey = 'JENISKEMASAN_ID';
    protected $guarded = ['JENISKEMASAN_ID'];
    public $timestamps = false;

    public static function add($fields)
	{		
        $check = JenisKemasan::select("KODE")
							->where("KODE", $fields["input-kode"])
							->orWhere("URAIAN", $fields["input-uraian"]);
		if ($check->count() > 0){
			throw new \Exception("Jenis Kemasan sudah ada");
		}		
		$data = Array("KODE" => strtoupper($fields["input-kode"]),
					  "URAIAN" => strtoupper($fields["input-uraian"]));				
		JenisKemasan::create($data);		
	}
	public static function edit($fields)
	{
        $check = JenisKemasan::select("KODE")
                            ->where(function($query) use ($fields){
								$query->where("KODE", $fields["input-kode"])
									  ->orWhere("URAIAN", $fields["input-uraian"]);
							})
                            ->where("JENISKEMASAN_ID" ,"<>", $fields["input-id"]);
		if ($check->count() > 0){
			throw new \Exception("Jenis Kemasan sudah ada");
		}		
		$data = Array( "KODE" => strtoupper(trim($fields["input-kode"])),
					   "URAIAN" => trim($fields["input-uraian"]));
		JenisKemasan::where("JENISKEMASAN_ID", $fields["input-id"])->update($data);		
	}
	public static function drop($id)
	{		
		$checkStat = Transaksi::select("ID")->firstWhere("JENIS_KEMASAN", $id);
		if ($checkStat){
			throw new \Exception("Jenis Kemasan tidak dapat dihapus karena sudah dipakai di transaksi");
		}
		else {
			$data = JenisKemasan::find($id);
			if ($data){
				$data->delete();
			}			
		}
	}
}
