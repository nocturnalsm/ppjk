<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class JenisBarang extends Model
{
    protected $table  = 'ref_jenis_barang';
    protected $primaryKey = 'JENISBARANG_ID';
    protected $guarded = ['JENISBARANG_ID'];
    public $timestamps = false;

    public static function add($fields)
	{		
        $check = JenisBarang::select("KODE")
							->where("KODE", $fields["input-kode"])
							->orWhere("URAIAN", $fields["input-uraian"]);
		if ($check->count() > 0){
			throw new \Exception("Jenis Barang sudah ada");
		}		
		$data = Array("KODE" => strtoupper($fields["input-kode"]),
					  "URAIAN" => strtoupper($fields["input-uraian"]));				
		JenisBarang::create($data);		
	}
	public static function edit($fields)
	{
        $check = JenisBarang::select("KODE")
                            ->where(function($query) use ($fields){
								$query->where("KODE", $fields["input-kode"])
									  ->orWhere("URAIAN", $fields["input-uraian"]);
							})
                            ->where("JENISBARANG_ID" ,"<>", $fields["input-id"]);
		if ($check->count() > 0){
			throw new \Exception("Jenis Barang sudah ada");
		}		
		$data = Array( "KODE" => strtoupper(trim($fields["input-kode"])),
					   "URAIAN" => trim($fields["input-uraian"]));
		JenisBarang::where("JENISBARANG_ID", $fields["input-id"])->update($data);		
	}
	public static function drop($id)
	{		
		$checkStat = Transaksi::select("ID")->firstWhere("JENIS_BARANG", $id);
		if ($checkStat){
			throw new \Exception("Jenis Barang tidak dapat dihapus karena sudah dipakai di transaksi");
		}
		else {
			$data = JenisBarang::find($id);
			if ($data){
				$data->delete();
			}			
		}
	}
}
