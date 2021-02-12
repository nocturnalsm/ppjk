<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class Penerima extends Model
{
    protected $table  = 'ref_penerima';
    protected $primaryKey = 'PENERIMA_ID';
    protected $guarded = ['PENERIMA_ID'];
    public $timestamps = false;

    public static function add($fields)
	{		
        $check = Penerima::select("KODE")
							->where("KODE", $fields["input-kode"])
							->orWhere("URAIAN", $fields["input-uraian"]);
		if ($check->count() > 0){
			throw new \Exception("Referensi Penerima sudah ada");
		}		
		$data = Array("KODE" => strtoupper($fields["input-kode"]),
					  "URAIAN" => strtoupper($fields["input-uraian"]));				
		Penerima::create($data);		
	}
	public static function edit($fields)
	{
        $check = Penerima::select("KODE")
                            ->where(function($query) use ($fields){
								$query->where("KODE", $fields["input-kode"])
									  ->orWhere("URAIAN", $fields["input-uraian"]);
							})
                            ->where("PENERIMA_ID" ,"<>", $fields["input-id"]);
		if ($check->count() > 0){
			throw new \Exception("Referensi Penerima sudah ada");
		}		
		$data = Array( "KODE" => strtoupper(trim($fields["input-kode"])),
					   "URAIAN" => trim($fields["input-uraian"]));
		Penerima::where("PENERIMA_ID", $fields["input-id"])->update($data);		
	}
	public static function drop($id)
	{		
		
		$data = Penerima::find($id);
		if ($data){
			$data->delete();
		}			
	}
}
