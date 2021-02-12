<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class Kantor extends Model
{
	protected $_tableName  = 'ref_kantor';
	
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
		$data = $this->query("SELECT kantor_id, kode, uraian FROM ref_kantor " 
							  .$where .$strOrder .$strLimit);
		return $data;
	}	
	public function add($fields)
	{		
		$check = $this->selectBy("KODE","LOWER(KODE) = '" 
                                .$this->escapeString(trim(strtolower($fields["input-kode"]))) ."'
                                OR LOWER(URAIAN) = '" 
								.$this->escapeString(trim(strtolower($fields["input-uraian"]))) ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Kode Kantor tersebut sudah ada");
		}		
		$data = Array("kode" => strtoupper(trim($fields["input-kode"])),
					  "uraian" => trim($fields["input-uraian"]));				
		$this->save($data);		
	}
	public function edit($fields)
	{
		$check = $this->selectBy("KODE","(LOWER(KODE) = '" 
								.$this->escapeString(trim(strtolower($fields["input-kode"])))
                                ."') OR (LOWER(URAIAN) = '" 
								.$this->escapeString(trim(strtolower($fields["input-uraian"])))
                                ."') AND KANTOR_ID <> '" .$fields["input-id"] ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Kode Kantor tersebut sudah ada");
		}		
		$data = Array( "kode" => strtoupper(trim($fields["input-kode"])),
					   "uraian" => trim($fields["input-uraian"]));
		$this->updateBy("KANTOR_ID", $fields["input-id"], $data);		
	}
	public function drop($id)
	{		
        $checkStat = $this->query("SELECT COUNT(*) AS used
                                   FROM tbl_header WHERE KANTOR_ID  = '" .$id ."'");
		if ($checkStat->current() && $checkStat->current()->used == 0){
			$this->deleteBy("KANTOR_ID", $id);
		}
		else {
			throw new \Exception("Kode Kantor tersebut tidak dapat dihapus karena sudah dipakai di transaksi");
		}
	}
}