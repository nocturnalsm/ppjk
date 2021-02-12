<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;
use App\Models\Rekening;

class Bank extends Model
{
    protected $table  = 'bank';
    protected $primaryKey = 'BANK_ID';
    protected $guarded = ['BANK_ID'];
    public $timestamps = false;

    public static function add($fields)
	{		
        $check = Bank::select("BANK_ID")
							->where("BANK", $fields["input-bank"]);
		if ($check->count() > 0){
			throw new \Exception("Bank sudah ada");
		}		
		$data = Array("BANK" => strtoupper($fields["input-bank"]));				
		Bank::create($data);		
	}
	public static function edit($fields)
	{
        $check = Bank::select("KODE")
                        ->where("BANK", $fields["input-bank"])
                        ->where("BANK_ID" ,"<>", $fields["input-id"]);
		if ($check->count() > 0){
			throw new \Exception("Bank sudah ada");
		}		
		$data = Array("BANK" => strtoupper($fields["input-bank"]));				
		Bank::where("BANK_ID", $fields["input-id"])->update($data);		
	}
	public static function drop($id)
	{		
		$checkStat = Rekening::select("REKENING_ID")->firstWhere("BANK_ID", $id);
		if ($checkStat){
			throw new \Exception("Bank tidak dapat dihapus karena sudah dipakai di data rekening");
		}
		else {
			$data = Bank::find($id);
			if ($data){
				$data->delete();
			}			
		}
	}
}
