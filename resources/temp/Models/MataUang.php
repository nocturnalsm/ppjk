<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class MataUang extends Model
{
	protected $_tableName  = 'ref_matauang';
	
	public function getData($search, $start = null, $length = null, $order = Array())
	{		
		$where = $search && count($search) > 0 ? "(MATAUANG LIKE '%" .$search["value"] ."%'" : "";
		$strOrder = "";
		$strLimit = "";
		$columns = Array("matauang");
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
		$data = $this->query("SELECT MATAUANG_ID, MATAUANG FROM ref_matauang " 
							  .$where .$strOrder .$strLimit);
		return $data;
	}	
	public function add($fields)
	{		
		$check = $this->selectBy("MATAUANG","LOWER(MATAUANG) = '" 
                                .$this->escapeString(trim(strtolower($fields["input-matauang"]))) ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Mata Uang tersebut sudah ada");
		}		
		$data = Array("matauang" => strtoupper(trim($fields["input-matauang"])));				
		$this->save($data);		
	}
	public function edit($fields)
	{
		$check = $this->selectBy("MATAUANG","(LOWER(MATAUANG) = '" 
								.$this->escapeString(trim(strtolower($fields["input-matauang"])))
                                ."')");
		if ($check->num_rows() > 0){
			throw new \Exception("Mata Uang sudah ada");
		}		
		$data = Array( "matauang" => strtoupper(trim($fields["input-matauang"])));
		$this->updateBy("MATAUANG_ID", $fields["input-id"], $data);		
	}
	public function drop($id)
	{		
        $checkStat = $this->query("SELECT COUNT(*) AS used
                                   FROM tbl_penarikan_header WHERE MATAUANG_ID  = '" .$id ."'");
		if ($checkStat->current() 
			&& $checkStat->current()->used == 0){
			$this->deleteBy("MATAUANG_ID", $id);
		}
		else {
			throw new \Exception("Mata Uang tidak dapat dihapus karena sudah dipakai di transaksi");
		}
	}
}