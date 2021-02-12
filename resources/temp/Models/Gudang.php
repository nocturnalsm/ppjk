<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class Gudang extends Model
{
	protected $_tableName  = 'ref_gudang';
	
	public function getData($search, $start = null, $length = null, $order = Array())
	{		
		$where = $search && count($search) > 0 ? "(KODE LIKE '%" .$search["value"] ."%' OR URAIAN LIKE '%" .$search["value"] ."%')" : "";
		$strOrder = "";
		$strLimit = "";
		$columns = Array("kode","uraian");
		if (count($order) > 0){
			foreach ($order as $ord){
				$strOrder .= $columns[$ord["column"]] ." " .$ord["dir"] .",";
			}
			$strOrder = " ORDER BY " .substr($strOrder, 0, strlen($strOrder)-1);
		}
		if ($where != ""){
			$where = " WHERE " .$where;
		}
		if ($length && $length != -1){
		    $strLimit .= $length;
		}
        if ($start){
            $strLimit = $start ."," .$strLimit;
        }
		$strLimit = $strLimit != "" ? " LIMIT " .$strLimit : "";		
		$data = $this->query("SELECT gudang_id, kode, uraian FROM ref_gudang " 
							  .$where .$strOrder .$strLimit);
		return $data;
	}	
	public function add($fields)
	{		
		$check = $this->selectBy("KODE","LOWER(KODE) = '" 
								.$this->escapeString(trim(strtolower($fields["input-kode"]))) ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Data Gudang sudah ada");
		}		
		$data = Array("kode" => strtoupper(trim($fields["input-kode"])),
					  "uraian" => trim($fields["input-uraian"]));				
		$this->save($data);		
	}
	public function edit($fields)
	{
		$check = $this->selectBy("KODE","LOWER(KODE) = '" 
								.$this->escapeString(trim(strtolower($fields["input-kode"])))
								."' AND GUDANG_ID <> '" .$fields["input-id"] ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Data Gudang sudah ada");
		}		
		$data = Array( "kode" => strtoupper(trim($fields["input-kode"])),
					   "uraian" => trim($fields["input-uraian"]));
		$this->updateBy("GUDANG_ID", $fields["input-id"], $data);		
	}
	public function drop($id)
	{		
		$checkStat = $this->query("SELECT COUNT(*) AS used
                                   FROM tbl_penarikan_header WHERE GUDANG_ID  = '" .$id ."'");
		if ($checkStat->current() && $checkStat->current()->used == 0){
			$this->deleteBy("GUDANG_ID", $id);
		}
		else {
			throw new \Exception("Gudang tidak dapat dihapus karena sudah dipakai di transaksi");
		}
	}
}