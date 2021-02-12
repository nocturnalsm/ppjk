<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class Bank extends Model
{
	protected $_tableName  = 'bank';
	
	public function getData($search, $start = null, $length = null, $order = Array())
	{		
		$where = $search && count($search) > 0 ? "BANK LIKE '%" .$search["value"] ."%'" : "";
		$strOrder = "";
		$strLimit = "";
		$columns = Array("BANK");
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
		$data = $this->query("SELECT BANK_ID, BANK FROM bank " 
                              .$where .$strOrder .$strLimit);
		return $data;
	}	
	public function add($fields)
	{		
		$check = $this->selectBy("BANK","LOWER(BANK) = '" 
                                .$this->escapeString(trim(strtolower($fields["input-bank"]))) ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Data Bank tersebut sudah ada");
		}		
		$data = Array("BANK" => strtoupper(trim($fields["input-bank"])));				
		$this->save($data);		
	}
	public function edit($fields)
	{
		$check = $this->selectBy("BANK","(LOWER(BANK) = '" 
								.$this->escapeString(trim(strtolower($fields["input-bank"])))
                                ."' AND BANK_ID <> '" .$fields["input-id"] ."')");
		if ($check->num_rows() > 0){
			throw new \Exception("Data Bank sudah ada");
		}		
		$data = Array( "BANK" => strtoupper(trim($fields["input-bank"])));
		$this->updateBy("BANK_ID", $fields["input-id"], $data);		
	}
	public function drop($id)
	{		
        $checkStat = $this->query("SELECT COUNT(*) AS used
                                   FROM tbl_penarikan_header WHERE BANK_ID  = '" .$id ."'");
		if ($checkStat->current() 
			&& $checkStat->current()->used == 0){
			$this->deleteBy("BANK_ID", $id);
		}
		else {
			throw new \Exception("Bank tidak dapat dihapus karena sudah dipakai di transaksi");
		}
	}
}