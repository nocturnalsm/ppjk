<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class Produk extends Model
{
	protected $_tableName  = 'produk';

	public function getData($search, $start = null, $length = null, $order = Array())
	{		
        $where = $search && count($search) > 0 ? 
                     " AND (produk.kode LIKE '%" .$search["value"] 
                    ."%' OR nama LIKE '%" .$search["value"]
                    ."%' OR hscode LIKE '%" .$search["value"] ."%')" : "";
		$strOrder = "";
		$strLimit = "";
		$columns = Array("produk.kode","nama","hscode","satuan","harga");
		if (count($order) > 0){
			foreach ($order as $ord){
				$strOrder .= $columns[$ord["column"]] ." " .$ord["dir"] .",";
			}
			$strOrder = " ORDER BY " .substr($strOrder, 0, strlen($strOrder)-1);
		}
		if ($where != ""){
			$where = " WHERE produk.satuan_id = satuan.id" .$where;
		}
		if ($length && $length != -1){
		    $strLimit .= $length;
		}
        if ($start){
            $strLimit = $start ."," .$strLimit;
        }
		$strLimit = $strLimit != "" ? " LIMIT " .$strLimit : "";
		$data = $this->query("SELECT produk.*, satuan.satuan FROM produk,satuan " 
							  .$where .$strOrder .$strLimit);
		return $data;
	}	
	public function add($fields)
	{		
		$check = $this->selectBy("kode","LOWER(kode) = '" 
                                .$this->escapeString(trim(strtolower($fields["input-kode"]))) ."'
                                OR LOWER(nama) = '" 
								.$this->escapeString(trim(strtolower($fields["input-nama"]))) ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Produk sudah ada");
		}		
		$data = Array("kode" => strtoupper(trim($fields["input-kode"])),
                      "nama" => strtoupper(trim($fields["input-nama"])),
                      "hscode" => strtoupper(trim($fields["input-hscode"])),
                      "harga" => str_replace(",","",(trim($fields["input-harga"]))),
                      "satuan_id" => $fields["input-satuan"],
                      "spesifikasi" => trim($fields["input-spesifikasi"]),
                      "tgl_rekam" => Date("Y-m-d", strtotime($fields["input-tglrekam"]))
                    );				
		$this->save($data);		
	}
	public function edit($fields)
	{
		$check = $this->selectBy("kode","(LOWER(kode) = '" 
								.$this->escapeString(trim(strtolower($fields["input-kode"])))
                                ."' OR LOWER(nama) = '" 
								.$this->escapeString(trim(strtolower($fields["input-nama"])))
                                ."') AND id <> '" .$fields["input-id"] ."'");
		if ($check->num_rows() > 0){
			throw new \Exception("Produk sudah ada");
		}		
        $data = Array("kode" => strtoupper(trim($fields["input-kode"])),
                        "nama" => strtoupper(trim($fields["input-nama"])),
                        "hscode" => strtoupper(trim($fields["input-hscode"])),
                        "harga" => str_replace(",","",(trim($fields["input-harga"]))),
                        "satuan_id" => $fields["input-satuan"],
                        "spesifikasi" => trim($fields["input-spesifikasi"]),
                        "tgl_rekam" => Date("Y-m-d", strtotime($fields["input-tglrekam"]))
                    );
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
	public function getProduk()
    {
        $data = $this->query("SELECT produk.*, satuan.satuan FROM produk, satuan WHERE produk.satuan_id = satuan.id ORDER BY kode");
        return $data->get();
	}
	public function search($hscode, $from = "", $to = "")
	{
		$where = "";
		if ($hscode != ""){
			$where .= "hscode = '" .$hscode ."'";
		}
		if ($from != ""){
			$where .= $where != "" ? " AND " : "";
			$where .= "harga >= " .str_replace(",","", $from);
		}
		if ($to != ""){
			$where .= $where != "" ? " AND " : "";
			$where .= "harga <= " .str_replace(",","", $to);
		}
		$data = $this->query("SELECT id FROM produk WHERE $where ORDER BY id");
		if ($data){
			return $data->get();
		}
		else {
			return [];
		}
	}
	public function getDataProduk($search, $start = null, $length = null, $order = Array())
	{		
		$where = "";
		if ($search && count($search) > 0){
			$dataSearch = json_decode($search["value"]);
			if (is_array($dataSearch)){
				foreach ($dataSearch as $dt){
					$where .= "produk.id = " .$dt->id ." OR ";  
				}
			}
		}
		$where = trim($where, " OR ");
		if (trim($where) == ""){
			$where = " produk.id = 'xxx' ";
		}	
		else {
			$where = "(" .$where .")";
		}
		$where = $where != "" ? " AND " .$where : "";
		
		$strOrder = "";
		$strLimit = "";
		$columns = Array("produk.kode","nama","hscode","satuan","harga");
		if (count($order) > 0){
			foreach ($order as $ord){
				$strOrder .= $columns[$ord["column"]] ." " .$ord["dir"] .",";
			}
			$strOrder = " ORDER BY " .substr($strOrder, 0, strlen($strOrder)-1);
		}
		if ($length && $length != -1){
		    $strLimit .= $length;
		}
        if ($start){
            $strLimit = $start ."," .$strLimit;
        }
		$strLimit = $strLimit != "" ? " LIMIT " .$strLimit : "";

		$data = $this->query("SELECT produk.*, satuan.satuan FROM produk,satuan 
							   WHERE produk.satuan_id = satuan.id " 
							  .$where .$strOrder .$strLimit);
		return ['data' => $data, 'count' => $data->num_rows()];
	}
	
}