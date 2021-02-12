<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class Kantor extends Model
{
    protected $table  = 'ref_kantor';
    protected $primaryKey = 'KANTOR_ID';
    protected $guarded = ['KANTOR_ID'];
    public $timestamps = false;

    public static function add($fields)
	{		
        $check = Kantor::select("KODE")
                            ->where("KODE", $fields["input-kode"])
                            ->orWhere("URAIAN", $fields["input-uraian"]);
		if ($check->count() > 0){
			throw new \Exception("Kode Kantor sudah ada");
		}		
		$data = Array("KODE" => strtoupper($fields["input-kode"]),
					  "URAIAN" => strtoupper($fields["input-uraian"]));				
		Kantor::create($data);		
	}
	public static function edit($fields)
	{
        $check = Kantor::select("KODE")
                            ->where(function($query) use ($fields){
                                $query->where("KODE", $fields["input-kode"])
                                      ->orWhere("URAIAN", $fields["input-uraian"]);
                            })
                            ->where("Kantor_ID" ,"<>", $fields["input-id"]);
		if ($check->count() > 0){
			throw new \Exception("Kode Kantor sudah ada");
		}		
		$data = Array( "KODE" => strtoupper(trim($fields["input-kode"])),
					   "URAIAN" => trim($fields["input-uraian"]));
		Kantor::where("Kantor_ID", $fields["input-id"])->update($data);		
	}
	public static function drop($id)
	{		
		$checkStat = Transaksi::select("ID")->firstWhere("KANTOR_ID", $id);
		if ($checkStat){
			throw new \Exception("Kode Kantor tidak dapat dihapus karena sudah dipakai di transaksi");
		}
		else {
			$data = Kantor::find($id);
			if ($data){
				$data->delete();
			}			
		}
	}
}
