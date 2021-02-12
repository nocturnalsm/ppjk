<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class Importir extends Model
{
    protected $table  = 'importir';
    protected $primaryKey = 'IMPORTIR_ID';
    protected $guarded = ['IMPORTIR_ID'];
    public $timestamps = false;

    public static function add($fields)
	{		
        $check = Importir::select("NPWP")
							->where("NPWP", $fields["input-npwp"]);
		if ($check->count() > 0){
			throw new \Exception("Data Importir dengan npwp tersebut sudah ada");
		}		
		$data = Array("NPWP" => strtoupper(trim($fields["input-npwp"])),
					  "NAMA" => trim($fields["input-nama"]),
					  "ALAMAT" => trim($fields["input-alamat"]),
					  "TELEPON" => trim($fields["input-telepon"]),
					  "EMAIL" => trim($fields["input-email"]),
					);							
		Importir::create($data);		
	}
	public static function edit($fields)
	{
        $check = Importir::select("NPWP")
                            ->where("NPWP", $fields["input-npwp"])
                            ->where("IMPORTIR_ID" ,"<>", $fields["input-id"]);
		if ($check->count() > 0){
			throw new \Exception("Data Importir dengan npwp tersebut sudah ada");
		}		
		$data = Array("NPWP" => strtoupper(trim($fields["input-npwp"])),
					  "NAMA" => trim($fields["input-nama"]),
					  "ALAMAT" => trim($fields["input-alamat"]),
					  "TELEPON" => trim($fields["input-telepon"]),
					  "EMAIL" => trim($fields["input-email"]),
					);							
		Importir::where("IMPORTIR_ID", $fields["input-id"])->update($data);		
	}
	public static function drop($id)
	{		
		$checkStat = Transaksi::select("ID")->firstWhere("IMPORTIR", $id);
		if ($checkStat){
			throw new \Exception("Importir tidak dapat dihapus karena sudah dipakai di transaksi");
		}
		else {
			$data = Importir::find($id);
			if ($data){
				$data->delete();
			}			
		}
	}
}
