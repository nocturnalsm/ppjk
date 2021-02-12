<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class Importir extends Model
{
	protected $_tableName  = 'importir';
	
	public function getData($search, $start = false, $length = false, $order = Array())
	{		
		$where = $search && count($search) > 0 ? "(NAMA LIKE '%" .$search["value"] ."%' OR ALAMAT LIKE '%" .$search["value"] ."%'
									OR NPWP LIKE '%" .$search["value"] ."%' OR TELEPON LIKE '%" .$search["value"] ."%'
									OR EMAIL LIKE '%" .$search["value"] ."%')" : "";
		$strOrder = "";
		$strLimit = "";
		$columns = Array("npwp","nama","alamat","telepon","email");
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
		$data = $this->query("SELECT importir_id, npwp, nama, alamat, telepon, email FROM importir " 
							  .$where .$strOrder .$strLimit);		
		return $data;
	}	
	public function add($fields)
	{		
		$check = $this->selectBy("NPWP","LOWER(NPWP) = '" 
								.$this->escapeString(trim(strtolower($fields["input-npwp"]))) ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Data Importir dengan nomor NPWP tersebut sudah ada");
		}		
		$data = Array("npwp" => strtoupper(trim($fields["input-npwp"])),
					  "nama" => trim($fields["input-nama"]),
					  "alamat" => trim($fields["input-alamat"]),
					  "telepon" => trim($fields["input-telepon"]),
					  "email" => trim($fields["input-email"]),
					);				
		$this->save($data);		
	}
	public function edit($fields)
	{
		$check = $this->selectBy("NPWP","LOWER(NPWP) = '" 
								.$this->escapeString(trim(strtolower($fields["input-npwp"])))
								."' AND IMPORTIR_ID <> '" .$fields["input-id"] ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Data Importir tersebut sudah ada");
		}		
		$data = Array("npwp" => strtoupper(trim($fields["input-npwp"])),
					  "nama" => trim($fields["input-nama"]),
					  "alamat" => trim($fields["input-alamat"]),
					  "telepon" => trim($fields["input-telepon"]),
					  "email" => trim($fields["input-email"]),
					);
		$this->updateBy("IMPORTIR_ID", $fields["input-id"], $data);		
	}
	public function drop($id)
	{		
		$checkStat = $this->query("SELECT COUNT(*) AS used
									FROM tbl_header WHERE IMPORTIR  = '" .$id ."'
									OR IMPORTIR_BC_28 = '" .$id ."'");
		if ($checkStat->current() && $checkStat->current()->used == 0){
			$this->deleteBy("IMPORTIR_ID", $id);
		}
		else {
			throw new \Exception("Nama importir tersebut tidak dapat dihapus karena sudah dipakai di transaksi");
		}
	}
}