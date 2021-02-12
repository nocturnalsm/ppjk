<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;
use App\Models\KonversiBarang;

class Rate extends Model
{
    protected $table  = 'ref_rate';
    protected $primaryKey = 'RATE_ID';
    public $timestamps = false;

    public static function add($fields)
	{		
        $rate = filter_var($fields['input-rate'], FILTER_SANITIZE_NUMBER_INT);
        if (!$rate){
            throw new \Exception('Harap masukkan angka yang benar');
        }
        $check = Rate::select("RATE")
							->where("RATE", $rate);
		if ($check->count() > 0){
			throw new \Exception("Rate sudah ada");
		}		
        $data = new Rate;
        $data->RATE = $rate;				
		$data->save();
	}
	public static function edit($fields)
	{
        $rate = filter_var($fields['input-rate'], FILTER_SANITIZE_NUMBER_INT);
        if (!$rate){
            throw new \Exception('Harap masukkan angka yang benar');
        }
        $check = Rate::select("RATE")
                        ->where("RATE", $rate)
                        ->where("RATE_ID" ,"<>", $fields["input-id"]);
		if ($check->count() > 0){
			throw new \Exception("Rate sudah ada");
		}		
        $data = Rate::find($fields["input-id"]);
        if ($data){
            $data->RATE = $rate;				
            $data->save();
        }
	}
	public static function drop($id)
	{		
		$checkStat = KonversiBarang::select("ID")->firstWhere("RATE", $id);
		if ($checkStat){
			throw new \Exception("Rate tidak dapat dihapus karena sudah dipakai di transaksi");
		}
		else {
			$data = Rate::where('RATE', $id);
			if ($data){
				$data->delete();
			}			
		}
	}
}
