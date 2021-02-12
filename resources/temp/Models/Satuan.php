<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class Satuan extends Model
{
	protected $_tableName  = 'satuan';
	
	public function getData($search, $start = null, $length = null, $order = Array())
	{		
		$where = $search && count($search) > 0 ? "(kode LIKE '%" .$search["value"] ."%' OR satuan LIKE '%" .$search["value"] ."%')" : "";
		$strOrder = "";
		$strLimit = "";
		$columns = Array("kode","satuan");
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
		$data = $this->query("SELECT id, kode, satuan FROM satuan " 
							  .$where .$strOrder .$strLimit);
		return $data;
	}	
	public function add($fields)
	{		
		$check = $this->selectBy("kode","LOWER(kode) = '" 
                                .$this->escapeString(trim(strtolower($fields["input-kode"]))) ."'
                                OR LOWER(satuan) = '" 
								.$this->escapeString(trim(strtolower($fields["input-satuan"]))) ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Satuan tersebut sudah ada");
		}		
		$data = Array("kode" => strtoupper(trim($fields["input-kode"])),
					  "satuan" => trim($fields["input-satuan"]));				
		$this->save($data);		
	}
	public function edit($fields)
	{
		$check = $this->selectBy("kode","(LOWER(kode) = '" 
								.$this->escapeString(trim(strtolower($fields["input-kode"])))
                                ."' OR LOWER(satuan) = '" 
								.$this->escapeString(trim(strtolower($fields["input-satuan"])))
                                ."') AND id <> '" .$fields["input-id"] ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Satuan sudah ada");
		}		
		$data = Array( "kode" => strtoupper(trim($fields["input-kode"])),
					   "satuan" => trim($fields["input-satuan"]));
		$this->updateBy("id", $fields["input-id"], $data);		
	}
	public function drop($id)
	{		
        /*$checkStat = $this->query("SELECT COUNT(*) AS used
                                   FROM tbl_header WHERE JENIS_DOKUMEN  = '" .$id ."'");
		if ($checkStat->current() 
			&& $checkStat->current()->used == 0){*/
			$this->deleteBy("id", $id);
		/*}
		else {
			throw new \Exception("Jenis Dokumen tidak dapat dihapus karena sudah dipakai di transaksi");
		}*/
	}
	public function getSatuan()
    {
        $data = $this->query("SELECT id, kode, satuan FROM satuan ORDER BY satuan");
        return $data->get();
    }
}