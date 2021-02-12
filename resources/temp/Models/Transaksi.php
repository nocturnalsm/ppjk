<?php

namespace App\Models;

use Now\System\Core\Model as Model;

	
class Transaksi extends Model
{
    public function getJenisKemasan()
    {
        $data = $this->query("SELECT * FROM ref_jenis_kemasan ORDER BY uraian");
        return $data->get();
    }
    public function getCustomer($id = "")
    {
        if ($id == ""){
            $data = $this->query("SELECT * FROM plbbandu_app15.tb_customer ORDER BY nama_customer");
            return $data->get();    
        }
        else {
            $data = $this->query("SELECT * FROM plbbandu_app15.tb_customer WHERE id_customer = '" .$id ."'");
            return $data->current();
        }
    }
    public function getJumlahKontainer()
    {
        $data = $this->query("SELECT * FROM ref_jumlah_kontainer ORDER BY CAST(jumlah AS unsigned)");
        return $data->get();
    }
    public function getImportir($id = "", $list = false)
    {
        if ($id == ""){
            $data = $this->query("SELECT * FROM importir ORDER BY nama");
            return $data->get();    
        }
        else {
            $data = $this->query("SELECT * FROM importir WHERE IMPORTIR_ID = '" .$id ."'");
            return $list ? $data->get() : $data->current();
        }
    }
    public function getPembeli($id = "", $list = false)
    {
        if ($id == ""){
            $data = $this->query("SELECT * FROM ref_pembeli ORDER BY NAMA, KODE");
            return $data->get();    
        }
        else {
            $data = $this->query("SELECT * FROM ref_pembeli WHERE ID = '" .$id ."'");
            return $list ? $data->get() : $data->current();
        }
    }
    public function getJenisBarang()
    {
        $data = $this->query("SELECT * FROM ref_jenis_barang ORDER BY uraian");
        return $data->get();
    }
    public function getStatusRevisi()
    {
        $data = $this->query("SELECT * FROM ref_status_revisi ORDER BY statusrevisi_id");
        return $data->get();
    }
    public function getGudang($id = "")
    {
        if ($id == ""){
            $data = $this->query("SELECT * FROM ref_gudang ORDER BY KODE");
            return $data->get();    
        }
        else {
            $data = $this->query("SELECT * FROM ref_gudang WHERE GUDANG_ID = '" .$id ."'");
            return $data->current();
        }
    }
    public function getPelmuat()
    {
        $data = $this->query("SELECT * FROM ref_pelmuat ORDER BY uraian");
        return $data->get();
    }
    public function getShipper($shipper = "")
    {
        if ($shipper == ""){
            $data = $this->query("SELECT * FROM plbbandu_app15.tb_pemasok ORDER BY nama_pemasok");
            return $data->get();
        }
        else {
            $data = $this->query("SELECT * FROM plbbandu_app15.tb_pemasok where id_pemasok = '$shipper'");
            if ($data->num_rows() > 0){
                return $data->current();
            }
            else {
                return false;
            }
        }
    }
    public function getUkuranKontainer()
    {
        $data = $this->query("SELECT * FROM ref_ukuran_kontainer ORDER BY uraian");
        return $data->get();
    }
    public function getJenisDokumen()
    {
        $data = $this->query("SELECT * FROM ref_jenis_dokumen ORDER BY uraian");
        return $data->get();
    }
    public function getJenisFile()
    {
        $data = $this->query("SELECT * from jenisfile order by JENIS");
        return $data->get();
    }
    public function getPenerima()
    {
        $data = $this->query("SELECT * FROM ref_penerima ORDER BY uraian");
        return $data->get();
    }
    public function getKantor($id = "")
    {
        if ($id == ""){
            $data = $this->query("SELECT * FROM ref_kantor ORDER BY uraian");
            return $data->get();    
        }
        else {
            $data = $this->query("SELECT * FROM ref_kantor WHERE KANTOR_ID = '" .$id ."'");
            return $data->current();    
        }
    }
    public function getMataUang()
    {
        $data = $this->query("SELECT * FROM ref_matauang ORDER BY MATAUANG");
        return $data->get();
    }    
    public function getTOP()
    {
        $data = $this->query("SELECT * FROM ref_top ORDER BY TOP_ID");
        return $data->get();
    }
    public function getBank()
    {
        $data = $this->query("SELECT * FROM bank ORDER BY BANK");
        return $data->get();
    }
    public function getRekening()
    {
        $data = $this->query("SELECT r.*, b.BANK FROM rekening r, bank b
                              WHERE r.BANK_ID = b.BANK_ID ORDER BY NO_REKENING");
        return $data->get();
    }
    public function getInv($value)
    {
        $data = $this->query("SELECT c.nama_customer, h.NO_INV, h.ID, h.NOAJU, 
                              h.NOPEN, h.TGL_NOPEN, imp.NAMA as NAMAIMPORTIR, 
                              shipper.nama_pemasok AS NAMASHIPPER 
                              FROM tbl_penarikan_header h 
                              INNER JOIN plbbandu_app15.tb_customer c 
                              on c.id_customer = h.CUSTOMER 
                              INNER JOIN importir imp on imp.IMPORTIR_ID = h.IMPORTIR 
                              INNER JOIN plbbandu_app15.tb_pemasok shipper 
                              on shipper.id_pemasok = h.SHIPPER
                              WHERE NO_INV = '" .$value ."'");
        //echo $data->printquery();
        if ($data->num_rows() > 0){
            return $data->current();
        }
        else {
            return false;
        }
    }
    public function getKodeBarang($value)
    {
        $data = $this->query("SELECT db.ID, p.kode, dk.HARGAJUAL 
                              FROM tbl_detail_barang db 
                              INNER JOIN tbl_konversi dk 
                              on db.ID = dk.ID_HEADER 
                              INNER JOIN produk p on p.id = dk.PRODUK_ID
                              WHERE db.KODEBARANG = '" .$value ."'");
        //echo $data->printquery();
        if ($data->num_rows() > 0){
            return $data->current();
        }
        else {
            return false;
        }
    }
    public function search($term, $searchtype, $filter)
    {
        if (trim($term) == ""){
            return [];
        }
        $whereFilter = "";
        if (isset($filter)){
            /*
            $whereFilter .= $filter["kantor"] != "" ? " AND h.KANTOR_ID = '" .$filter["kantor"] ."'" : "";
            if ($filter["tgltiba1"] != "" && $filter["tgltiba2"] != ""){
                $whereFilter .= " AND (TGL_TIBA BETWEEN '" .Date("Y-m-d", strtotime($filter["tgltiba1"])) 
                           ."' AND '" .Date("Y-m-d", strtotime($filter["tgltiba2"])) ."')"; 
            }
            if ($filter["tglbongkar1"] != "" && $filter["tglbongkar2"] != ""){
                $whereFilter .= " AND (TGL_BONGKAR BETWEEN '" .Date("Y-m-d", strtotime($filter["tglbongkar1"])) 
                           ."' AND '" .Date("Y-m-d", strtotime($filter["tglbongkar2"])) ."')";    
            }
            */
            $whereFilter .= $filter["customer"] != "" ? " AND CUSTOMER = '" .$filter["customer"] ."'" : "";
        }
        //echo $whereFilter;die();
        if ($searchtype == "kontainer"){
            $data = $this->query("SELECT id_header AS id FROM tbl_penarikan_kontainer
                                  WHERE NOMOR_KONTAINER = '" .$term ."'");
        }
        else {
            $data = $this->query("SELECT h.id FROM tbl_penarikan_header h 
                        LEFT JOIN ref_kantor k on h.kantor_id = k.kantor_id
                        LEFT JOIN plbbandu_app15.tb_customer c on c.id_customer = h.customer
                        WHERE (NO_BL LIKE '%" .$term 
                            ."%' OR NOAJU LIKE '%" .$term
                            ."%' OR NOPEN LIKE '%" .$term
                            ."%' OR NO_INV LIKE '%" .$term
                            ."%'"
                            .(ctype_digit($term) ? " OR h.JUMLAH_KEMASAN = " .$term ." " : "") 
                            .") " .$whereFilter); 
        }        		
        if ($data->num_rows() > 0){
            return $data->get();
        }
        else {
            return [];
        }
    }
    public function searchsptnp($nopen, $tglnopen)
    {
        $data = $this->query("SELECT ID FROM tbl_penarikan_header 
                    WHERE NOPEN = '$nopen' AND TGL_NOPEN = '" 
                    .Date("Y-m-d", strtotime($tglnopen)) ."'"); 
        if ($data->num_rows() > 0){
            return $data->get();
        }
        else {
            return [];
        }
    }
    public function getTransaksiSPTNP($id)
    {
        $data = $this->query("SELECT ID, NOPEN, TGL_NOPEN, BM, PPN, PPH, DENDA,
                    BMT, TOTAL_SPTNP, JENIS_KESALAHAN FROM tbl_penarikan_header 
                    WHERE ID = '$id'")->get()[0];
        $data->TGL_NOPEN = $data->TGL_NOPEN == "" ? "" : Date("d-m-Y", strtotime($data->TGL_NOPEN));
        return $data;
    }
    public function getTransaksiBC($id)
    {
        $data = $this->query("SELECT ID, NOPEN, TGL_NOPEN, LEVEL_DOK, JENIS_DOKUMEN, TGL_PERIKSA,
                    HASIL_PERIKSA, CATATAN, NO_BL, NO_INV, TGL_SPPB, TGL_KELUAR, TGL_MASUK_GUDANG, NOAJU, JALUR
                    FROM tbl_penarikan_header 
                    WHERE ID = '$id'")->get()[0];
        $data->TGL_NOPEN = $data->TGL_NOPEN == "" ? "" : Date("d-m-Y", strtotime($data->TGL_NOPEN));
        $data->TGL_PERIKSA = $data->TGL_PERIKSA == "" ? "" : Date("d-m-Y", strtotime($data->TGL_PERIKSA));
        $data->TGL_SPPB = $data->TGL_SPPB == "" ? "" : Date("d-m-Y", strtotime($data->TGL_SPPB));
        $data->TGL_KELUAR = $data->TGL_KELUAR == "" ? "" : Date("d-m-Y", strtotime($data->TGL_KELUAR));
        $data->TGL_MASUK_GUDANG = $data->TGL_MASUK_GUDANG == "" ? "" : Date("d-m-Y", strtotime($data->TGL_MASUK_GUDANG));
        return $data;
    }
    public function getData($search, $start = null, $length = null, $order = Array())
	{		
        $where = "";
        if ($search && count($search) > 0){
            $dataSearch = json_decode($search["value"]);
            if (is_array($dataSearch)){
                foreach ($dataSearch as $dt){
                    $where .= "ID = '" .$dt->id ."' OR ";  
                }
                $where = substr($where, 0, strlen($where) - 4);
            }
        }
        $strOrder = "";
        $strLimit = "";
        $columns = Array("","no_inv","tgl_tiba","jumlah_kemasan",
                         "noaju","nopen", "tgl_nopen",
                         "nama_customer", 
                         "tgl_keluar", "no_bl", "no_form", "no_po");
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
		$data = $this->query("SELECT h.id, no_bl, 
                              DATE_FORMAT(tgl_tiba,'%d-%m-%Y') AS tgl_tiba, 
                              FORMAT(jumlah_kemasan, '###,###,###') AS jumlah_kemasan, 
                              noaju, nopen, DATE_FORMAT(tgl_nopen,'%d-%m-%Y') AS tgl_nopen, 
                              nama_customer,
                              DATE_FORMAT(tgl_keluar,'%d-%m-%Y') AS tgl_keluar, no_inv,
                              no_form, no_po                        
                              FROM tbl_penarikan_header h 
                              LEFT JOIN plbbandu_app15.tb_customer c on c.id_customer = h.customer " 
							  .$where .$strOrder .$strLimit);
		return $data;
    }
    public function getTransaksi($id, $includeKontainer = true)
    {
        $header = $this->query("SELECT h.*, BMTB+BMTTB+PPNTB+PPHTB+DENDA_TB AS TOTAL_TB FROM tbl_penarikan_header h                                
                                WHERE id = '" .$id ."'")->get()[0];

        if ($includeKontainer){
            $kontainer = $this->query("SELECT * FROM tbl_penarikan_kontainer dk
                                   INNER JOIN ref_ukuran_kontainer uk ON dk.UKURAN_KONTAINER = uk.KODE
                                   WHERE id_header = '" .$id ."'")->get();
        }
        $header->TGL_INV = $header->TGL_INV == "" ? "" : Date("d-m-Y", strtotime($header->TGL_INV));
        $header->TGL_BL = $header->TGL_BL == "" ? "" : Date("d-m-Y", strtotime($header->TGL_BL));
        $header->TGL_BERANGKAT = $header->TGL_BERANGKAT == "" ? "" : Date("d-m-Y", strtotime($header->TGL_BERANGKAT));
        $header->TGL_TIBA = $header->TGL_TIBA == "" ? "" : Date("d-m-Y", strtotime($header->TGL_TIBA));
        $header->TGL_MASUK_GUDANG = $header->TGL_MASUK_GUDANG == "" ? "" : Date("d-m-Y", strtotime($header->TGL_MASUK_GUDANG));
        $header->TGL_KELUAR = $header->TGL_KELUAR == "" ? "" : Date("d-m-Y", strtotime($header->TGL_KELUAR));
        $header->TGL_PO = $header->TGL_PO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PO));
        $header->TGL_VO = $header->TGL_VO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_VO));
        $header->TGL_SC = $header->TGL_SC == "" ? "" : Date("d-m-Y", strtotime($header->TGL_SC));
        $header->TGL_FORM = $header->TGL_FORM == "" ? "" : Date("d-m-Y", strtotime($header->TGL_FORM));
        $header->TGL_NOPEN = $header->TGL_NOPEN == "" ? "" : Date("d-m-Y", strtotime($header->TGL_NOPEN));
        $header->TGL_PERIKSA = $header->TGL_PERIKSA == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PERIKSA));
        $header->TGL_PERIKSA_VO = $header->TGL_PERIKSA_VO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PERIKSA_VO));
        $header->TGL_SPPB = $header->TGL_SPPB == "" ? "" : Date("d-m-Y", strtotime($header->TGL_SPPB));
        $header->TGL_SPTNP = $header->TGL_SPTNP == "" ? "" : Date("d-m-Y", strtotime($header->TGL_SPTNP));
        $header->TGL_LS = $header->TGL_LS == "" ? "" : Date("d-m-Y", strtotime($header->TGL_LS));
        $header->TGL_BRT = $header->TGL_BRT == "" ? "" : Date("d-m-Y", strtotime($header->TGL_BRT));
        $header->TGL_LUNAS = $header->TGL_LUNAS == "" ? "" : Date("d-m-Y", strtotime($header->TGL_LUNAS));
        $header->TGL_JATUH_TEMPO_SPTNP = $header->TGL_JATUH_TEMPO_SPTNP == "" ? "" : Date("d-m-Y", strtotime($header->TGL_JATUH_TEMPO_SPTNP));
        return Array("header" => $header, "kontainer" => isset($kontainer) ? $kontainer : []);
    }
    public function getTransaksiBayar($id)
    {
        $header = $this->query("SELECT h.* FROM tbl_header_bayar h
                                INNER JOIN rekening rek ON h.REKENING_Id = rek.REKENING_ID                                
                                WHERE id = '" .$id ."'")->get()[0];
        $detail = $this->query("SELECT db.*, r.MATAUANG, KURS, ROUND(KURS*db.NOMINAL) AS RUPIAH,
                                    c.nama_customer AS CUSTOMER, h.NO_INV AS NOINV,
                                    h.NOAJU, h.NOPEN, h.TGL_NOPEN, 
                                    imp.NAMA AS IMPORTIR, shipper.nama_pemasok AS SHIPPER
                                    FROM tbl_detail_bayar db
                                   INNER JOIN tbl_penarikan_header h
                                   on h.ID = db.NO_INV
                                   LEFT JOIN plbbandu_app15.tb_customer c
                                   on c.id_customer = h.CUSTOMER
                                   LEFT JOIN importir imp
                                   on imp.IMPORTIR_ID = h.IMPORTIR
                                   LEFT JOIN plbbandu_app15.tb_pemasok shipper
                                   on shipper.id_pemasok = h.SHIPPER
                                   LEFT JOIN ref_matauang r ON db.CURR = r.MATAUANG_ID
                                   WHERE db.ID_HEADER = '" .$id ."'")->get();

        $header->TGL_PENARIKAN = $header->TGL_PENARIKAN == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PENARIKAN));
        
        return Array("header" => $header, "detail" => $detail);
    }
    public function getTransaksiDo($id)
    {
        $header = $this->query("SELECT h.ID, NO_INV, NO_PO, NO_SC, NO_BL, NO_FORM,JUMLAH_KEMASAN,
                                TGL_INV, TGL_PO, TGL_SC, TGL_BL, TGL_FORM,TGL_JATUH_TEMPO,
                                KAPAL, PEL_MUAT, TGL_BERANGKAT, TGL_TIBA, TGL_DOK_TRM,
                                PEMBAYARAN, TOP, CURR, CIF, FAKTUR  
                                FROM tbl_penarikan_header h                                
                                WHERE h.ID = '" .$id ."'")->get()[0];
        $header->TGL_INV = $header->TGL_INV == "" ? "" : Date("d-m-Y", strtotime($header->TGL_INV));
        $header->TGL_DOK_TRM = $header->TGL_DOK_TRM == "" ? "" : Date("d-m-Y", strtotime($header->TGL_DOK_TRM));
        $header->TGL_PO = $header->TGL_PO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PO));
        $header->TGL_JATUH_TEMPO = $header->TGL_JATUH_TEMPO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_JATUH_TEMPO));
        $header->TGL_SC = $header->TGL_SC == "" ? "" : Date("d-m-Y", strtotime($header->TGL_SC));
        $header->TGL_BL = $header->TGL_BL == "" ? "" : Date("d-m-Y", strtotime($header->TGL_BL));
        $header->TGL_FORM = $header->TGL_FORM == "" ? "" : Date("d-m-Y", strtotime($header->TGL_FORM));
        $header->TGL_BERANGKAT = $header->TGL_BERANGKAT == "" ? "" : Date("d-m-Y", strtotime($header->TGL_BERANGKAT));
        $header->TGL_TIBA = $header->TGL_TIBA == "" ? "" : Date("d-m-Y", strtotime($header->TGL_TIBA));
        return $header;
    }
    public function getTransaksiVo($id)
    {
        $header = $this->query("SELECT h.ID, KODE_HS_VO, h.CONSIGNEE, h.NO_PI AS ID_PI, q.NO_PI,
                                TGL_LS, NO_VO, TGL_VO, TGL_PERIKSA_VO,
                                h.STATUS, CATATAN  
                                FROM tbl_penarikan_header h 
                                LEFT JOIN tbl_header_quota q on h.NO_PI = q.ID
                                WHERE h.ID = '" .$id ."'")->get()[0];
        $header->TGL_LS = $header->TGL_LS == "" ? "" : Date("d-m-Y", strtotime($header->TGL_LS));
        $header->TGL_VO = $header->TGL_VO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_VO));
        $header->TGL_PERIKSA_VO = $header->TGL_PERIKSA_VO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PERIKSA_VO));
        return $header;
    }
    public function saveTransaksi($action, $header, $kontainer, $detail){
        $checkWhere = Array();
        if (trim($header["noinv"]) != ""){
            $checkWhere[] = "NO_INV = '" .$header["noinv"] ."'";
        } 
        if (trim($header["nobl"]) != ""){
            $checkWhere[] = "NO_BL = '" .$header["nobl"] ."'";
        }
        if (count($checkWhere) > 0){
            if ($action == "insert"){
                $check = $this->query("SELECT NO_INV FROM tbl_penarikan_header WHERE (" .implode(" OR ", $checkWhere) .")");
            }
            else if ($action == "update"){
                $check = $this->query("SELECT NO_INV FROM tbl_penarikan_header WHERE (" .implode(" OR ", $checkWhere) .") AND ID <> '" .$header["idtransaksi"] ."'");
            }
            if ($check->num_rows() > 0){
                throw new \Exception("Nomer Invoice / Nomor BL sudah ada");
            }
        }
        $arrHeader = Array("KANTOR_ID" => $header["kantor"],
                           "CUSTOMER" => trim($header["customer"]) == "" ? NULL : $header["customer"],
                           "SHIPPER" => trim($header["shipper"]) == "" ? NULL : $header["shipper"],
                           //"GUDANG_ID" => $header["gudang"],
                           "PEL_MUAT" => trim($header["pelmuat"]) == "" ? NULL : $header["pelmuat"],
                           "TGL_TIBA" => trim($header["tgltiba"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgltiba"])),
                           "TGL_BERANGKAT" => trim($header["tglberangkat"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglberangkat"])),
                           "KAPAL" => $header["kapal"],                           
                           "IMPORTIR" => trim($header["importir"]) == "" ? NULL : $header["importir"],
                           "CONSIGNEE" => trim($header["consignee"]) == "" ? NULL : $header["consignee"],
                           "NO_INV" => $header["noinv"],
                           "TGL_INV" => trim($header["tglinv"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglinv"])),
                           "NO_PO" => $header["nopo"],
                           "TGL_PO" => trim($header["tglpo"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglpo"])),
                           "NO_SC" => $header["nosc"],"NO_FORM" => $header["noform"],
                           "TGL_SC" => trim($header["tglsc"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglsc"])),
						   "TGL_FORM" => trim($header["tglform"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglform"])),
                           "NO_BL" => $header["nobl"],                           
                           "TGL_BL" => trim($header["tglbl"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglbl"])),
                           "JUMLAH_KEMASAN" => trim($header["jmlkemasan"]) == "" ? NULL : str_replace(",","",$header["jmlkemasan"]),
                           "JUMLAH_KONTAINER" => trim($header["jmlkontainer"]) == "" ? NULL : str_replace(",","",$header["jmlkontainer"]),
                           "GW" => trim($header["gw"]) == "" ? NULL : str_replace(",","",$header["gw"]),
                           "CBM" => trim($header["cbm"]) == "" ? NULL : str_replace(",","",$header["cbm"]),
                           "JENIS_BARANG" => trim($header["jenisbarang"]) == "" ? NULL : $header["jenisbarang"],
                           "JENIS_KEMASAN" => trim($header["jeniskemasan"]) == "" ? NULL : $header["jeniskemasan"],
                           "KODE_HS" => $header["kodehs"],
                           "NO_VO" => $header["novo"],
                           "TGL_VO" => trim($header["tglvo"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglvo"])),
                           "KODE_HS_VO" => $header["kodehsvo"],
                           "TGL_PERIKSA_VO" => trim($header["tglperiksavo"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglperiksavo"])),
                           "TGL_LS" => trim($header["tglls"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglls"])),
                           "STATUS" => $header["status"],
                           "JENIS_DOKUMEN" => trim($header["jenisdokumen"]) == "" ? NULL : $header["jenisdokumen"],
                           "NOPEN" => $header["nopen"],"NOAJU" => $header["noaju"],
                           "TGL_NOPEN" => trim($header["tglnopen"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglnopen"])),
                           "TGL_PERIKSA" => trim($header["tglperiksa"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglperiksa"])),
                           "CATATAN" => $header["catatan"],
                           "HASIL_PERIKSA" => $header["hasilperiksa"],
                           "TGL_MASUK_GUDANG" => trim($header["tglmasuk"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglmasuk"])),
                           "TGL_SPPB" => trim($header["tglsppb"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglsppb"])),
                           "TGL_KELUAR" => trim($header["tglkeluar"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkeluar"])),
                           "NO_SPTNP" => $header["nostpnp"],
                           "TGL_SPTNP" => trim($header["tglstpnp"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglstpnp"])),
                           /*
                           "TOTAL_SPTNP" => str_replace(",","",$header["total"]),
                           "JENIS_KESALAHAN" => $header["jeniskesalahan"],
                           */
                           "LEVEL_DOK" => isset($header["leveldok"]) ? $header["leveldok"] : NULL, "NO_PI" => trim($header["idpi"]) == "" ? NULL : $header["idpi"],
                           "TGL_BRT" => trim($header["tglbrt"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglbrt"])),
                           "TGL_JATUH_TEMPO_SPTNP" => trim($header["tgljthtemposptnp"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgljthtemposptnp"])),
                           "BMTB" => trim($header["bmtb"]) == "" ? 0 : str_replace(",","",$header["bmtb"]),
                           "BMTTB" => trim($header["bmttb"]) == "" ? 0 : str_replace(",","",$header["bmttb"]),
                           "PPNTB" => trim($header["ppntb"]) == "" ? 0 : str_replace(",","",$header["ppntb"]),
                           "PPHTB" => trim($header["pphtb"]) == "" ? 0 : str_replace(",","",$header["pphtb"]),
                           "DENDA_TB" => trim($header["dendatb"]) == "" ? 0 : str_replace(",","",$header["dendatb"]),
                           "JENIS_SPTNP" => trim($header["jenissptnp"]) == "" ? NULL : $header["jenissptnp"],
                           "HSL_BRT" => $header["hslbrt"],
                           "TGL_LUNAS" => trim($header["tgllunas"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgllunas"])),
                          );
        $this->setTableName("tbl_penarikan_header");
        if ($action == "insert"){            
            $this->save($arrHeader);
            $idtransaksi = $this->getAutoInc();
            $arrKontainer = Array();
            if (is_array($kontainer) && count($kontainer) > 0){
                foreach ($kontainer as $item){
                    $arrKontainer[] = Array("ID_HEADER" => $idtransaksi,
                                            "NOMOR_KONTAINER" => $item["NOMOR_KONTAINER"],
                                            "UKURAN_KONTAINER" => $item["UKURAN_KONTAINER"]);
                }
            }
            if (count($arrKontainer) > 0){
                $this->setTableName("tbl_penarikan_kontainer");
                $this->insert($arrKontainer);
            }
            $arrDetail = Array();
            if (is_array($detail) && count($detail) > 0){
                foreach ($detail as $item){
                    $arrDetail[] = Array("ID_HEADER" => $idtransaksi,
                                            "KODE_HS" => $item["KODE_HS"],
                                            "BOOKING" => str_replace(",","", $item["BOOKING"]),
                                            "SATUAN_ID" => $item["SATUAN_ID"]
                                );
                }
            }
            if (count($arrDetail) > 0){
                $this->setTableName("tbl_realisasi_quota");
                $this->insert($arrDetail);
            }

        }
        else if ($action == "update"){
            $idtransaksi = $header["idtransaksi"];
            $this->updateBy("ID", $idtransaksi, $arrHeader); 
            $arrKontainer = Array();
            $this->setTableName("tbl_penarikan_kontainer");   
            if (is_array($kontainer) && count($kontainer) > 0){         
                foreach ($kontainer as $item){                
                    if (!isset($item["ID"]) 
                        || $item["ID"] == ""){
                        $arrKontainer[] = Array("ID_HEADER" => $idtransaksi,
                                                "NOMOR_KONTAINER" => $item["NOMOR_KONTAINER"],
                                                "UKURAN_KONTAINER" => $item["UKURAN_KONTAINER"]);
                    }
                    else {
                        $editKontainer = Array("NOMOR_KONTAINER" => $item["NOMOR_KONTAINER"],
                                            "UKURAN_KONTAINER" => $item["UKURAN_KONTAINER"]);
                        $this->updateBy("ID", $item["ID"], $editKontainer);
                    }
                }                        
            }
            if (count($arrKontainer) > 0){                
                $this->insert($arrKontainer);
            }
            if ($header["deletekontainer"] != ""){
                $iddelete = explode(";", $header["deletekontainer"]);
                foreach ($iddelete as $iddel){
                    if ($iddel != ""){
                        $this->deleteBy("ID", $iddel);
                    }
                }
            }            
            $arrDetail = Array();
            $this->setTableName("tbl_realisasi_quota");   
            if (is_array($detail) && count($detail) > 0){         
                foreach ($detail as $item){                
                    if (!isset($item["ID"]) 
                        || $item["ID"] == ""){
                        $arrDetail[] = Array("ID_HEADER" => $idtransaksi,
                                             "KODE_HS" => $item["KODE_HS"],
                                             "SATUAN_ID" => $item["SATUAN_ID"],
                                             "BOOKING" => str_replace(",","", $item["BOOKING"]));
                    }
                    else {
                        $editDetail = Array("KODE_HS" => $item["KODE_HS"],
                                             "SATUAN_ID" => $item["SATUAN_ID"],
                                             "BOOKING" => str_replace(",","", $item["BOOKING"]));
                        $this->updateBy("ID", $item["ID"], $editDetail);
                    }
                }                        
            }
            if (count($arrDetail) > 0){                
                $this->insert($arrDetail);
            }
            if ($header["deletedetail"] != ""){
                $iddelete = explode(";", $header["deletedetail"]);
                foreach ($iddelete as $iddel){
                    if ($iddel != ""){
                        $this->deleteBy("ID", $iddel);
                    }
                }
            }            

        }
    }
    public function saveTransaksiDo($header, $files){      
        $checkWhere = Array();
        if (trim($header["noinv"]) != ""){
            $checkWhere[] = "NO_INV = '" .$header["noinv"] ."'";
        } 
        if (trim($header["nobl"]) != ""){
            $checkWhere[] = "NO_BL = '" .$header["nobl"] ."'";
        }
        if (count($checkWhere) > 0){
            $check = $this->query("SELECT NO_INV FROM tbl_penarikan_header WHERE (" .implode(" OR ", $checkWhere) .") AND ID <> '" .$header["idtransaksi"] ."'");
            if ($check->num_rows() > 0){
                throw new \Exception("Nomer Inv / No BL sudah ada");
            }
        }
        $arrHeader = Array("NO_INV" => $header["noinv"],"NO_PO" => $header["nopo"],"NO_SC" => $header["nosc"],
                           "NO_BL" => $header["nobl"],"NO_FORM" => $header["noform"],
                           "TGL_INV" => trim($header["tglinv"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglinv"])),
                           "TGL_PO" => trim($header["tglpo"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglpo"])),
                           "TGL_SC" => trim($header["tglsc"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglsc"])),
                           "TGL_BL" => trim($header["tglbl"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglbl"])),
                           "TGL_FORM" => trim($header["tglform"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglform"])),
                           "PEL_MUAT" => $header["pelmuat"],"TGL_JATUH_TEMPO" => trim($header["tgljatuhtempo"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgljatuhtempo"])),
                           "TGL_TIBA" => trim($header["tgltiba"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgltiba"])),
                           "TGL_DOK_TRM" => trim($header["tgldoktrm"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgldoktrm"])),
                           "TGL_BERANGKAT" => trim($header["tglberangkat"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglberangkat"])),
                           "KAPAL" => $header["kapal"],
                           "JUMLAH_KEMASAN" => str_replace(",","",$header["jmlkemasan"]),
                           "PEMBAYARAN" => $header["pembayaran"],
                           "TOP" => $header["top"],
                           "CURR" => $header["curr"], "FAKTUR" => $header["faktur"],
                           "CIF" => str_replace(",","",$header["cif"])
                          );
        $this->setTableName("tbl_penarikan_header");        
        $idtransaksi = $header["idtransaksi"];        
        $this->updateBy("ID", $idtransaksi, $arrHeader); 
        $this->setTableName("tbl_files");        
        $oldFiles = Array();
        if (!is_array($files)){
            $fileIds = Array();
        }
        else {
            $fileIds = array_map(function($elem){
                return $elem["id"];
            }, $files);
        }
        $dtFiles = $this->query("SELECT GROUP_CONCAT(ID) AS STR FROM tbl_files WHERE ID_HEADER = '$idtransaksi'");
        if ($dtFiles->num_rows() > 0){
            $oldFiles = explode(",",$dtFiles->current()->STR);
        }
        $diff = array_diff($fileIds, $oldFiles);
        if (count($diff) > 0){
            $strFile = "('" .implode("','", $diff) ."')";
            $this->update(["ID_HEADER" => $idtransaksi, "AKTIF" => "Y"], "ID IN " .$strFile);
        }        
        $diff = array_diff($oldFiles, $fileIds);
        if (count($diff) > 0){
            $strFile = "('" .implode("','", $diff) ."')";
            $this->delete("ID IN " .$strFile);
        }
        foreach ($fileIds as $key=>$id){
            $this->updateBy("ID", $id, ["JENISFILE_ID" => $files[$key]["jenisfile"]]);
        }
    }
    public function saveTransaksiBarang($header, $detail/*, $files*/){                              
        $arrHeader = Array("TGL_KELUAR" => trim($header["tglkeluar"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkeluar"])),
                           "TGL_TERIMA" => trim($header["tglterimabrg"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglterimabrg"])),
                           "NDPBM" => str_replace(",","", $header["ndpbm"]),
                           "CIF" => str_replace(",","",$header["nilai"]),
                           "JUMLAH_KEMASAN" => str_replace(",","",$header["jumlahkemasan"]),
                           "TGL_NOPEN" => trim($header["tglnopen"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglnopen"])),
                            "PENGIRIM" => $header["pengirim"],"JENIS_DOKUMEN" => $header["jenisdokumen"],
                            "CUSTOMER" => $header["customer"], "IMPORTIR" => $header["importir"],
                            "NOPEN" => $header["nopen"], "NO_FORM" => $header["noform"],
                            "NO_INV" => $header["noinv"],"NO_BL" => $header["nobl"],
                            "JALUR" => isset($header["jalur"]) ? $header["jalur"] : "",
                            "TGL_BL" => trim($header["tglbl"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglbl"])),
                            "TGL_INV" => trim($header["tglinv"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglinv"])),
                            "TGL_FORM" => trim($header["tglform"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglform"])),
                            "TGL_SPPB" => trim($header["tglsppb"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglsppb"])),
                            "NOAJU" => trim($header["noaju"]), "CURR" => isset($header["curr"]) ? $header["curr"] : "",
                            "TGL_AJU" => trim($header["tglaju"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglaju"])),
                        );        
        if (isset($header["bm"])){
            $arrHeader["BM"] = str_replace(",","",$header["bm"]);
            $arrHeader["BMT"] = str_replace(",","",$header["bmt"]);
            $arrHeader["PPN"] = str_replace(",","",$header["ppn"]);
            $arrHeader["PPH"] = str_replace(",","",$header["pph"]);
            $arrHeader["PPH_BEBAS"] = str_replace(",","",$header["pphbebas"]);
            $arrHeader["TOTAL"] = intval($arrHeader["BM"]) +  intval($arrHeader["BMT"]) +intval($arrHeader["PPN"]) + intval($arrHeader["PPH"]);
        }
        if (isset($header["nols"])){
            $arrHeader["NO_LS"] = $header["nols"];
            $arrHeader["TGL_LS"] = trim($header["tglls"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglls"]));
        }
        if (isset($header["tglkonversi"])){
            $arrHeader["TGL_KONVERSI"] = trim($header["tglkonversi"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkonversi"])); 
        }
        $this->setTableName("tbl_penarikan_header");
        $id = $header["idtransaksi"];
        $this->updateBy("ID", $id, $arrHeader);
        /*
        $this->setTableName("tbl_files");        
        $oldFiles = Array();
        if (!is_array($files)){
            $files = Array();
        }
        $dtFiles = $this->query("SELECT GROUP_CONCAT(ID) AS STR FROM tbl_files WHERE ID_HEADER = '$id'");
        if ($dtFiles->num_rows() > 0){
            $oldFiles = explode(",",$dtFiles->current()->STR);
        }
        $diff = array_diff($files, $oldFiles);
        if (count($diff) > 0){
            $strFile = "('" .implode("','", $diff) ."')";
            $this->update(["ID_HEADER" => $id, "AKTIF" => "Y"], "ID IN " .$strFile);
        }        
        $diff = array_diff($oldFiles, $files);
        if (count($diff) > 0){
            $strFile = "('" .implode("','", $diff) ."')";
            $this->delete("ID IN " .$strFile);
        }*/
        $this->setTableName("tbl_detail_barang");
        $deletedetail = $header["deletedetail"];
        $deletedetail = trim($deletedetail,";");
        $deleted = str_replace(";", "','", $deletedetail);
        $deleted = "'" .$deleted ."'";
        
        $this->delete("ID IN (" .$deleted .")");                   

        $arrDetail = Array();
        if (is_array($detail) && count($detail) > 0){
            foreach ($detail as $item){
                $data = Array("ID_HEADER" => $id,                                            
                            "SERIBARANG" => $item["SERIBARANG"],
                            "KODEBARANG" => $item["KODEBARANG"],
                            "URAIAN" => $item["URAIAN"],
                            "JENISKEMASAN" => $item["JENISKEMASAN"] != "" ? $item["JENISKEMASAN"] : 0,
                            "NOSPTNP" => $item["NOSPTNP"],
                            "SATUAN_ID" => $item["SATUAN_ID"] != "" ? $item["SATUAN_ID"] : null,
                            "TGLSPTNP" => trim($item["TGLSPTNP"]) == "" ? NULL : Date("Y-m-d", strtotime($item["TGLSPTNP"])),
                            "JMLKEMASAN" => $item["JMLKEMASAN"] != "" ? str_replace(",","",$item["JMLKEMASAN"]) : 0,
                            "JMLSATHARGA" => $item["JMLSATHARGA"] != "" ? str_replace(",","",$item["JMLSATHARGA"]) : 0,
                            "HARGA" => $item["HARGA"] != "" ? str_replace(",","",$item["HARGA"]) : 0,
                            "CIF" => $item["CIF"] != "" ? str_replace(",","",$item["CIF"]) : 0
                            );
                $pos = strpos($item["ID"], "dt-");
                if ($pos !== false){
                    $arrDetail[] = $data;
                }
                else {
                    $this->updateBy("ID", $item["ID"], $data);
                }
            }
            if (count($arrDetail) > 0){
                $this->insert($arrDetail);
            }    
        }
    }
    public function saveTransaksiKonversi($header, $detail){                              
        $id = $header["idtransaksi"];
        $this->setTableName("tbl_konversi");
        $deletedetail = $header["deletedetail"];
        $deletedetail = trim($deletedetail,";");
        $deleted = str_replace(";", "','", $deletedetail);
        $deleted = "'" .$deleted ."'";
        
        $this->delete("ID IN (" .$deleted .")");

        $arrDetail = Array();
        if (is_array($detail) && count($detail) > 0){
            foreach ($detail as $item){
                $data = Array("ID_HEADER" => $id,                                            
                                    "PRODUK_ID" => $item["PRODUK_ID"],
                                    "TGL_TERIMA" => trim($item["TGLTERIMA"]) == "" ? NULL : Date("Y-m-d", strtotime($item["TGLTERIMA"])),
                                    "DPP" => $item["DPP"] != "" ? str_replace(",","",$item["DPP"]) : 0,
                                    "RATE" => $item["RATE"] != "" ? str_replace(",","",$item["RATE"]) : 0
                                    );
                $pos = strpos($item["ID"], "dt-"); 
                if ($pos !== false){
                    $arrDetail[] = $data;
                }
                else {
                    $this->updateBy("ID", $item["ID"], $data);
                }
            }
            if (count($arrDetail) > 0){
                $this->insert($arrDetail);
            }    
        }
    }
    public function saveTransaksiVo($header, $detail){               
        $arrHeader = Array(                
                "TGL_LS" => trim($header["tglls"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglls"])),
                "KODE_HS_VO" => $header["kodehs"], "NO_PI" => $header["idpi"],
                "CATATAN" => $header["catatan"],
                "NO_VO" => $header["novo"],
                "STATUS" => $header["status"],
                "TGL_VO" => trim($header["tglvo"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglvo"])),
                "TGL_PERIKSA_VO" => trim($header["tglperiksa"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglperiksa"])),
            );
        $this->setTableName("tbl_penarikan_header");        
        $idtransaksi = $header["idtransaksi"];
        $this->updateBy("ID", $idtransaksi, $arrHeader);           
        
        $this->setTableName("tbl_realisasi_quota");
        if (is_array($detail) && count($detail) > 0){         
            foreach ($detail as $item){                
                $editDetail = Array("KODE_HS" => $item["KODE_HS"],
                                     "SATUAN_ID" => $item["SATUAN_ID"],
                                     "BOOKING" => str_replace(",","", $item["BOOKING"]),
                                     "REALISASI" => str_replace(",","", $item["REALISASI"]));
                $this->updateBy("ID", $item["ID"], $editDetail);
            }                        
        }
        if ($header["deletedetail"] != ""){
            $iddelete = explode(";", $header["deletedetail"]);
            foreach ($iddelete as $iddel){
                if ($iddel != ""){
                    $this->deleteBy("ID", $iddel);
                }
            }
        }            
    }
    public function saveTransaksiBC($header){               
        $arrHeader = Array(                
                "TGL_NOPEN" => trim($header["tglnopen"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglnopen"])),
                "LEVEL_DOK" => $header["leveldok"],"JENIS_DOKUMEN" => $header["jenisdokumen"],
                "CATATAN" => $header["catatan"], "HASIL_PERIKSA" => $header["hasilperiksa"],
                "NOPEN" => $header["nopen"],
                "NO_INV" => $header["noinv"],"NO_BL" => $header["nobl"],
                "TGL_PERIKSA" => trim($header["tglperiksa"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglperiksa"])),
                "TGL_SPPB" => trim($header["tglsppb"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglsppb"])),
                "TGL_KELUAR" => trim($header["tglkeluar"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkeluar"])),
                "TGL_MASUK_GUDANG" => trim($header["tglmasuk"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglmasuk"])),
                "NOAJU" => trim($header["noaju"]), "JALUR" => isset($header["jalur"]) ? $header["jalur"] : ""
            );
        $this->setTableName("tbl_penarikan_header");        
        $idtransaksi = $header["idtransaksi"];
        $this->updateBy("ID", $idtransaksi, $arrHeader);                      
    }
    public function saveTransaksiSPTNP($header){               
        $arrHeader = Array(                
                "TGL_NOPEN" => trim($header["tglnopen"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglnopen"])),
                "NOPEN" => $header["nopen"],
                "JENIS_KESALAHAN" => $header["jeniskesalahan"],
                "TOTAL_SPTNP" => str_replace(",","",$header["total"]),
                "BMT" => str_replace(",","",$header["bmt"]), "PPN" => str_replace(",","",$header["ppn"]),
                "PPH" => str_replace(",","",$header["pph"]),"DENDA" => str_replace(",","",$header["denda"]),
                "BM" => str_replace(",","",$header["bbm"])
            );
        $this->setTableName("tbl_penarikan_header");        
        $idtransaksi = $header["idtransaksi"];
        $this->updateBy("ID", $idtransaksi, $arrHeader);                      
    }
    public function updateHasilBongkar($header){
            
        $check = $this->query("SELECT NO_BL FROM tbl_penarikan_header WHERE NO_BL = '" .$header["nobl"] ."' AND ID <> '" .$header["idtransaksi"] ."'");
        if ($check->num_rows() > 0){
            throw new \Exception("Nomer BL sudah ada");
        }
        $arrHeader = Array("NO_BL" => $header["nobl"],
                           "JUMLAH_KEMASAN" => str_replace(",","",$header["jmlkemasan"]),
                           "JENIS_KEMASAN" => $header["jeniskemasan"],
                           "HASIL_BONGKAR" => $header["hasilbongkar"],
                           "CATATAN" => $header["catatan"],                           
                           "JENIS_DOKUMEN1" => $header["jenisdokumen1"],
                           "NOPEN1" => $header["nopen1"],
                           "TGL_BONGKAR" => trim($header["tglbongkar"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglbongkar"])),
                           "TGL_NOPEN1" => trim($header["tglnopen1"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglnopen1"])),
                           "TGL_KELUAR" => trim($header["tglkeluar"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkeluar"])),
                           "AJU1" => $header["aju1"],
                           "STATUS_REVISI" => $header["statusrevisi"]
                          );
        if (isset($header["customer"])){
            $arrHeader["CUSTOMER"] = $header["customer"];
        }
        $this->setTableName("tbl_penarikan_header");
        $idtransaksi = $header["idtransaksi"];
        $this->updateBy("ID", $idtransaksi, $arrHeader);
    }
    public function getMonitoring($kantor, $gudang, $customer, $importir, $kategori, $dari, $sampai )
    {
        $dari = Date("Y-m-d", strtotime($dari));
        $sampai = Date("Y-m-d", strtotime($sampai));
        $data = Array();
        $whereCustomer = $customer != "" ? " AND h.CUSTOMER = '" .$customer ."'" : "";
        $whereImportir = $importir != "" ? " AND h.IMPORTIR = '" .$importir ."'" : "";
        $field = Array("Tanggal Tiba" => "TGL_TIBA",
                       "Tanggal Keluar" => "TGL_KELUAR",
                       "Tanggal Nopen Dok In" => "TGL_NOPEN1",                       
                       "Tanggal Bongkar" => "TGL_BONGKAR",
                       "Tanggal Rekam" => "TGL_REKAM"); 
        $header = $this->query("SELECT h.ID, h.NO_BL, h.JUMLAH_KEMASAN, h.JUMLAH_KONTAINER,
                                AJU1, NOPEN1, DATE_FORMAT(TGL_NOPEN1, '%d-%m-%Y') AS TGLNOPEN1,
                                c.nama_customer AS NAMACUSTOMER,
                                DATE_FORMAT(TGL_BONGKAR, '%d-%m-%Y') AS TGLBONGKAR, 
                                IF(DOK_LAIN = '', '', IF (DOK_LAIN = 'Y', 'Pakai Form', 'Tanpa Form')) AS FORM,
                                i.NAMA AS NAMACONSIGNEE, i2.NAMA AS NAMAIMPORTIR,
                                jb.URAIAN AS NAMAJENISBARANG FROM tbl_penarikan_header h    
                                LEFT JOIN ref_jenis_barang jb ON h.jenis_barang = jb.JENISBARANG_ID
                                LEFT JOIN importir i ON h.CONSIGNEE = i.importir_id
                                LEFT JOIN importir i2 ON h.IMPORTIR = i2.importir_id                            
                                LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                                WHERE KANTOR_ID = '" .$kantor ."' "
                                .(trim($gudang) != "" ? " AND GUDANG_ID = '" .$gudang ."' " : "")
                                ." AND (" .$field[$kategori] ." BETWEEN '" .$dari ."' AND '" .$sampai 
                                ."') " .$whereCustomer .$whereImportir ." ORDER BY " .$field[$kategori]);        
        foreach ($header as $head){            
            $current["header"] = $head;
            $kontainer = $this->query("SELECT * FROM tbl_penarikan_kontainer dk
                    INNER JOIN ref_ukuran_kontainer uk ON dk.UKURAN_KONTAINER = uk.KODE
                    WHERE id_header = '" .$head->ID ."'")->get();
            $current["kontainer"] = $kontainer;
            if (count($current) > 0){
                $data[] = $current;
            }
        }
        return $data;    
    } 
    public function getHarian($kantor, $gudang, $customer, $kategori1, $dari1, 
                              $sampai1, $kategori2, $dari2, $sampai2 )
    {
        $data = Array(); 
        
        $array = Array("Tanggal Perekaman" => "TGL_REKAM", "Tanggal Bongkar" => "TGL_BONGKAR", "Tanggal Tiba" => "TGL_TIBA",
                       "Tanggal Keluar" => "TGL_KELUAR", "Tanggal Nopen Dok In" => "TGL_NOPEN1",
                       "Tanggal Nopen Dok Out" => "TGL_NOPEN2");
        $where = "";
        $whereCustomer = $customer != "" ? " AND customer = '" .$customer ."'" : "";
        if ($kategori1 != ""){
            if (trim($dari1) == "" && trim($sampai1) == ""){
                $where  .=  "(" .$array[$kategori1] ." IS NULL OR " .$array[$kategori1] ." = '')";
            }
            else {
                if (trim($dari1) == ""){
                    $dari1 = "0000-00-00";
                }
                if (trim($sampai1) == ""){
                    $sampai1 = "9999-99-99";
                }
                $where  .=  "(" .$array[$kategori1] ." BETWEEN '" .Date("Y-m-d", strtotime($dari1)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai1)) ."')";
            }

        }
        if ($kategori2 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  "(" .$array[$kategori2] ." IS NULL OR " .$array[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  "(" .$array[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        
        $header = $this->query("SELECT h.ID, h.NO_BL, i.NAMA AS NAMACONSIGNEE,
                                c.nama_customer AS NAMACUSTOMER,
                                DATE_FORMAT(TGL_REKAM, '%d-%m-%Y') AS TGLREKAM,
                                DATE_FORMAT(TGL_BERANGKAT, '%d-%m-%Y') AS TGLBERANGKAT,
                                DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA, h.JUMLAH_KONTAINER,
                                h.KAPAL, h.JUMLAH_KEMASAN, h.NO_PO, h.NO_FORM, h.GW, h.CBM, h.NO_INPL,
                                jk.URAIAN AS JENISKEMASAN,
                                pl.URAIAN AS NAMAPELMUAT, p.nama_pemasok AS NAMASHIPPER,
                                jb.URAIAN AS NAMAJENISBARANG FROM tbl_penarikan_header h    
                                LEFT JOIN ref_jenis_barang jb ON h.jenis_barang = jb.JENISBARANG_ID
                                LEFT JOIN ref_jenis_kemasan jk ON h.jenis_kemasan = jk.JENISKEMASAN_ID                                                                
                                LEFT JOIN plbbandu_app15.tb_pemasok p ON h.SHIPPER = p.id_pemasok
                                LEFT JOIN importir i ON h.CONSIGNEE = i.importir_id
                                LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                                LEFT JOIN ref_pelmuat pl ON h.PEL_MUAT = pl.PELMUAT_ID                            
                                WHERE KANTOR_ID = '" .$kantor ."' "
                                .(trim($gudang) != "" ? " AND GUDANG_ID = '" .$gudang ."' " : "")
                                .(trim($where) != "" ? " AND " .$where : "") .$whereCustomer
                                ."  ORDER BY TGL_REKAM");        
        foreach ($header as $head){            
            $current["header"] = $head;
            $kontainer = $this->query("SELECT * FROM tbl_penarikan_kontainer dk
                    INNER JOIN ref_ukuran_kontainer uk ON dk.UKURAN_KONTAINER = uk.KODE
                    WHERE id_header = '" .$head->ID ."'")->get();
            $current["kontainer"] = $kontainer;
            if (count($current) > 0){
                $data[] = $current;
            }
        }
        
        return $data;    
    }
    public function deleteTransaksi($id)
    {
        $this->setTableName("tbl_penarikan_header");
        $this->deleteBy("ID", $id);
        $this->setTableName("tbl_penarikan_kontainer");
        $this->deleteBy("ID_HEADER", $id);
    }
    public function browse($kantor, $customer, $importir, $kategori1, $dari1, 
                           $sampai1, $kategori2, $dari2, $sampai2)
    {                
        $array = Array("Tanggal Tiba" => "TGL_TIBA",
                       "Tanggal Keluar" => "TGL_KELUAR", "Tanggal Nopen" => "TGL_NOPEN");
        $where = "";
        if ($kategori1 != ""){
            if (trim($dari1) == "" && trim($sampai1) == ""){
                $where  .=  "(" .$array[$kategori1] ." IS NULL OR " .$array[$kategori1] ." = '')";
            }
            else {
                if (trim($dari1) == ""){
                    $dari1 = "0000-00-00";
                }
                if (trim($sampai1) == ""){
                    $sampai1 = "9999-99-99";
                }
                $where  .=  "(" .$array[$kategori1] ." BETWEEN '" .Date("Y-m-d", strtotime($dari1)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai1)) ."')";
            }

        }
        if ($kategori2 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  "(" .$array[$kategori2] ." IS NULL OR " .$array[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  "(" .$array[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        if (trim($customer) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."CUSTOMER = '" .$customer ."'";
        }   
        if (trim($importir) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."IMPORTIR = '" .$importir ."'";
        }
        if (trim($kantor) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."k.KANTOR_ID = '" .$kantor ."'";
        }
        $where = trim($where) != "" ? "WHERE $where" : "";
        $data = $this->query("SELECT k.KODE AS KODEKANTOR, NO_INV, NO_BL, JUMLAH_KEMASAN, nama_customer AS NAMA, 
                              i.nama AS IMPORTIR, NOAJU, 
                              DATE_FORMAT(TGL_SPPB, '%d-%m-%Y') AS TGLSPPB,
                              DATE_FORMAT(TGL_TERIMA, '%d-%m-%Y') AS TGLTERIMA,
                              DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,                               
                              DATE_FORMAT(TGL_KELUAR, '%d-%m-%Y') AS TGLKELUAR,  
                              NOPEN, DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y')
                              AS TGLNOPEN, JUMLAH_KONTAINER
                              FROM tbl_penarikan_header h 
                              INNER JOIN ref_kantor k ON h.KANTOR_ID = k.KANTOR_ID
                              LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                              LEFT JOIN importir i ON h.IMPORTIR = i.importir_id
                              " .$where);
        return $data->get();        
    }
    public function browsedo($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2, $kategori3, $dari3, $sampai3)
    {   
        $array1 =  Array("No Inv" => "NO_INV","No BL" => "NO_BL","No Vo" => "NO_VO", "Nopen" => "NOPEN");

        $array2 = Array("Tanggal BL" => "TGL_BL", "Tanggal Tiba" => "TGL_TIBA", "Tanggal Nopen" => "TGL_NOPEN", "Tgl Dok Terima" => "TGL_DOK_TRM");
        $where = "";
        if ($kategori1 != ""){
            if (trim($isikategori1) == ""){
                $where  .=  "(" .$array1[$kategori1] ." IS NULL OR " .$array1[$kategori1] ." = '')";
            }
            else {
                $where  .=  "(" .$array1[$kategori1] ." LIKE '%" .$isikategori1 ."%')";
            }

        }
        if ($kategori2 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  "(" .$array2[$kategori2] ." IS NULL OR " .$array2[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        if ($kategori3 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari3) == "" && trim($sampai3) == ""){
                $where  .=  "(" .$array2[$kategori3] ." IS NULL OR " .$array2[$kategori3] ." = '')";
            }
            else {
                if (trim($dari3) == ""){
                    $dari3 = "0000-00-00";
                }
                if (trim($sampai3) == ""){
                    $sampai3 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori3] ." BETWEEN '" .Date("Y-m-d", strtotime($dari3)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai3)) ."')";
            }            
        }
        if (trim($customer) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."CUSTOMER = '" .$customer ."'";
        }           
        if (trim($importir) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."IMPORTIR = '" .$importir ."'";
        }           
        if (trim($kantor) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."KANTOR_ID = '" .$kantor ."'";
        }
        $where = trim($where) != "" ? " WHERE $where " : "";
        $data = $this->query("SELECT ID, NO_INV, NO_PO, NO_SC, NO_BL, NO_FORM, NOAJU, NOPEN,
                              i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER,                              
                              DATE_FORMAT(TGL_BL, '%d-%m-%Y') AS TGLBL,
                              DATE_FORMAT(TGL_LS, '%d-%m-%Y') AS TGLLS,
                              DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,
                              DATE_FORMAT(TGL_DOK_TRM, '%d-%m-%Y') AS TGLDOKTRM,
                              DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN
                              FROM tbl_penarikan_header h 
                              LEFT JOIN importir i ON h.IMPORTIR = i.importir_id
                              LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                              " .$where);
        //echo $data->printquery();die();
        return $data->get();        
    }
    public function hasilbongkar($kantor, $gudang, $customer, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {   
        $array1 =  Array("Aju Dok In" => "AJU1","Nopen Dok In" => "NOPEN1", "Hasil Bongkar" => "HASIL_BONGKAR");

        $array2 = Array("Tanggal Bongkar" => "TGL_BONGKAR", "Tanggal Tiba" => "TGL_TIBA",
                       "Tanggal Keluar" => "TGL_KELUAR", "Tanggal Nopen Dok In" => "TGL_NOPEN1",
                       "Tanggal Nopen Dok Out" => "TGL_NOPEN2");
        $where = "";
        if ($kategori1 != ""){
            if (trim($isikategori1) == ""){
                $where  .=  "(" .$array1[$kategori1] ." IS NULL OR " .$array1[$kategori1] ." = '')";
            }
            else {
                $where  .=  "(" .$array1[$kategori1] ." LIKE '%" .$isikategori1 ."%')";
            }

        }
        if ($kategori2 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  "(" .$array2[$kategori2] ." IS NULL OR " .$array2[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        if (trim($gudang) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."GUDANG_ID = '" .$gudang ."'";
        }
        if (trim($customer) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."CUSTOMER = '" .$customer ."'";
        }           
        $where .= (trim($where) != "" ? " AND " : "") ."KANTOR_ID = '" .$kantor ."'";
        $data = $this->query("SELECT id, NO_BL, JUMLAH_KEMASAN, nama_customer AS NAMA, 
                              i.nama AS IMPORTIR, IF(HASIL_BONGKAR = '' OR HASIL_BONGKAR IS NULL, '', 
                              IF(HASIL_BONGKAR = 'Y', 'Sesuai','Tidak Sesuai')) AS HASILBONGKAR,
                              DATE_FORMAT(TGL_BONGKAR, '%d-%m-%Y') AS TGLBONGKAR,
                              DATE_FORMAT(TGL_KELUAR, '%d-%m-%Y') AS TGLKELUAR,
                              AJU1, NOPEN1,  DATE_FORMAT(TGL_NOPEN1, '%d-%m-%Y') AS TGLNOPEN1, r.STATUS_REVISI
                              FROM tbl_penarikan_header h 
                              LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                              LEFT JOIN importir i ON h.IMPORTIR = i.importir_id
                              LEFT JOIN ref_status_revisi r ON h.STATUS_REVISI = r.STATUSREVISI_ID
                              WHERE " .$where);
        //echo $data->printquery()
        return $data->get();        
    }
    public function browsevo($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2, $kategori3, $dari3, $sampai3)
    {   
        $array1 =  Array("No Inv" => "NO_INV","No VO" => "NO_VO", "Status VO" => "STATUS");

        $array2 = Array("Tanggal Periksa" => "TGL_PERIKSA_VO", "Tanggal Tiba" => "TGL_TIBA",
                       "Tanggal LS" => "TGL_LS","Tanggal VO" => "TGL_VO","Tanggal Nopen" => "TGL_NOPEN");
        $where = "";
        if ($kategori1 != ""){
            if (trim($isikategori1) == ""){
                $where  .=  "(" .$array1[$kategori1] ." IS NULL OR " .$array1[$kategori1] ." = '')";
            }
            else {
                $where  .=  "(" .$array1[$kategori1] ." LIKE '%" .$isikategori1 ."%')";
            }

        }
        if ($kategori2 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  "(" .$array2[$kategori2] ." IS NULL OR " .$array2[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        if ($kategori3 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari3) == "" && trim($sampai3) == ""){
                $where  .=  "(" .$array2[$kategori3] ." IS NULL OR " .$array2[$kategori3] ." = '')";
            }
            else {
                if (trim($dari3) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai3) == ""){
                    $sampai3 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori3] ." BETWEEN '" .Date("Y-m-d", strtotime($dari3)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai3)) ."')";
            }            
        }
        if (trim($customer) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."CUSTOMER = '" .$customer ."'";
        }           
        if (trim($importir) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."IMPORTIR = '" .$importir ."'";
        }           
        if (trim($kantor) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."KANTOR_ID = '" .$kantor ."'";
        }
        $where = trim($where) != "" ? " WHERE $where " : "";
        $data = $this->query("SELECT ID, NO_INV, i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER,
                              IF(STATUS = '' OR STATUS IS NULL, '',
                              CASE  WHEN STATUS = 'K' THEN 'Konfirmasi'
                                    WHEN STATUS = 'B' THEN 'Belum Inspect'
                                    WHEN STATUS = 'S' THEN 'Sudah Inspect'
                                    WHEN STATUS = 'R' THEN 'Revisi FD'
                                    WHEN STATUS = 'F' THEN 'FD'
                                    WHEN STATUS = 'L' THEN 'LS Terbit'
                                    END) AS STATUSVO, NOPEN,
                              DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,
                              DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,
                              DATE_FORMAT(TGL_PERIKSA_VO, '%d-%m-%Y') AS TGLPERIKSAVO,
                              DATE_FORMAT(TGL_VO, '%d-%m-%Y') AS TGLVO,
                              DATE_FORMAT(TGL_LS, '%d-%m-%Y') AS TGLLS, KODE_HS_VO, NO_VO
                              FROM tbl_penarikan_header h 
                              LEFT JOIN importir i ON h.IMPORTIR = i.importir_id
                              LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                              " .$where);
        //echo $data->printquery()
        return $data->get();        
    }
    public function browsebc($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {   
        $array1 =  Array("Nopen" => "NOPEN","No BL" => "NO_BL", "No Kontainer" => "NOMOR_KONTAINER","Hasil Periksa" => "HASIL_PERIKSA");

        $array2 = Array("Tanggal Periksa" => "TGL_PERIKSA","Tanggal Keluar" => "TGL_KELUAR",
                       "Tanggal Nopen" => "TGL_NOPEN","Tanggal SPPB" => "TGL_SPPB", "Tanggal Tiba" => "TGL_TIBA");
        $where = "";
        if ($kategori1 != ""){
            if (trim($isikategori1) == ""){
                $where  .=  "(" .$array1[$kategori1] ." IS NULL OR " .$array1[$kategori1] ." = '')";
            }
            else {
                $where  .=  "(" .$array1[$kategori1] ." LIKE '%" .$isikategori1 ."%')";
            }

        }
        if ($kategori2 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  "(" .$array2[$kategori2] ." IS NULL OR " .$array2[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        if (trim($customer) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."id_customer = '" .$customer ."'";
        }           
        if (trim($importir) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."importir_id = '" .$importir ."'";
        }           

        $where .= (trim($where) != "" ? " AND " : "") ."KANTOR_ID = '" .$kantor ."'";
        $data = $this->query("SELECT ID, KANTOR_ID, i.importir_id, c.id_customer,
                              NOPEN, i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER,
                              DATE_FORMAT(TGL_PERIKSA, '%d-%m-%Y') AS TGLPERIKSA,
                              DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,
                              DATE_FORMAT(TGL_DOK_TRM, '%d-%m-%Y') AS TGLDOKTRM,
                              DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,
                              DATE_FORMAT(TGL_SPPB, '%d-%m-%Y') AS TGLSPPB, 
                              DATE_FORMAT(TGL_KELUAR, '%d-%m-%Y') AS TGLKELUAR,
                              NO_BL, NO_INV, JUMLAH_KEMASAN,
                              IF(JALUR = '' OR JALUR IS NULL, '',
                              CASE  WHEN JALUR = 'K' THEN 'Kuning'
                                    WHEN JALUR = 'M' THEN 'Merah'
                                    WHEN JALUR = 'H' THEN 'Hijau'
                                    END) AS JALURDOK,
                              IF(LEVEL_DOK = '' OR LEVEL_DOK IS NULL, '',
                              CASE  WHEN LEVEL_DOK = 'K' THEN 'Kuning'
                                    WHEN LEVEL_DOK = 'M' THEN 'Merah'
                                    WHEN LEVEL_DOK = 'H' THEN 'Hijau'
                                    END) AS LEVEL_DOK,
                              HASIL_PERIKSA,  (SELECT GROUP_CONCAT(NOMOR_KONTAINER)
                              FROM tbl_penarikan_kontainer d where d.ID_HEADER = h.ID)
                              AS NOMOR_KONTAINER 
                              FROM tbl_penarikan_header h 
                              LEFT JOIN importir i ON h.IMPORTIR = i.importir_id
                              LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                              WHERE " .$where);

        return $data->get();        
    }
    public function browsebayar($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {   
        $array1 =  Array("No Inv" => "h.NO_INV","TOP" => "TOP_ID", "TT/Non TT" => "PEMBAYARAN");
        $array2 = Array("Tanggal Jatuh Tempo" => "TGL_JATUH_TEMPO");
        $where = "";
        if ($kategori1 != ""){
            if (trim($isikategori1) == ""){
                $where  .=  "(" .$array1[$kategori1] ." IS NULL OR " .$array1[$kategori1] ." = '')";
            }
            else {
                $where  .=  "(" .$array1[$kategori1] ." LIKE '%" .$isikategori1 ."%')";
            }

        }
        if ($kategori2 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  "(" .$array2[$kategori2] ." IS NULL OR " .$array2[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        if (trim($customer) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."CUSTOMER = '" .$customer ."'";
        }           
        if (trim($importir) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."IMPORTIR = '" .$importir ."'";
        }           
        if (trim($kantor) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."KANTOR_ID = '" .$kantor ."'";
        }
        $where = trim($where) != "" ? " WHERE $where " : "";
        $data = $this->query("SELECT h.ID, h.NO_INV, i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER,
                              IF(PEMBAYARAN = '' OR PEMBAYARAN IS NULL, '', 
                              CASE  WHEN PEMBAYARAN = 'Y' THEN 'TT'
                                    WHEN PEMBAYARAN = 'T' THEN 'Non TT'
                                    END) AS PEMBAYARAN, u.MATAUANG,
                              t.TOP AS TERM, 
                              DATE_FORMAT(TGL_JATUH_TEMPO, '%d-%m-%Y') AS TGLJTHTEMPO, 
                              IF(FAKTUR = '' OR FAKTUR IS NULL, '',
                              CASE  WHEN FAKTUR = 'Y' THEN 'Ya'
                                    WHEN FAKTUR = 'T' THEN 'Tidak'
                                    WHEN FAKTUR = 'P' THEN 'Sebagian'
                                    END) AS FAKTUR,
                              h.CIF, (SELECT IFNULL(SUM(NOMINAL),0) 
                              FROM tbl_detail_bayar WHERE NO_INV = h.ID)
                              AS BAYAR
                              FROM tbl_penarikan_header h 
                              LEFT JOIN importir i ON h.IMPORTIR = i.importir_id
                              LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                              LEFT JOIN ref_top t ON h.TOP = t.TOP_ID                              
                              LEFT JOIN ref_matauang u ON h.CURR = u.MATAUANG_ID 
                              " .$where);
        
        return $data->get();        
    }
    public function saveTransaksiBayar($action, $header, $detail)
    {       
        $arrHeader = Array("TGL_PENARIKAN" => trim($header["tglpenarikan"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tglpenarikan"])),
                           "NOMINAL" => str_replace(",","",$header["totpayment"]),
                           "REKENING_ID" => $header["rekening"],
                           "NO_CEK" => $header["nocek"],
                          );
        $this->setTableName("tbl_header_bayar");
        if ($action == "insert"){            
            $this->save($arrHeader);
            $idtransaksi = $this->getAutoInc();
            $this->setTableName("tbl_detail_bayar");
        }
        else if ($action == "update"){
            $idtransaksi = $header["idtransaksi"];
            $this->updateBy("ID", $idtransaksi, $arrHeader); 
            $this->setTableName("tbl_detail_bayar");
            $this->deleteBy("ID_HEADER", $idtransaksi);
        }
        $arrDetail = Array();
        if (is_array($detail) && count($detail) > 0){
            foreach ($detail as $item){
                $arrDetail[] = Array("ID_HEADER" => $idtransaksi,                                            
                                    "NO_PPU" => $item["NO_PPU"],
                                    "NO_INV" => $item["NO_INV"],
                                    "CURR" => $item["CURR"] == "" ? NULL : $item["CURR"],
                                    "KURS" => $item["KURS"] != "" ? str_replace(",","",$item["KURS"]) : 0,
                                    "NOMINAL" => $item["NOMINAL"] != "" ? str_replace(",","",$item["NOMINAL"]) : 0
                                    );
            }
            $this->insert($arrDetail);
        }
    }
    public function calculateBayar($id)
    {
        $dtTotal = $this->query("SELECT IFNULL(SUM(ROUND(KURS*NOMINAL)),0) AS TOTAL FROM tbl_detail_bayar WHERE ID_HEADER = '" .$id ."'");
        $total = 0;
        if ($dtTotal->num_rows() > 0){
            $total = $dtTotal->current()->TOTAL;
        }
        $this->execute("UPDATE tbl_header_bayar SET NOMINAL = $total WHERE ID = '$id'");
    }
    public function getFiles($id, $type = 0)
    {
        $dtFiles = $this->query("SELECT tbl_files.*, jenisfile.JENIS FROM tbl_files
                                 LEFT JOIN jenisfile on tbl_files.JENISFILE_ID = jenisfile.ID 
                                 WHERE ID_HEADER = '$id' and AKTIF = 'Y' AND TYPE = " .$type);
        return $dtFiles->get();
    }
    public function getMaxFileId($prefix)
    {
        $data = $this->query("SELECT MAX(ID) as MAX FROM tbl_files WHERE ID LIKE '$prefix%'");
        if ($data->num_rows() > 0){
            return $data->get()[0]->MAX;
        }
        else {
            return false;
        }
    }
    public function saveFile($realname, $extension, $type = 0)
    {
        $timestamp = Date("YmdHis");
        $id = $this->getMaxFileId($timestamp);
        if ($id != false){
            $max = str_pad(intval(substr($id,14)) + 1,3,"0",STR_PAD_LEFT);
        }
        else {
            $max = '001';
        }			
        $id = $timestamp .$max;
        $array = ["ID" => $id, "FILENAME" => $id ."." .$extension, "FILEREALNAME" => $realname, "TYPE" => $type];
        $this->setTableName("tbl_files");
        $this->save($array);
        return $id;
    }
    public function deleteFile($id)
    {
        $this->setTableName("tbl_files");
        $dtFile = $this->query("SELECT FILENAME FROM tbl_files WHERE ID =  '$id'");
        if ($dtFile->num_rows() > 0){
            $filename = ROOT_DIR ."/uploads/" .$dtFile->current()->FILENAME;
            unlink($filename);
            $this->deleteBy("ID", $id);
        }        
    }
    public function kartuhutang($kantor, $customer, $importir, $shipper, $kategori2, $dari2, $sampai2)
    {   
        //$array1 =  Array("No Inv" => "h.NO_INV","TOP" => "TOP_ID", "TT/Non TT" => "PEMBAYARAN");
        $array2 = Array("Tgl Jatuh Tempo" => "TGL_JATUH_TEMPO","Tgl Inv" => "TGL_INV", "Tgl Nopen" => "TGL_NOPEN");
        $where = "";
        /*
        if ($kategori1 != ""){
            if (trim($isikategori1) == ""){
                $where  .=  "(" .$array1[$kategori1] ." IS NULL OR " .$array1[$kategori1] ." = '')";
            }
            else {
                $where  .=  "(" .$array1[$kategori1] ." = '" .$isikategori1 ."')";
            }

        }
        */
        if ($kategori2 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  "(" .$array2[$kategori2] ." IS NULL OR " .$array2[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        if (trim($customer) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."CUSTOMER = '" .$customer ."'";
        }           
        if (trim($importir) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."IMPORTIR = '" .$importir ."'";
        }
        if (trim($kantor) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."k.KANTOR_ID = '" .$kantor ."'";
        }
        if (trim($shipper) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."SHIPPER = '" .$shipper ."'";
        }           
        if ($where != ""){
            $where = "WHERE " .$where;
        }
        $data = $this->query("SELECT h.ID, h.NO_INV, h.NOPEN, h.CIF, jd.KODE as JENISDOKUMEN, i.nama AS IMPORTIR, 
                              c.nama_customer AS CUSTOMER, k.URAIAN AS KANTOR,
                              ship.nama_pemasok AS SHIPPER,
                              DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,
                              DATE_FORMAT(TGL_INV, '%d-%m-%Y') AS TGLINV,
                              DATE_FORMAT(TGL_JATUH_TEMPO, '%d-%m-%Y') AS TGLJTHTEMPO, 
                              IFNULL(db.TOT_PAYMENT,0) AS TOT_PAYMENT, h.CIF - IFNULL(db.TOT_PAYMENT,0) AS SALDO, u.MATAUANG
                              FROM tbl_penarikan_header h 
                              INNER JOIN ref_kantor k ON k.KANTOR_ID = h.KANTOR_ID
                              LEFT JOIN importir i ON h.IMPORTIR = i.importir_id
                              LEFT JOIN (SELECT NO_INV, IFNULL(SUM(NOMINAL),0) AS TOT_PAYMENT 
                              FROM tbl_detail_bayar GROUP BY NO_INV) db
                              ON h.ID = db.NO_INV
                              LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                              LEFT JOIN plbbandu_app15.tb_pemasok ship ON h.SHIPPER = ship.id_pemasok
                              LEFT JOIN ref_jenis_dokumen jd ON h.JENIS_DOKUMEN = jd.JENISDOKUMEN_ID
                              LEFT JOIN ref_matauang u ON h.CURR = u.MATAUANG_ID 
                              " .$where);
        return $data->get();        
    }
    public function getDetailBayar($id)
    {
        $data = $this->query("SELECT  ID_HEADER, DATE_FORMAT(TGL_PENARIKAN, '%d-%m-%Y') AS TGLBAYAR, 
                              NO_PPU, u.MATAUANG,
                              KURS, db.NOMINAL, KURS*db.NOMINAL AS RUPIAH FROM tbl_detail_bayar db
                              INNER JOIN tbl_header_bayar h on db.ID_HEADER = h.ID
                              LEFT JOIN ref_matauang u ON db.CURR = u.MATAUANG_ID
                              WHERE NO_INV = '$id'");        
        return $data->get();
    }
    public function browsebarang($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2, $detail = false)
    {   
        $array1 =  Array("No Inv" => "NO_INV", "Nopen" => "NOPEN","No BL" => "NO_BL", "No Kontainer" => "NOMOR_KONTAINER","Hasil Periksa" => "HASIL_PERIKSA");

        $array2 = Array("Tanggal Keluar" => "TGL_KELUAR",
                       "Tanggal Nopen" => "TGL_NOPEN","Tanggal SPPB" => "TGL_SPPB", "Tanggal Tiba" => "TGL_TIBA");
        $where = "";
        if ($kategori1 != ""){
            if (trim($isikategori1) == ""){
                $where  .=  "(" .$array1[$kategori1] ." IS NULL OR " .$array1[$kategori1] ." = '')";
            }
            else {
                $where  .=  "(" .$array1[$kategori1] ." LIKE '%" .$isikategori1 ."%')";
            }

        }
        if ($kategori2 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  "(" .$array2[$kategori2] ." IS NULL OR " .$array2[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        if (trim($customer) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."id_customer = '" .$customer ."'";
        }           
        if (trim($importir) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."importir_id = '" .$importir ."'";
        }           
        if (trim($kantor) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."KANTOR_ID = '" .$kantor ."'";
        }
        $where = trim($where) != "" ? " WHERE $where " : "";
        $headerFields = ",  DATE_FORMAT(h.TGL_INV, '%d-%m-%Y') AS TGLINV, PENGIRIM, NO_LS, DATE_FORMAT(TGL_LS, '%d-%m-%Y') AS TGLLS, 
                            BM, BMT, PPN, PPH, TOTAL, PPH_BEBAS, r.MATAUANG, jd.kode AS JENISDOKUMEN, h.CIF AS NILAI,
                            DATE_FORMAT(TGL_BL, '%d-%m-%Y') AS TGLBL, NO_FORM, DATE_FORMAT(TGL_FORM, '%d-%m-%Y') AS TGLFORM, 
                            JENIS_DOKUMEN, DATE_FORMAT(h.TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN, 
                            DATE_FORMAT(TGL_AJU, '%d-%m-%Y') AS TGLAJU, DATE_FORMAT(TGL_TERIMA, '%d-%m-%Y') AS TGLTERIMA, 
                            NDPBM, t.KODEBARANG, t.JMLKEMASAN, t.JMLSATHARGA, t.HARGA, t.CIF, t.URAIAN,
                            t.SATUAN_ID, t.JENISKEMASAN, t.NOSPTNP, DATE_FORMAT(TGLSPTNP, '%d-%m-%Y') AS TGLSPTNP, sk.satuan as SATUANKEMASAN, st.satuan";
        $headerJoin = " LEFT JOIN ref_matauang r ON h.CURR = r.MATAUANG_ID
                        LEFT JOIN tbl_detail_barang t ON h.ID = t.ID_HEADER 
                        LEFT JOIN ref_jenis_dokumen jd ON h.JENIS_DOKUMEN = jd.JENISDOKUMEN_ID 
                        LEFT JOIN satuan sk ON t.JENISKEMASAN = sk.id
                        LEFT JOIN satuan st ON t.SATUAN_ID = st.id ";
                        
        $data = $this->query("SELECT h.ID, KANTOR_ID, i.importir_id, c.id_customer,
                              NOPEN, i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER, NOAJU,
                              DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,
                              DATE_FORMAT(TGL_DOK_TRM, '%d-%m-%Y') AS TGLDOKTRM,
                              DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,
                              DATE_FORMAT(TGL_SPPB, '%d-%m-%Y') AS TGLSPPB, 
                              DATE_FORMAT(TGL_KELUAR, '%d-%m-%Y') AS TGLKELUAR,
                              NO_BL, NO_INV, JUMLAH_KEMASAN,
                              IF(JALUR = '' OR JALUR IS NULL, '',
                              CASE  WHEN JALUR = 'K' THEN 'Kuning'
                                    WHEN JALUR = 'M' THEN 'Merah'
                                    WHEN JALUR = 'H' THEN 'Hijau'
                                    END) AS JALURDOK,
                            (SELECT GROUP_CONCAT(NOMOR_KONTAINER)
                              FROM tbl_penarikan_kontainer d where d.ID_HEADER = h.ID)
                              AS NOMOR_KONTAINER "
                            .($detail ? $headerFields : "")
                            ." FROM tbl_penarikan_header h "
                            ." LEFT JOIN importir i ON h.IMPORTIR = i.importir_id
                              LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer "
                            .($detail ? $headerJoin : "")

                             .$where);
        //print($data->printquery());die();
        return $data->get();        
    }
    public function getTransaksiBarang($id)
    {
        $header = $this->query("SELECT h.ID, h.CUSTOMER, c.nama_customer as NAMACUSTOMER,
                                h.IMPORTIR, h.NO_INV, h.TGL_INV, PENGIRIM, NO_LS, TGL_LS,
                                BM, BMT, PPN, PPH, TOTAL, PPH_BEBAS,
                                NO_BL, TGL_BL, NO_FORM, TGL_FORM, CIF, TGL_KONVERSI,
                                TGL_SPPB, TGL_KELUAR,JENIS_DOKUMEN,JUMLAH_KEMASAN,
                                h.NOPEN, h.TGL_NOPEN, h.NOAJU, TGL_AJU, TGL_TERIMA, 
                                NDPBM, h.CURR, JALUR
                                FROM tbl_penarikan_header h
                                LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                                LEFT JOIN importir imp ON h.IMPORTIR = imp.IMPORTIR_ID
                                WHERE h.ID = '" .$id ."'")->get()[0];
        $cif = $header->CIF;        
        $detail = $this->query("SELECT db.*, s.satuan AS NAMASATUAN, db.CIF,
                                db.CIF/db.JMLSATHARGA as HARGASATUAN,
                                jk.URAIAN AS NAMAJENISKEMASAN
                                FROM tbl_detail_barang db                                   
                                INNER JOIN tbl_penarikan_header h
                                on h.ID = db.ID_HEADER
                                LEFT JOIN satuan s
                                on db.SATUAN_ID = s.id
                                LEFT JOIN ref_jenis_kemasan jk
                                on db.JENISKEMASAN = jk.JENISKEMASAN_ID
                                WHERE h.ID = '" .$id ."'")->get();
        foreach ($detail as $key=>$det){
            $detail[$key]->files = $this->query("SELECT ID, FILEREALNAME from tbl_files 
                                                WHERE AKTIF = 'Y' AND ID_HEADER = ' AND TYPE = 1" 
                                                .$det->ID ."'")->get();
        }
        $header->TGL_NOPEN = $header->TGL_NOPEN == "" ? "" : Date("d-m-Y", strtotime($header->TGL_NOPEN));
        $header->TGL_KELUAR = $header->TGL_KELUAR == "" ? "" : Date("d-m-Y", strtotime($header->TGL_KELUAR));
        $header->TGL_BL = $header->TGL_BL == "" ? "" : Date("d-m-Y", strtotime($header->TGL_BL));
        $header->TGL_LS = $header->TGL_LS == "" ? "" : Date("d-m-Y", strtotime($header->TGL_LS));
        $header->TGL_INV = $header->TGL_INV == "" ? "" : Date("d-m-Y", strtotime($header->TGL_INV));
        $header->TGL_FORM = $header->TGL_FORM == "" ? "" : Date("d-m-Y", strtotime($header->TGL_FORM));
        $header->TGL_TERIMA = $header->TGL_TERIMA == "" ? "" : Date("d-m-Y", strtotime($header->TGL_TERIMA));
        $header->TGL_SPPB = $header->TGL_SPPB == "" ? "" : Date("d-m-Y", strtotime($header->TGL_SPPB));
        $header->TGL_AJU = $header->TGL_AJU == "" ? "" : Date("d-m-Y", strtotime($header->TGL_AJU));
        $header->TGL_KONVERSI = $header->TGL_KONVERSI == "" ? "" : Date("d-m-Y", strtotime($header->TGL_KONVERSI));

        return Array("header" => $header, "detail" => $detail);
    }
    public function browseKonversi($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2, $kategori3, $dari3, $sampai3)
    {   
        $array1 =  Array("No Inv" => "NO_INV", "Nopen" => "NOPEN","No BL" => "NO_BL", "No Kontainer" => "NOMOR_KONTAINER","Hasil Periksa" => "HASIL_PERIKSA");

        $array2 = Array("Tanggal Keluar" => "TGL_KELUAR", "Tanggal Konversi" => "TGL_KONVERSI",
                       "Tanggal Nopen" => "TGL_NOPEN","Tanggal SPPB" => "TGL_SPPB", "Tanggal Tiba" => "TGL_TIBA");
        $where = "";
        if ($kategori1 != ""){
            if (trim($isikategori1) == ""){
                $where  .=  "(" .$array1[$kategori1] ." IS NULL OR " .$array1[$kategori1] ." = '')";
            }
            else {
                $where  .=  "(" .$array1[$kategori1] ." LIKE '%" .$isikategori1 ."%')";
            }

        }
        if ($kategori2 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  "(" .$array2[$kategori2] ." IS NULL OR " .$array2[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        if ($kategori3 != ""){
            if ($where != ""){
                $where .= " AND ";
            }
            if (trim($dari3) == "" && trim($sampai3) == ""){
                $where  .=  "(" .$array2[$kategori3] ." IS NULL OR " .$array2[$kategori3] ." = '')";
            }
            else {
                if (trim($dari3) == ""){
                    $dari3 = "0000-00-00";
                }
                if (trim($sampai3) == ""){
                    $sampai3 = "9999-99-99";
                }
                $where  .=  "(" .$array2[$kategori3] ." BETWEEN '" .Date("Y-m-d", strtotime($dari3)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai3)) ."')";
            }            
        }
        if (trim($customer) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."id_customer = '" .$customer ."'";
        }           
        if (trim($importir) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."importir_id = '" .$importir ."'";
        }           
        if (trim($kantor) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."KANTOR_ID = '" .$kantor ."'";
        }
        $where = trim($where) != "" ? " WHERE $where " : "";

        $data = $this->query("SELECT ID, KANTOR_ID, i.importir_id, c.id_customer,
                              NOPEN, i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER,
                              DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,
                              DATE_FORMAT(TGL_DOK_TRM, '%d-%m-%Y') AS TGLDOKTRM,
                              DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,
                              DATE_FORMAT(TGL_SPPB, '%d-%m-%Y') AS TGLSPPB, 
                              DATE_FORMAT(TGL_KELUAR, '%d-%m-%Y') AS TGLKELUAR,
                              DATE_FORMAT(TGL_KONVERSI, '%d-%m-%Y') AS TGLKONVERSI,
                              NO_BL, NO_INV, JUMLAH_KEMASAN,
                              IF(JALUR = '' OR JALUR IS NULL, '',
                              CASE  WHEN JALUR = 'K' THEN 'Kuning'
                                    WHEN JALUR = 'M' THEN 'Merah'
                                    WHEN JALUR = 'H' THEN 'Hijau'
                                    END) AS JALURDOK,
                            (SELECT GROUP_CONCAT(NOMOR_KONTAINER)
                              FROM tbl_penarikan_kontainer d where d.ID_HEADER = h.ID)
                              AS NOMOR_KONTAINER 
                              FROM tbl_penarikan_header h 
                              LEFT JOIN importir i ON h.IMPORTIR = i.importir_id
                              LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                              " .$where);

        return $data->get();        
    }
    public function getKonversi($id)
    {
        $header = $this->query("SELECT h.ID, h.KODEBARANG, pb.NDPBM, h.URAIAN, h.ID_HEADER, h.HARGA                                
                                FROM tbl_detail_barang h
                                INNER JOIN tbl_penarikan_header pb
                                on h.ID_HEADER = pb.ID
                                WHERE h.ID = '" .$id ."'")->get()[0];
        $konversi = $this->query("SELECT k.*, DATE_FORMAT(TGL_TERIMA, '%d-%m-%Y') AS TGLTERIMA,
                                p.kode AS KODEPRODUK,p.nama AS NAMAPRODUK 
                                FROM tbl_konversi k                                   
                                INNER JOIN produk p
                                on p.id = k.PRODUK_ID                           
                                WHERE ID_HEADER = '" .$id ."'")->get();

        return Array("header" => $header, "konversi" => $konversi);
    }
    public function getMutasiBarang($id)
    {
        $header = $this->query("SELECT db.*, 
                                    DATE_FORMAT(TGL_KELUAR,'%d-%m-%Y') AS TGLKELUAR,
                                    s.satuan FROM tbl_detail_mutasi db                                   
                                   INNER JOIN satuan s
                                   on db.satuan_id = s.id
                                   WHERE ID_HEADER = '" .$id ."'")->get();         
                
        return Array($header);
    }
    public function saveMutasiBarang($header, $detail, $files){                              
        $check = $this->query("SELECT ID FROM tbl_perekaman_barang WHERE ID_HEADER = '" .$header["idtransaksi"] ."'");
        $arrHeader = Array("TGL_KELUAR" => trim($header["tglkeluar"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkeluar"])),
                           "TGL_TERIMA" => trim($header["tglterimabrg"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglterimabrg"]))
                          );        
        $this->setTableName("tbl_perekaman_barang");
        if ($check->num_rows() == 0){            
            $arrHeader["ID_HEADER"] = $header["idtransaksi"];
            $this->save($arrHeader);
            $id = $this->getAutoInc();
        }
        else {
            $id = $check->current()->ID;
            $this->updateBy("ID", $id, $arrHeader);                   
        }        
        
        $this->setTableName("tbl_files");        
        $oldFiles = Array();
        if (!is_array($files)){
            $files = Array();
        }
        $dtFiles = $this->query("SELECT GROUP_CONCAT(ID) AS STR FROM tbl_files WHERE ID_HEADER = '$id' AND TYPE = 1");
        if ($dtFiles->num_rows() > 0){
            $oldFiles = explode(",",$dtFiles->current()->STR);
        }
        $diff = array_diff($files, $oldFiles);
        if (count($diff) > 0){
            $strFile = "('" .implode("','", $diff) ."')";
            $this->update(["ID_HEADER" => $id, "AKTIF" => "Y"], "ID IN " .$strFile);
        }        
        $diff = array_diff($oldFiles, $files);
        if (count($diff) > 0){
            $strFile = "('" .implode("','", $diff) ."')";
            $this->delete("ID IN " .$strFile);
        }
        $this->setTableName("tbl_detail_barang");
        $arrDetail = Array();
        if (is_array($detail) && count($detail) > 0){
            foreach ($detail as $item){
                $arrDetail[] = Array("ID_HEADER" => $id,                                            
                                    "SERIBARANG" => $item["SERIBARANG"],
                                    "KODEBARANG" => $item["KODEBARANG"],
                                    "PRODUK_ID" => $item["PRODUK_ID"],
                                    "TGL_TERIMA" => trim($item["TGL_TERIMA"]) == "" ? NULL : Date("Y-m-d", strtotime($item["TGL_TERIMA"])),
                                    "JMLKEMASAN" => $item["JMLKEMASAN"] != "" ? str_replace(",","",$item["JMLKEMASAN"]) : 0,
                                    "JMLSATHARGA" => $item["JMLSATHARGA"] != "" ? str_replace(",","",$item["JMLSATHARGA"]) : 0,
                                    "HARGA" => $item["HARGA"] != "" ? str_replace(",","",$item["HARGA"]) : 0
                                    );
            }
        }
        $this->deleteBy("ID_HEADER",$id);
        $this->insert($arrDetail);
    }
    public function stokProduk($importir, $dari, $sampai, $kategori1, $isikategori1)
    {   
        $array1 =  Array("Kode Produk" => "p.kode");

        $where = "WHERE 1 = 1 ";
        $whereProduk = "";
        $joinType = "LEFT JOIN";
        if ($kategori1 != ""){
            if (trim($isikategori1) != ""){
                $where  .=  " AND " .$array1[$kategori1] ." Like '%" .$isikategori1 ."%'";
                $whereProduk = "WHERE p.kode LIKE '%" .$isikategori1 ."%'";
            }
        }
        if (trim($importir) != ""){
            $where .= " AND IMPORTIR = '" .$importir ."'";
            $joinType = "INNER JOIN";
        }     
        $data = $this->query("SELECT p.id, kode, 
                          SUM(IFNULL(t.kemasansawal,0)) as kemasansawal, 
                          SUM(IFNULL(t.kemasanmasuk,0)) as kemasanmasuk, 
                          SUM(IFNULL(t.kemasankeluar,0)) As kemasankeluar,
                          SUM(IFNULL(t.kemasansawal,0)) + SUM(IFNULL(t.kemasanmasuk,0)) - SUM(IFNULL(t.kemasankeluar,0)) as kemasansakhir,
                          SUM(IFNULL(t.satuansawal,0)) as satuansawal, 
                          SUM(IFNULL(t.satuanmasuk,0)) as satuanmasuk, 
                          SUM(IFNULL(t.satuankeluar,0)) As satuankeluar,
                          SUM(IFNULL(t.satuansawal,0)) + SUM(IFNULL(t.satuanmasuk,0)) - SUM(IFNULL(t.satuankeluar,0)) as satuansakhir,
                          t.satuankemasan, t.satuan FROM
                          produk p " .$joinType ."
                          (SELECT p.id, 
                          JMLKEMASAN AS kemasansawal, 0 as kemasanmasuk, 0 as kemasankeluar,
                          JMLSATHARGA AS satuansawal, 0 as satuanmasuk, 0 as satuankeluar,
                          '' as satuankemasan, s.satuan as satuan
                          FROM tbl_konversi tk INNER JOIN tbl_detail_barang tb on tk.ID_HEADER = tb.ID
                          INNER JOIN tbl_penarikan_header th ON tb.ID_HEADER = th.ID
                          INNER JOIN produk p ON tk.produk_id = p.id
                          INNER JOIN satuan s ON tb.SATUAN_ID = s.id
                          $where AND tk.TGL_TERIMA < '" .Date("Y-m-d", strtotime($dari)) ."'
                          UNION ALL
                          SELECT p.id, 
                          -JMLKMSKELUAR AS kemasansawal, 0 as kemasanmasuk, 0 as kemasankeluar,
                          -JMLSATHARGAKELUAR satuansawal, 0 as satuanmasuk, 0 as satuankeluar,
                          '' as satuankemasan, s.satuan as satuan
                          FROM tbl_konversi tk INNER JOIN tbl_detail_barang tb on tk.ID_HEADER = tb.ID
                          INNER JOIN detail_do do ON tb.ID = do.KODEBARANG
                          INNER JOIN tbl_penarikan_header th ON tb.ID_HEADER = th.ID
                          INNER JOIN produk p ON tk.produk_id = p.id
                          INNER JOIN satuan s ON tb.SATUAN_ID = s.id
                          $where AND do.TGL_KELUAR < '" .Date("Y-m-d", strtotime($dari)) ."'
                          UNION ALL
                          SELECT p.id, 
                          0 AS kemasansawal, JMLKEMASAN as kemasanmasuk, 0 as kemasankeluar,
                          0 AS satuansawal,  JMLSATHARGA as satuanmasuk, 0 as satuankeluar,
                          '' as satuankemasan, s.satuan as satuan
                          FROM tbl_konversi tk INNER JOIN tbl_detail_barang tb on tk.ID_HEADER = tb.ID
                          INNER JOIN tbl_penarikan_header th ON tb.ID_HEADER = th.ID
                          INNER JOIN produk p ON tk.produk_id = p.id
                          INNER JOIN satuan s ON tb.SATUAN_ID = s.id
                          $where AND tk.TGL_TERIMA BETWEEN '" .Date("Y-m-d", strtotime($dari)) ."' AND '" .Date("Y-m-d", strtotime($sampai)) ."'
                          UNION ALL
                          SELECT p.id, 
                          0 AS kemasansawal, 0 as kemasanmasuk, JMLKMSKELUAR as kemasankeluar,
                          0 satuansawal, 0 as satuanmasuk, JMLSATHARGAKELUAR as satuankeluar,
                          '' as satuankemasan, s.satuan as satuan
                          FROM tbl_konversi tk INNER JOIN tbl_detail_barang tb on tk.ID_HEADER = tb.ID
                          INNER JOIN detail_do do ON tb.ID = do.KODEBARANG
                          INNER JOIN tbl_penarikan_header th ON tb.ID_HEADER = th.ID
                          INNER JOIN produk p ON tk.produk_id = p.id
                          INNER JOIN satuan s ON tb.SATUAN_ID = s.id
                          $where AND do.TGL_KELUAR BETWEEN '" .Date("Y-m-d", strtotime($dari)) ."' AND '" .Date("Y-m-d", strtotime($sampai)) ."'
                          ) t
                          ON p.id = t.id                               
                          $whereProduk
                          GROUP BY id, p.kode, t.satuankemasan, t.satuan");
        //echo $data->printquery();die();
        return $data->get();        
    }
    public function detailStokProduk($importir, $dari, $sampai, $id)
    {   
        $where = "WHERE p.id = '" .$id ."'" .($importir != "" ? " AND IMPORTIR = $importir " : "");
        $data = $this->query("
                          SELECT p.id, tb.KODEBARANG, i.NAMA, tk.TGL_TERIMA AS TANGGAL, 
                          0 AS kemasansawal, JMLKEMASAN as kemasanmasuk, 0 as kemasankeluar,
                          0 AS satuansawal,  JMLSATHARGA as satuanmasuk, 0 as satuankeluar,
                          '' as satuankemasan, s.satuan as satuan
                          FROM tbl_konversi tk INNER JOIN tbl_detail_barang tb on tk.ID_HEADER = tb.ID
                          INNER JOIN tbl_penarikan_header th ON tb.ID_HEADER = th.ID
                          INNER JOIN produk p ON tk.produk_id = p.id
                          INNER JOIN satuan s ON tb.SATUAN_ID = s.id
                          INNER JOIN importir i ON th.IMPORTIR = i.IMPORTIR_ID
                          $where AND (tk.TGL_TERIMA BETWEEN '" .Date("Y-m-d", strtotime($dari)) ."' AND '" .Date("Y-m-d", strtotime($sampai)) ."')
                          UNION ALL
                          SELECT p.id, tb.KODEBARANG, i.NAMA, do.TGL_KELUAR AS TANGGAL,
                          0 AS kemasansawal, 0 as kemasanmasuk, JMLKMSKELUAR as kemasankeluar,
                          0 satuansawal, 0 as satuanmasuk, JMLSATHARGAKELUAR as satuankeluar,
                          '' as satuankemasan, s.satuan as satuan
                          FROM tbl_konversi tk INNER JOIN tbl_detail_barang tb on tk.ID_HEADER = tb.ID
                          INNER JOIN detail_do do ON tb.ID = do.KODEBARANG
                          INNER JOIN tbl_penarikan_header th ON tb.ID_HEADER = th.ID
                          INNER JOIN produk p ON tk.produk_id = p.id
                          INNER JOIN satuan s ON tb.SATUAN_ID = s.id
                          INNER JOIN importir i ON th.IMPORTIR = i.IMPORTIR_ID
                          $where AND (do.TGL_KELUAR BETWEEN '" .Date("Y-m-d", strtotime($dari)) ."' AND '" .Date("Y-m-d", strtotime($sampai)) ."')
                          ORDER BY TANGGAL");
        //echo $data->printquery();die();
        return $data->get();        
    }
    public function stokBarang($customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {   
        $array1 =  Array("Saldo Akhir" => "t.sakhir", "Kode Barang" => "tb.KODEBARANG");
        $array2 = Array("Tanggal Terima" => "t.TGL_TERIMA", 'Tanggal DO' => "do.TGL_DO");
        $dari2 = Date("Y-m-d", strtotime($dari2));
        $sampai2 = Date("Y-m-d", strtotime($sampai2));

        $where = "";
        if ($kategori1 != ""){
            $where  .=  " AND " .$array1[$kategori1] ." LIKE '%" .trim($isikategori1) ."%'";
        }
        if (trim($customer) != ""){
            $where .= " AND th.CUSTOMER = '" .$customer ."'";
        }
        if (trim($importir) != ""){
            $where .= " AND IMPORTIR = '" .$importir ."'";
        }           
        $where = trim($where) != "" ? " WHERE 1 = 1 $where" : "";
        $data = $this->query("SELECT t.ID, t.KODEBARANG, p.kode, c.nama_customer AS CUSTOMER, t.NDPBM, t.FAKTUR, t.NOAJU, SUM(TGLDO) AS CEKDO,
                              t.HARGA, t.DPP, t.TGL_TERIMA, 
                              SUM(IFNULL(t.kemasansawal,0)) as kemasansawal, 
                              SUM(IFNULL(t.kemasanmasuk,0)) as kemasanmasuk, 
                              SUM(IFNULL(t.kemasankeluar,0)) As kemasankeluar,
                              SUM(IFNULL(t.kemasansawal,0)) + SUM(IFNULL(t.kemasanmasuk,0)) - SUM(IFNULL(t.kemasankeluar,0)) as kemasansakhir,
                              SUM(IFNULL(t.satuansawal,0)) as satuansawal, 
                              SUM(IFNULL(t.satuanmasuk,0)) as satuanmasuk, 
                              SUM(IFNULL(t.satuankeluar,0)) As satuankeluar,
                              SUM(IFNULL(t.satuansawal,0)) + SUM(IFNULL(t.satuanmasuk,0)) - SUM(IFNULL(t.satuankeluar,0)) as satuansakhir,
                              t.satuankemasan, t.satuan
                              FROM                              
                              (
                              SELECT tb.ID, tb.KODEBARANG, tk.produk_id, th.CUSTOMER, th.NOAJU, 0 AS TGLDO,
                              IF(th.FAKTUR = 'A', 'Semua', IF(th.FAKTUR = 'P', 'Sebagian', IF (th.FAKTUR = 'T', 'Tidak', ''))) AS FAKTUR, th.NDPBM, 
                              tb.HARGA, tk.DPP, tk.TGL_TERIMA, 
                              JMLKEMASAN AS kemasansawal, 0 as kemasanmasuk, 0 as kemasankeluar,
                              JMLSATHARGA AS satuansawal, 0 as satuanmasuk, 0 as satuankeluar,
                              '' as satuankemasan, s.satuan as satuan
                              FROM tbl_konversi tk INNER JOIN tbl_detail_barang tb on tk.ID_HEADER = tb.ID
                              INNER JOIN tbl_penarikan_header th ON tb.ID_HEADER = th.ID            
                              INNER JOIN produk p ON tk.produk_id = p.id
                              INNER JOIN satuan s ON tb.SATUAN_ID = s.id
                              $where AND tk.TGL_TERIMA < '$dari2'
                              UNION ALL 
                              SELECT tb.ID, tb.KODEBARANG, tk.produk_id, th.CUSTOMER, th.NOAJU, 0 AS TGLDO,                              
                              IF(th.FAKTUR = 'A', 'Semua', IF(th.FAKTUR = 'P', 'Sebagian', IF (th.FAKTUR = 'T', 'Tidak', ''))) AS FAKTUR, th.NDPBM,
                              tb.HARGA, tk.DPP, tk.TGL_TERIMA,
                              -do.JMLKMSKELUAR AS kemasansawal, 0 as kemasanmasuk, 0 as kemasankeluar,
                              -do.JMLSATHARGAKELUAR AS satuansawal, 0 as satuanmasuk, 0 as satuankeluar,
                              '' as satuankemasan, s.satuan as satuan
                              FROM 
                              detail_do do INNER JOIN tbl_detail_barang tb 
                              on do.KODEBARANG = tb.ID
                              INNER JOIN tbl_konversi tk  
                              on tk.ID_HEADER = tb.ID
                              INNER JOIN tbl_penarikan_header th ON tb.ID_HEADER = th.ID
                              INNER JOIN produk p ON tk.produk_id = p.id
                              INNER JOIN satuan s ON tb.SATUAN_ID = s.id
                              $where AND do.TGL_KELUAR < '$dari2'
                              UNION ALL
                              SELECT tb.ID, tb.KODEBARANG, tk.produk_id, th.CUSTOMER, th.NOAJU, 0 AS TGLDO,
                              IF(th.FAKTUR = 'A', 'Semua', IF(th.FAKTUR = 'P', 'Sebagian', IF (th.FAKTUR = 'T', 'Tidak', ''))) AS FAKTUR, th.NDPBM, 
                              tb.HARGA, tk.DPP, tk.TGL_TERIMA,
                              0 AS kemasansawal, JMLKEMASAN as kemasanmasuk, 0 as kemasankeluar,
                              0 AS satuansawal, JMLSATHARGA as satuanmasuk, 0 as satuankeluar,
                              '' as satuankemasan, s.satuan as satuan
                              FROM tbl_konversi tk INNER JOIN tbl_detail_barang tb on tk.ID_HEADER = tb.ID
                              INNER JOIN tbl_penarikan_header th ON tb.ID_HEADER = th.ID
                              INNER JOIN produk p ON tk.produk_id = p.id
                              INNER JOIN satuan s ON tb.SATUAN_ID = s.id
                              $where AND tk.TGL_TERIMA BETWEEN '$dari2' AND '$sampai2'
                              UNION ALL
                              SELECT tb.ID, tb.KODEBARANG, tk.produk_id, th.CUSTOMER, th.NOAJU, IF(do.TGL_KELUAR >= '$dari2' AND do.TGL_KELUAR <= '$sampai2', 1, 0) AS TGLDO,
                              IF(th.FAKTUR = 'A', 'Semua', IF(th.FAKTUR = 'P', 'Sebagian', IF (th.FAKTUR = 'T', 'Tidak', ''))) AS FAKTUR, th.NDPBM, 
                              tb.HARGA, tk.DPP, tk.TGL_TERIMA,
                              0 AS kemasansawal, 0 as kemasanmasuk, do.JMLKMSKELUAR as kemasankeluar,
                              0 AS satuansawal, 0 as satuanmasuk, do.JMLSATHARGAKELUAR as satuankeluar,
                              '' as satuankemasan, s.satuan as satuan
                              FROM 
                              detail_do do INNER JOIN tbl_detail_barang tb 
                              on do.KODEBARANG = tb.ID
                              INNER JOIN tbl_konversi tk  
                              on tk.ID_HEADER = tb.ID
                              INNER JOIN tbl_penarikan_header th ON tb.ID_HEADER = th.ID
                              INNER JOIN produk p ON tk.produk_id = p.id
                              INNER JOIN satuan s ON tb.SATUAN_ID = s.id
                              $where AND do.TGL_KELUAR BETWEEN '$dari2' AND '$sampai2') t
                              INNER JOIN produk p
                              ON p.id = t.produk_id
                              INNER JOIN plbbandu_app15.tb_customer c ON c.id_customer = t.CUSTOMER "
                              .($kategori2 == 'Tanggal Terima' ? "WHERE " .$array2[$kategori2] ." BETWEEN '$dari2' AND '$sampai2'" : "")
                              ." GROUP BY t.ID, t.KODEBARANG, p.kode, c.nama_customer, t.NDPBM, t.FAKTUR, t.NOAJU,
                                       t.HARGA, t.DPP, t.TGL_TERIMA, t.satuankemasan, t.satuan"
                              .($kategori2 == 'Tanggal DO' ? " HAVING SUM(TGLDO) > 0 " : "")

                                       );
        //echo $data->printquery();die();
        return $data->get();        
    }
    public function getTransaksiDOrder($id, $includeDetail = TRUE, $searchBy = "ID")
    {
        $header = $this->query("SELECT d.*, IFNULL(t.TOTJMLKEMASAN,0) AS TOTJMLKMSKELUAR, 
                                (SELECT IFNULL(SUM(JMLKEMASAN),0) FROM tbl_pengeluaran p
                                WHERE p.ID_HEADER = d.ID) AS TOTALMUAT,
                                IFNULL(t.TOTJMLSATHARGA,0) AS TOTJMLSATHARGAKELUAR
                                FROM deliveryorder d
                                LEFT JOIN (
                                    SELECT ID_HEADER, SUM(IFNULL(JMLKMSKELUAR,0)) AS TOTJMLKEMASAN,
                                    SUM(IFNULL(JMLSATHARGAKELUAR,0)) AS TOTJMLSATHARGA
                                    FROM detail_do
                                    GROUP BY ID_HEADER
                                ) t
                                ON d.ID = t.ID_HEADER
                                WHERE d." .$searchBy ." = '" .$id ."'");
        
        if ($header->num_rows() > 0){
            $header = $header->get()[0];
            $header->TGL_DO = $header->TGL_DO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_DO));
            $header->TGL_INV_JUAL = $header->TGL_INV_JUAL == "" ? "" : Date("d-m-Y", strtotime($header->TGL_INV_JUAL));

            $return["header"] = $header;
            if ($includeDetail){
                $detail = $this->query("SELECT d.ID, d.KODEBARANG AS KODEBARANG_ID, 
                                    p.kode, db.KODEBARANG, JMLKMSKELUAR,
                                    JMLSATHARGAKELUAR, DATE_FORMAT(TGL_KELUAR,'%d-%m-%Y') AS TGL_KELUAR,
                                    dk.HARGAJUAL
                                    FROM detail_do d
                                    INNER JOIN tbl_detail_barang db
                                    ON d.KODEBARANG = db.ID
                                    INNER JOIN tbl_konversi dk
                                    ON db.ID = dk.ID_HEADER
                                    INNER JOIN produk p
                                    ON dk.PRODUK_ID = p.id
                                    WHERE d.ID_HEADER = '" .$id ."'")->get();
                $return["detail"] = $detail;
            }
            return $return;
        }
        else {
            return false;
        }
    }
    public function saveTransaksiDorder($action, $header, $detail, $pengeluaran)
    {       
        $arrHeader = Array("TGL_INV_JUAL" => trim($header["tglinvjual"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tglinvjual"])),
                           "NO_INV_JUAL" => trim($header["noinvjual"]), "NO_DO" => trim($header["nodo"]),
                           "TGL_DO" => trim($header["tgldo"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tgldo"])),
                           "PEMBELI" => trim($header["pembeli"]) == "" ? NULL : $header["pembeli"],
                           "TOTAL" => trim($header["total"]) == "" ? NULL : str_replace(",","", $header["total"]));
        $this->setTableName("deliveryorder");
        if ($action == "insert"){            
            $this->save($arrHeader);
            $idtransaksi = $this->getAutoInc();
            $this->setTableName("detail_do");
        }
        else if ($action == "update"){
            $idtransaksi = $header["idtransaksi"];
            $this->updateBy("ID", $idtransaksi, $arrHeader); 
            $this->setTableName("detail_do");
            $this->deleteBy("ID_HEADER", $idtransaksi);
        }
        $arrDetail = Array();
        if (is_array($detail) && count($detail) > 0){
            foreach ($detail as $item){
                $arrDetail[] = Array("ID_HEADER" => $idtransaksi,                                            
                                    "KODEBARANG" => $item["KODEBARANG_ID"],
                                    "TGL_KELUAR" => Date("Y-m-d", strtotime($item["TGL_KELUAR"])),
                                    "JMLKMSKELUAR" => $item["JMLKMSKELUAR"] != "" ? str_replace(",","",$item["JMLKMSKELUAR"]) : 0,
                                    "JMLSATHARGAKELUAR" => $item["JMLSATHARGAKELUAR"] != "" ? str_replace(",","",$item["JMLSATHARGAKELUAR"]) : 0
                                    );
            }
            $this->insert($arrDetail);
        }
        $this->savePengeluaran($idtransaksi, $pengeluaran);
    }
    public function getDataPengeluaran($id)
	{		
        $data = $this->query("SELECT ID,ID_HEADER, DATE_FORMAT(TGL_MUAT,'%d-%m-%Y') AS TGL_MUAT, 
                              NO_POL, NO_SJ, DRIVER, REMARKS,JMLKEMASAN FROM tbl_pengeluaran WHERE ID_HEADER = " .$id);
		return $data->get();
    }
    public function savePengeluaran($id, $data)
    {
        $this->setTableName("tbl_pengeluaran");
        $this->deleteBy("ID_HEADER", $id);
        $arrDetail = Array();
        if (is_array($data) && count($data) > 0){
            foreach ($data as $item){
                $arrDetail[] = Array("ID_HEADER" => $id,                                            
                                    "NO_POL" => $item["NO_POL"],
                                    "DRIVER" => $item["DRIVER"],
                                    "NO_SJ" => $item["NO_SJ"],
                                    "TGL_MUAT" => Date("Y-m-d", strtotime($item["TGL_MUAT"])),
                                    "JMLKEMASAN" => $item["JMLKEMASAN"] != "" ? str_replace(",","",$item["JMLKEMASAN"]) : 0,
                                    "REMARKS" => $item["REMARKS"]);
            }
            $this->insert($arrDetail);
        }
    }
    public function getDetailStokBarang($id, $kategori, $dari, $sampai)
    {
        $dari = Date("Y-m-d", strtotime($dari));
        $sampai = Date("Y-m-d", strtotime($sampai));
        $data = $this->query("SELECT tb.KODEBARANG, dorder.ID, NO_DO, DATE_FORMAT(TGL_DO,'%d-%m-%Y') AS TGL_DO, 
                            NO_INV_JUAL, DATE_FORMAT(TGL_KELUAR,'%d-%m-%Y') AS TGL_KELUAR, 
                            IFNULL(JMLKMSKELUAR,0) As kemasankeluar,
                            IFNULL(JMLSATHARGAKELUAR,0) as satuankeluar,
                            '' as satuankemasan, s.satuan as satuan
                            FROM detail_do do
                            INNER JOIN tbl_detail_barang tb
                            ON do.KODEBARANG = tb.ID
                            INNER JOIN deliveryorder dorder
                            ON do.ID_HEADER = dorder.ID
                            INNER JOIN satuan s ON tb.SATUAN_ID = s.id                            
                            WHERE do.KODEBARANG = '" .$id ."'
                            AND TGL_KELUAR BETWEEN '$dari' AND '$sampai'");
        //echo $data->printquery();die();
        return $data->get();
    }
    public function getTransaksiQuota($id, $includeDetail = true)
    {
        $header = $this->query("SELECT ID, CONSIGNEE, NO_PI, TGL_PI, TGL_BERLAKU, STATUS 
                                FROM tbl_header_quota h                                
                                WHERE ID = '" .$id ."'");
        
        if ($header->num_rows() > 0){
            $header = $header->get()[0];
            $header->TGL_PI = $header->TGL_PI == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PI));
            $header->TGL_BERLAKU = $header->TGL_BERLAKU == "" ? "" : Date("d-m-Y", strtotime($header->TGL_BERLAKU));        

            $return["header"] = $header;
            if ($includeDetail){
                $detail = $this->query("SELECT d.NO, d.KODE_HS, d.SALDO_AWAL, d.SATUAN_ID, s.satuan 
                                    FROM tbl_detail_quota d
                                    INNER JOIN satuan s
                                    ON d.SATUAN_ID = s.id
                                    WHERE d.ID_HEADER = '" .$id ."'")->get();
                $return["detail"] = $detail;
            }
            return $return;
        }
        else {
            return false;
        }

        return $header;
    }
    public function saveTransaksiQuota($action, $header, $detail, $files)
    {       
        $arrHeader = Array("TGL_PI" => trim($header["tglpi"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tglpi"])),
                           "NO_PI" => trim($header["nopi"]),  "STATUS" => $header["status"],
                           "TGL_BERLAKU" => trim($header["tglberlaku"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tglberlaku"])),
                           "CONSIGNEE" => $header["consignee"]);
        $this->setTableName("tbl_header_quota");
        $oldId = "";
        $check = $this->query("SELECT ID FROM tbl_header_quota WHERE STATUS = 'Y' AND CONSIGNEE = " .$header["consignee"]);
        if ($check->num_rows() > 0){
            $oldId = $check->current()->ID;
        }
        if ($action == "insert"){            
            $arrHeader["STATUS"] = "Y";
            $this->save($arrHeader);
            $idtransaksi = $this->getAutoInc();
            if ($header["status"] == "Y"){
                $this->updateBy("ID", $oldId, Array("STATUS" => 'T', 'TGL_EXPIRE' => Date("Y-m-d")));
            }
            $this->setTableName("tbl_detail_quota");
        }
        else if ($action == "update"){
            $idtransaksi = $header["idtransaksi"];
            $this->updateBy("ID", $idtransaksi, $arrHeader); 
            $this->setTableName("tbl_detail_quota");
            $this->deleteBy("ID_HEADER", $idtransaksi);
        }
        $arrDetail = Array();
        if (is_array($detail) && count($detail) > 0){
            foreach ($detail as $item){
                $arrDetail[] = Array("ID_HEADER" => $idtransaksi,                                            
                                    "NO" => $item["NO"],
                                    "KODE_HS" => $item["KODE_HS"],
                                    "SALDO_AWAL" => $item["SALDO_AWAL"] != "" ? str_replace(",","",$item["SALDO_AWAL"]) : 0,
                                    "SATUAN_ID" => $item["SATUAN_ID"]
                                    );
            }
            $this->insert($arrDetail);
        }
        $this->setTableName("tbl_files");
        $oldFiles = Array();
        if (!is_array($files)){
            $fileIds = Array();
        }
        else {
            $fileIds = array_map(function($elem){
                return $elem["id"];
            }, $files);
        }
        $dtFiles = $this->query("SELECT GROUP_CONCAT(ID) AS STR FROM tbl_files WHERE TYPE = 2 AND ID_HEADER = '$idtransaksi'");
        if ($dtFiles->num_rows() > 0){
            $oldFiles = explode(",",$dtFiles->current()->STR);
        }
        $diff = array_diff($fileIds, $oldFiles);
        if (count($diff) > 0){
            $strFile = "('" .implode("','", $diff) ."')";
            $this->update(["ID_HEADER" => $idtransaksi, "AKTIF" => "Y"], "ID IN " .$strFile);
        }        
        $diff = array_diff($oldFiles, $fileIds);
        if (count($diff) > 0){
            $strFile = "('" .implode("','", $diff) ."')";
            $this->delete("ID IN " .$strFile);
        }
    }
    public function getRealisasiQuota($id)
    {
        $data = $this->query("SELECT h.ID, h.ID_HEADER, h.KODE_HS, h.BOOKING, h.REALISASI,
                                 h.SATUAN_ID, s.satuan FROM tbl_realisasi_quota h
                                 INNER JOIN satuan s ON h.SATUAN_ID = s.id 
                                 WHERE ID_HEADER = '$id'");
        return $data->get();
    }
    public function getPI($consignee = "")
    {
        $where = "";
        if ($consignee != ""){
            $where = "AND CONSIGNEE = '$consignee'";
        }
        $data = $this->query("SELECT ID, NO_PI, DATE_FORMAT(TGL_PI,'%d-%m-%Y') AS TGLPI FROM tbl_header_quota WHERE STATUS = 'Y' $where");
        if ($data->num_rows() > 0){
            if ($consignee == ""){
                return $data->get();
            }
            else {
                return $data->current();
            }
        }
        else {
            return false;
        }
    }
    public function saldoQuota($importir, $kategori1, $isiKategori1)
    {
        $kategori = Array("Kode HS" => "d.KODE_HS");
        $where = $importir != "" ? "AND IMPORTIR = '$importir'" : "";
        if ($kategori1 != ""){
            $where .= " AND " .$kategori[$kategori1] ." LIKE '%" .$isiKategori1 ."%'";
        }
        $data = $this->query("SELECT t.ID, i.NAMA AS NAMAIMPORTIR, t.KODE_HS, s.satuan AS SATUAN, SUM(IFNULL(t.AWAL,0)) AS AWAL, SUM(IFNULL(t.TERPAKAI,0)) AS TERPAKAI,
                              SUM(IFNULL(t.AWAL,0)) - SUM(IFNULL(t.TERPAKAI,0)) AS AKHIR
                              FROM (
                                  SELECT DISTINCT hq.ID AS ID, h.IMPORTIR, d.KODE_HS, d.SALDO_AWAL AS AWAL, 0 AS TERPAKAI, d.SATUAN_ID
                                  FROM tbl_detail_quota d INNER JOIN tbl_header_quota hq
                                  ON d.ID_HEADER = hq.ID
                                  INNER JOIN tbl_penarikan_header h ON hq.CONSIGNEE = h.CONSIGNEE
                                  WHERE hq.STATUS = 'Y' $where
                                  UNION ALL
                                  SELECT h.NO_PI AS ID, h.IMPORTIR, d.KODE_HS, 0 AS AWAL, IF (REALISASI IS NULL OR REALISASI <= 0, BOOKING, REALISASI) AS TERPAKAI, d.SATUAN_ID
                                  FROM tbl_realisasi_quota d INNER JOIN tbl_penarikan_header h
                                  ON d.ID_HEADER = h.ID
                                  INNER JOIN tbl_header_quota hq ON h.NO_PI = hq.ID
                                  WHERE hq.STATUS = 'Y' $where
                              ) t
                              INNER JOIN tbl_header_quota hq ON t.ID = hq.ID
                              INNER JOIN satuan s ON s.id = t.SATUAN_ID
                              INNER JOIN importir i ON t.IMPORTIR = i.IMPORTIR_ID
                              GROUP BY t.ID, i.NAMA, t.KODE_HS, s.satuan");
        //echo $data->printquery();die();
        return $data->get();
    }
    public function detailSaldoQuota($id, $kodehs)
    {
        $data = $this->query("SELECT i.NAMA AS NAMACONSIGNEE, c.nama_customer AS NAMACUSTOMER, h.NO_VO, h.NO_INV, h.NO_BL, d.BOOKING, d.REALISASI, s.satuan as NAMASATUAN
                              FROM tbl_realisasi_quota d INNER JOIN tbl_penarikan_header h 
                              ON d.ID_HEADER = h.ID
                              INNER JOIN tbl_header_quota hq ON h.NO_PI = hq.ID
                              INNER JOIN importir i ON i.IMPORTIR_ID = h.IMPORTIR
                              INNER JOIN plbbandu_app15.tb_customer c ON c.id_customer = h.CUSTOMER
                              INNER JOIN satuan s ON s.id = d.SATUAN_ID
                              WHERE hq.ID = $id AND d.KODE_HS = '$kodehs'");
        return $data->get();
    }
    public function getTransaksiVoucher($id)
    {
        $header = $this->query("SELECT h.ID, h.TANGGAL, h.NO_BL, h.TOTAL, p.NO_INV, imp.NAMA AS NAMAIMPORTIR, c.nama_customer AS NAMACUSTOMER,
                               p.NOAJU, p.NOPEN, DATE_FORMAT(p.TGL_NOPEN, '%d-%m-%Y) AS TGLNOPEN, p.JUMLAH_KEMASAN, 
                               (SELECT GROUP_CONCAT(k.NOMOR_KONTAINER) AS NO_KONTAINER FROM tbl_penarikan_kontainer k WHERE k.ID_HEADER = p.ID) AS NO_KONTAINER
                               FROM tbl_voucher h INNER JOIN tbl_penarikan_header p
                               ON h.ID = p.NO_BL LEFT JOIN plbbandu_app15.tb_customer c
                               ON p.CUSTOMER = c.id_customer LEFT JOIN importir imp
                               ON p.IMPORTIR = imp.IMPORTIR_ID
                               WHERE h.ID = $id");
        $detail = $this->query("SELECT * FROM tbl_detail_voucher WHERE ID_HEADER = $id");
        if ($header->num_rows() > 0){
            return Array("header" => $header->current(), "detail" => $detail->get());
        }
        else {
            return false;
        }
    }
    public function getBL($no_bl)
    {
        $header = $this->query("SELECT p.NO_INV, imp.NAMA AS NAMAIMPORTIR, c.nama_customer AS NAMACUSTOMER,
                               p.NOAJU, p.NOPEN, DATE_FORMAT(p.TGL_NOPEN, '%d-%m-%Y) AS TGLNOPEN, p.JUMLAH_KEMASAN, 
                               (SELECT GROUP_CONCAT(k.NOMOR_KONTAINER) AS NO_KONTAINER FROM tbl_penarikan_kontainer k WHERE k.ID_HEADER = p.ID) AS NO_KONTAINER
                               FROM tbl_penarikan_header p
                               LEFT JOIN plbbandu_app15.tb_customer c ON p.CUSTOMER = c.id_customer 
                               LEFT JOIN importir imp ON p.IMPORTIR = imp.IMPORTIR_ID
                               WHERE NO_BL = '" .$no_bl ."'");
        if ($header->num_rows() > 0){
            return $header->current();
        }
        else {
            return false;
        }
    }
    public function getRateDPP()
    {
        $rate = $this->query("SELECT RATE FROM ref_rate ORDER BY RATE");
		$datarate = Array();
		if ($rate->num_rows() > 0){
		    foreach ($rate->get() as $row){
    		    $datarate[] = $row->RATE;
		    }
		}
		return $datarate;
    }
    public function browseSPTNP($kantor, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {   
        $array1 =  Array("Nopen" => "NOPEN","No SPTNP" => "NO_SPTNP","Jenis SPTNP" => "JENIS_SPTNP");

        $array2 = Array("Tanggal Jatuh Tempo" => "TGL_JATUH_TEMPO_SPTNP", "Tanggal SPTNP" => "TGL_SPTNP", "Tanggal BRT" => "TGL_BRT");
        $where = "(NO_SPTNP IS NOT NULL AND TRIM(NO_SPTNP) <> '') ";
        if ($kategori1 != ""){
            if (trim($isikategori1) == ""){
                $where  .=  " AND (" .$array1[$kategori1] ." IS NULL OR " .$array1[$kategori1] ." = '')";
            }
            else {
                $where  .=  " AND (" .$array1[$kategori1] ." LIKE '%" .$isikategori1 ."%')";
            }

        }
        if ($kategori2 != ""){
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  " AND (" .$array2[$kategori2] ." IS NULL OR " .$array2[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  " AND (" .$array2[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }            
        }
        if (trim($importir) != ""){
            $where .= " AND IMPORTIR = '" .$importir ."'";
        }           
        if (trim($kantor) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."KANTOR_ID = '" .$kantor ."'";
        }
        $where = trim($where) != "" ? " WHERE $where " : "";
        $data = $this->query("SELECT ID, NO_SPTNP, HSL_BRT, DENDA_TB, JENIS_SPTNP, NOAJU, NOPEN,
                              c.nama_customer AS CUSTOMER, i.NAMA AS IMPORTIR,                            
                              DATE_FORMAT(TGL_SPTNP, '%d-%m-%Y') AS TGLSPTNP,
                              DATE_FORMAT(TGL_JATUH_TEMPO_SPTNP, '%d-%m-%Y') AS TGLJTHTEMPOSPTNP,
                              DATE_FORMAT(TGL_BRT, '%d-%m-%Y') AS TGLBRT,
                              DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,
                              DATE_FORMAT(TGL_LUNAS, '%d-%m-%Y') AS TGLLUNAS,
                              BMTB+BMTTB+PPNTB+PPHTB+DENDA_TB AS TOTAL_TB
                              FROM tbl_penarikan_header h 
                              LEFT JOIN plbbandu_app15.tb_customer c ON h.CUSTOMER = c.id_customer
                              LEFT JOIN importir i ON i.IMPORTIR_ID = h.IMPORTIR 
                              " .$where);
        //echo $data->printquery()
        return $data->get();        
    }
    public function deleteTransaksiDOrder($id)
    {
        if (trim($id) != ""){
            $this->setTableName("detail_do");
            $this->deleteBy("ID_HEADER", $id);
            $this->setTableName("deliveryorder");
            $this->deleteBy("ID", $id);
            $this->setTableName("tbl_pengeluaran");
            $this->deleteBy("ID_HEADER", $id);
        }
    }
    public function deleteTransaksiBayar($id)
    {
        if (trim($id) != ""){
            $this->setTableName("tbl_detail_bayar");
            $this->deleteBy("ID_HEADER", $id);
            $this->setTableName("tbl_header_bayar");
            $this->deleteBy("ID", $id);
        }
    }

}