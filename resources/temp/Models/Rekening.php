<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class Rekening extends Model
{
	protected $_tableName  = 'rekening';
	
	public function getData($search, $start = null, $length = null, $order = Array())
	{		
		$where = $search && count($search) > 0 ? "(BANK LIKE '%" .$search["value"] ."%' OR NO_REKENING LIKE '%" .$search["value"] ."%' OR NAMA LIKE '%" .$search["value"] ."%')" : "";
		$strOrder = "";
		$strLimit = "";
		$columns = Array("BANK","NO_REKENING","NAMA");
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
		$data = $this->query("SELECT REKENING_ID, bank.BANK_ID, BANK, NO_REKENING, NAMA FROM 
                              rekening INNER JOIN bank ON rekening.BANK_ID = bank.BANK_ID " 
                              .$where .$strOrder .$strLimit);        
		return $data;
	}	
	public function add($fields)
	{		
		$check = $this->selectBy("NO_REKENING","LOWER(NO_REKENING) = '" 
                                .$this->escapeString(trim(strtolower($fields["input-norekening"]))) 
                                ."' AND BANK_ID = '" .$this->escapeString($fields["input-bank"])
                                ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Data Rekening tersebut sudah ada");
		}		
        $data = Array("BANK_ID" => strtoupper(trim($fields["input-bank"])),
                      "NO_REKENING" => strtoupper(trim($fields["input-norekening"])),
                      "NAMA" => strtoupper(trim($fields["input-nama"])));				
		$this->save($data);		
	}
	public function edit($fields)
	{
		$check = $this->selectBy("NO_REKENING","LOWER(NO_REKENING) = '" 
                                .$this->escapeString(trim(strtolower($fields["input-norekening"]))) 
                                ."' AND BANK_ID = '" .$this->escapeString($fields["input-bank"])
                                ."' AND REKENING_ID <> '" .$fields["input-id"] ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Data Rekening sudah ada");
		}		
        $data = Array("BANK_ID" => strtoupper(trim($fields["input-bank"])),
                      "NO_REKENING" => strtoupper(trim($fields["input-norekening"])),
                      "NAMA" => strtoupper(trim($fields["input-nama"])));				
		$this->updateBy("REKENING_ID", $fields["input-id"], $data);		
	}
	public function drop($id)
	{		
        $checkStat = $this->query("SELECT COUNT(*) AS used
                                   FROM tbl_penarikan_header WHERE REKENING_ID  = '" .$id ."'");
		if ($checkStat->current() 
			&& $checkStat->current()->used == 0){
			$this->deleteBy("REKENING_ID", $id);
		}
		else {
			throw new \Exception("Rekening tidak dapat dihapus karena sudah dipakai di transaksi");
		}
	}
}