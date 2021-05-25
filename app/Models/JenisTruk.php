<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;
use DB;

class JenisTruk extends Model
{
    protected $table  = 'ref_jenis_truk';
    protected $primaryKey = 'JENISTRUK_ID';
    protected $guarded = ['JENISTRUK_ID'];
    public $timestamps = false;

    public static function add($fields)
	  {
        $check = JenisTruk::select("JENIS_TRUK")
							            ->where("JENIS_TRUK", $fields["input-jenis"]);
    		if ($check->count() > 0){
    			throw new \Exception("Jenis Truk sudah ada");
    		}
    		$data = Array("JENIS_TRUK" => strtoupper($fields["input-jenis"]));
    		JenisTruk::create($data);
  	}
  	public static function edit($fields)
  	{
        $check = JenisTruk::select("KODE")
                          ->where("JENIS_TRUK", $fields["input-jenis"])
                          ->where("JENISTRUK_ID" ,"<>", $fields["input-id"]);
    		if ($check->count() > 0){
    			throw new \Exception("Jenis Truk sudah ada");
    		}
    		$data = Array("JENIS_TRUK" => strtoupper($fields["input-jenis"]));
    		JenisTruk::where("JENISTRUK_ID", $fields["input-id"])->update($data);
  	}
  	public static function drop($id)
  	{
    		$checkStat = DB::table("tbl_detail_pengeluaran")
                     ->select("ID")->where("JENISTRUK_ID", $id);
    		if ($checkStat->exists()){
    			throw new \Exception("Jenis Truk tidak dapat dihapus karena sudah dipakai di transaksi");
    		}
    		else {
    			$data = JenisTruk::find($id);
    			if ($data){
    				$data->delete();
    			}
    		}
  	}
}
