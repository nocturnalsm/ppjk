<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;
use App\Models\Pembayaran;

class Rekening extends Model
{
    protected $table  = 'rekening';
    protected $primaryKey = 'REKENING_ID';
    protected $guarded = ['REKENING_ID'];
    public $timestamps = false;

    public static function add($fields)
	{		
        $check = Rekening::select("REKENING_ID")
                            ->where("NO_REKENING", $fields["input-norekening"])
                            ->where("BANK_ID", $fields["input-bank"])
                            ->where("NAMA", $fields["input-nama"]);
		if ($check->count() > 0){
			throw new \Exception("Data Rekening sudah ada");
		}		
        $data = Array("BANK_ID" => strtoupper(trim($fields["input-bank"])),
                      "NO_REKENING" => strtoupper(trim($fields["input-norekening"])),
                      "NAMA" => strtoupper(trim($fields["input-nama"])));				
      
		Rekening::create($data);		
	}
	public static function edit($fields)
	{
        $check = Rekening::select("REKENING_ID")
                            ->where("NO_REKENING", $fields["input-norekening"])
                            ->where("BANK_ID", $fields["input-bank"])
                            ->where("NAMA", $fields["input-nama"])                            
                            ->where("REKENING_ID" ,"<>", $fields["input-id"]);
		if ($check->count() > 0){
			throw new \Exception("Data Rekening sudah ada");
		}		
		$data = Array("BANK_ID" => strtoupper(trim($fields["input-bank"])),
                      "NO_REKENING" => strtoupper(trim($fields["input-norekening"])),
                      "NAMA" => strtoupper(trim($fields["input-nama"])));							
		Rekening::where("REKENING_ID", $fields["input-id"])->update($data);		
	}
	public static function drop($id)
	{		
		$checkStat = Pembayaran::select("ID")->firstWhere("REKENING_ID", $id);
		if ($checkStat){
			throw new \Exception("Rekening tidak dapat dihapus karena sudah dipakai di transaksi");
		}
		else {
			$data = Rekening::find($id);
			if ($data){
				$data->delete();
			}			
		}
	}
}
