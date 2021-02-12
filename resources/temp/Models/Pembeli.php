<?php

namespace App\Models;

use Now\System\Core\Model as Model;

class Pembeli extends Model
{
	protected $_tableName  = 'ref_pembeli';
	
	public function getData($search, $start = null, $length = null, $order = Array())
	{		
		$where = $search && count($search) > 0 ? "(KODE LIKE '%" .$search["value"] ."%' OR NAMA LIKE '%" .$search["value"] ."%')" : "";
		$strOrder = "";
		$strLimit = "";
		$columns = Array("KODE","NAMA");
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
		$data = $this->query("SELECT ID, KODE, NAMA, ALAMAT, CUSTOMER, nama_customer AS NAMACUSTOMER FROM ref_pembeli p
		                      INNER JOIN plbbandu_app15.tb_customer c ON p.CUSTOMER = c.id_customer " 
							  .$where .$strOrder .$strLimit);
		return $data;
	}	
	public function add($fields)
	{		
		$check = $this->query("SELECT nama_customer FROM plbbandu_app15.tb_customer WHERE id_customer = " .$fields["customer"]);
        $customer = $check->current()->nama_customer;
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $inisial = substr($customer,0,3);
        $kode = "";
        for ($i=0;$i<strlen($inisial);$i++){
            $kode .= str_pad(strpos($chars, $inisial[$i]) + 1, 2, "0", STR_PAD_LEFT) .".";
        }
        $kode = trim($kode, ".") ."-";
        $check = $this->query("SELECT KODE from ref_pembeli WHERE KODE LIKE '" .$kode ."%' ORDER BY KODE DESC LIMIT 1");
        if ($check->num_rows() > 0){
            $nomor = intval(substr($check->current()->KODE, -4, 4)) + 1;
            $nomor = str_pad($nomor, 4, "0", STR_PAD_LEFT);
        }
        else {
            $nomor = '0001';
        }

        $data = Array( "KODE" => $kode .$nomor,
                       "NAMA" => strtoupper(trim($fields["input-nama"])),
                       "CUSTOMER" => $fields["customer"],
					   "ALAMAT" => strtoupper(trim($fields["input-alamat"])),
					   "KETERANGAN" => strtoupper(trim($fields["input-keterangan"])),
					   "KTPNPWP" => strtoupper(trim($fields["input-ktpnpwp"]))
					  );		
		$this->save($data);		
	}
	public function edit($fields)
	{
		$data = Array( "NAMA" => strtoupper(trim($fields["input-nama"])),
					   "ALAMAT" => trim($fields["input-alamat"]),
					   "KETERANGAN" => trim($fields["input-keterangan"]),
					   "KTPNPWP" => trim($fields["input-ktpnpwp"])
					  );
		$this->updateBy("ID", $fields["input-id"], $data);		
	}
	public function drop($id)
	{		
        $checkStat = $this->query("SELECT COUNT(*) AS used
                                   FROM deliveryorder WHERE PEMBELI  = '" .$id ."'");
		if ($checkStat->current() 
			&& $checkStat->current()->used == 0){
			$this->deleteBy("ID", $id);
		}
		else {
			throw new \Exception("Pembeli tidak dapat dihapus karena sudah dipakai di transaksi");
		}
	}
}