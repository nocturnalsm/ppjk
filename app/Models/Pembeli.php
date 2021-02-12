<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;
use App\Models\DeliveryOrder;
use DB;

class Pembeli extends Model
{
	protected $table  = 'ref_pembeli';
	protected $primaryKey = "ID";
    protected $guarded = ['ID'];
    public $timestamps = false;

    public static function add($fields)
	{		
		$check = DB::table('plbbandu_app15.tb_customer')
					->select('nama_customer') 
					->where('id_customer', $fields["customer"])->first();
		if (!$check){
			throw new \Exception('Customer tidak ditemukan');
		}
        $customer = $check->nama_customer;
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $inisial = substr($customer,0,3);
        $kode = "";
        for ($i=0;$i<strlen($inisial);$i++){
            $kode .= str_pad(strpos($chars, $inisial[$i]) + 1, 2, "0", STR_PAD_LEFT) .".";
        }
        $kode = trim($kode, ".") ."-";
		$check = Pembeli::select("KODE")
						->firstWhere(DB::raw("KODE LIKE '" .$kode ."%'"));
        if ($check){
            $nomor = intval(substr($check->KODE, -4, 4)) + 1;
            $nomor = str_pad($nomor, 4, "0", STR_PAD_LEFT);
        }
        else {
            $nomor = '0001';
        }

        $data = Array( "KODE" => $kode .$nomor,
                       "NAMA" => strtoupper(trim($fields["input-nama"])),
                       "CUSTOMER" => $fields["customer"],
					   "ALAMAT" => strtoupper(trim($fields["input-alamat"])),
					   "KETERANGAN" => strtoupper(trim($fields["input-keterangan"])),
					   "KTPNPWP" => strtoupper(trim($fields["input-ktpnpwp"]))
					  );		
		Pembeli::create($data);		
	}
	public static function edit($fields)
	{
		$data = Array( "NAMA" => strtoupper(trim($fields["input-nama"])),
					   "ALAMAT" => trim($fields["input-alamat"]),
					   "KETERANGAN" => trim($fields["input-keterangan"]),
					   "KTPNPWP" => trim($fields["input-ktpnpwp"])
					  );
		Pembeli::where("ID", $fields["input-id"])->update($data);		
	}
	public static function drop($id)
	{		
        $checkStat = DeliveryOrder::firstWhere("PEMBELI", $id);
		if (!$checkStat){
			$data = Pembeli::find($id);
			if ($data){
				$data->delete();
			}
		}
		else {
			throw new \Exception("Pembeli tidak dapat dihapus karena sudah dipakai di transaksi");
		}
	}
}
