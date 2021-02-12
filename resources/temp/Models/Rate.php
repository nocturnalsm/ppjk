<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class Rate extends Model
{
	protected $_tableName  = 'ref_rate';
	
	public function getData($search, $start = null, $length = null, $order = Array())
	{		
		$where = $search && count($search) > 0 ? "(RATE LIKE '%" .$search["value"] ."%')" : "";
		$strOrder = "";
		$strLimit = "";
		$columns = Array("RATE");
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
		$data = $this->query("SELECT RATE_ID, RATE FROM ref_rate " 
							  .$where .$strOrder .$strLimit);
		return $data;
	}	
	public function add($fields)
	{		
		$check = $this->selectBy("RATE","RATE = '" 
                                .$this->escapeString(trim(strtolower($fields["input-rate"]))) ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Rate sudah ada");
		}		
		$data = Array("RATE" => strtoupper(trim($fields["input-rate"])));				
		$this->save($data);		
	}
	public function edit($fields)
	{
		$check = $this->selectBy("RATE", "RATE = '" 
								.$this->escapeString(trim(strtolower($fields["input-rate"])))
                                ."' AND RATE_ID <> '" .$fields["input-id"] ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Rate sudah ada");
		}		
		$data = Array( "RATE" => strtoupper(trim($fields["input-rate"])));
		$this->updateBy("RATE_ID", $fields["input-id"], $data);		
	}
	public function drop($id)
	{		
        $checkStat = $this->query("SELECT COUNT(*) AS used
                                   FROM tbl_konversi WHERE RATE  = '" .$id ."'");
		if ($checkStat->current() 
			&& $checkStat->current()->used == 0){
			$this->deleteBy("RATE", $id);
		}
		else {
			throw new \Exception("Rate tidak dapat dihapus karena sudah dipakai di transaksi");
		}
	}
}