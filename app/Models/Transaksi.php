<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Bank;
use App\Models\Rekening;
use App\Models\Customer;
use App\Models\Pembayaran;

class Transaksi extends Model
{
    protected $table  = 'job_order';
    protected $primaryKey = 'ID';
    protected $guarded = ['ID'];
    public $timestamps = false;

    public static function getTransaksi($id, $includeDetail = true)
    {
        if ($id == ""){
            $header = new Transaksi;
            $header->TGL_JOB = Date("d-m-Y");
            $header->JOB_ORDER = "(Baru)";
        }
        else {
            $header = Transaksi::where("ID", $id);
            if (!$header->exists()){
                return false;
            }
            $total = DB::table("job_order_detail")
                        ->where("ID_HEADER", $id)
                        ->sum(DB::raw("NOMINAL + PPN"));
            $totalBiaya = DB::table(DB::raw("pembayaran_detail pd"))
                            ->join(DB::raw("pembayaran p"), "pd.ID_HEADER","=","p.ID")
                            ->where("JOB_ORDER_ID", $id)
                            ->sum(DB::raw("IF(DK = 'D', NOMINAL, IF(DK = 'K', -NOMINAL,0))"));
            $header = $header->first();
            $header->TOTAL = $total;
            $header->TOTAL_BIAYA = $totalBiaya;
            $header->SALDO = $total - $totalBiaya;
            $header->TGL_JOB = $header->TGL_JOB == "" ? "" : Date("d-m-Y", strtotime($header->TGL_JOB));
        }
        if ($includeDetail){
            $detail = DB::table(DB::raw("job_order_detail jd"))
                        ->select("jd.ID", "jd.INV_BILLING","jd.TGL_INV_BILLING","NOMINAL","PPN",
                                 DB::raw("GROUP_CONCAT(CONCAT(f.ID,'=',f.FILENAME)) AS files")
                          )
                        ->leftJoin(DB::raw("tbl_files f"), function($join){
                            $join->on("f.ID_HEADER","=", "jd.ID");
                        })
                        ->groupBy("jd.ID", "jd.INV_BILLING","jd.TGL_INV_BILLING","NOMINAL","PPN")
                        ->where("jd.ID_HEADER", $id)->get();
        }
        $header->TGL_DOK = $header->TGL_DOK == "" ? "" : Date("d-m-Y", strtotime($header->TGL_DOK));
        $header->TGL_TIBA = $header->TGL_TIBA == "" ? "" : Date("d-m-Y", strtotime($header->TGL_TIBA));
        $header->TGL_NOPEN = $header->TGL_NOPEN == "" ? "" : Date("d-m-Y", strtotime($header->TGL_NOPEN));
        $header->TGL_SPPB = $header->TGL_SPPB == "" ? "" : Date("d-m-Y", strtotime($header->TGL_SPPB));
        return Array("header" => $header,
                     "detail" => isset($detail) ? $detail : []);
    }
    public static function saveTransaksi($action, $header, $detail){

        $arrHeader = Array("CUSTOMER" => isset($header["customer"]) && trim($header["customer"]) != "" ? $header["customer"] : NULL,
                           "JENIS_DOK" => trim($header["jenisdokumen"]) == "" ? NULL : $header["jenisdokumen"],
						               "TGL_JOB" => trim($header["tgljob"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgljob"])),
                           "NOPEN" => $header["nopen"],"NOAJU" => $header["noaju"],
                           "TGL_NOPEN" => trim($header["tglnopen"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglnopen"])),
                           "TGL_SPPB" => trim($header["tglsppb"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglsppb"])),
                           "TGL_TIBA" => trim($header["tgltiba"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgltiba"])),
                           "TGL_DOK" => trim($header["tgldokumen"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgldokumen"])),
                           "NO_DOK" => $header["nodok"],
                           "JML_KONTAINER" => $header["jmlkontainer"]
                          );

        if ($action == "insert"){
            $number = 0;
            $maxNumber = Transaksi::select("JOB_ORDER")->orderBy("JOB_ORDER", "DESC")->take(1);
            if ($maxNumber->exists()){
                $number = intval(str_replace("JO","", $maxNumber->first()->JOB_ORDER));
            }
            $number += 1;
            $arrHeader["JOB_ORDER"] = "JO" .str_pad($number, 6, "0", STR_PAD_LEFT);
            $idtransaksi = Transaksi::insertGetId($arrHeader);

            $arrDetail = Array();
            if (is_array($detail) && count($detail) > 0){
                foreach ($detail as $item){
                    $arrDetail[] = Array("ID_HEADER" => $idtransaksi,
                                          "INV_BILLING" => trim($item["INV_BILLING"]),
                                          "TGL_INV_BILLING" => trim($item["TGL_INV_BILLING"]) != "" ? Date("Y-m-d", strtotime($item["TGL_INV_BILLING"])) : NULL,
                                          "NOMINAL" => $item["NOMINAL"] != "" ? str_replace(",","",$item["NOMINAL"]) : 0,
                                          "PPN" => $item["PPN"] != "" ? str_replace(",","",$item["PPN"]) : 0
                                );
                }
            }
            if (count($arrDetail) > 0){
                DB::table("job_order_detail")
                    ->insert($arrDetail);
            }
            return response()->json(["id" => $idtransaksi]);
        }
        else if ($action == "update"){
            $idtransaksi = $header["idtransaksi"];
            $data = Transaksi::where("ID", $idtransaksi)
                              ->update($arrHeader);

            if (is_array($detail) && count($detail) > 0){
                foreach ($detail as $item){
                    $arrDetail = Array("ID_HEADER" => $idtransaksi,
                                        "INV_BILLING" => trim($item["INV_BILLING"]),
                                        "TGL_INV_BILLING" => trim($item["TGL_INV_BILLING"]) != "" ? Date("Y-m-d", strtotime($item["TGL_INV_BILLING"])) : NULL,
                                        "NOMINAL" => $item["NOMINAL"] != "" ? str_replace(",","",$item["NOMINAL"]) : 0,
                                        "PPN" => $item["PPN"] != "" ? str_replace(",","",$item["PPN"]) : 0
                              );
                    if (!isset($item["ID"])
                        || $item["ID"] == ""){
                        $arrDetail["ID_HEADER"] = $idtransaksi;
                        $insertDetail[] = $arrDetail;
                    }
                    else {
                        DB::table("job_order_detail")
                            ->where("ID", $item["ID"])
                            ->update($arrDetail);
                    }
                }
            }
            if (isset($insertDetail) && count($insertDetail) > 0){
                DB::table("job_order_detail")
                    ->insert($insertDetail);
            }
            if ($header["deletedetail"] != ""){
                $iddelete = explode(";", $header["deletedetail"]);
                foreach ($iddelete as $iddel){
                    if ($iddel != ""){
                        DB::table("job_order_detail")
                            ->where("ID", $iddel)
                            ->delete();
                    }
                }
            }
            return response()->json(["id" => $idtransaksi]);
        }
    }
    public static function deleteTransaksi($id)
    {
        Transaksi::where("ID", $id)->delete();
        DB::table("job_order_detail")
            ->where("ID_HEADER", $id)
            ->delete();
    }
    public static function deletePembayaran($id)
    {
        Pembayaran::where("ID", $id)->delete();
        DB::table("pembayaran_detail")
            ->where("ID_HEADER", $id)
            ->delete();
    }
    public static function browse($customer, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {
        $array1 = Array("No Job" => "JOB_ORDER", "No Dok" => "NO_DOK");
        $array2 = Array("Tanggal Tiba" => "TGL_TIBA",
                        "Tanggal Job" => "TGL_JOB");
        $where = " 1 = 1";
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
        if (trim($customer) != ""){
            $where .= " AND CUSTOMER = '" .$customer ."'";
        }

        $data = DB::table(DB::raw("job_order h"))
                    ->selectRaw("h.ID, NOAJU, NOPEN, JOB_ORDER, NO_DOK,"
                            ."i.nama_customer AS NAMACUSTOMER, "
                            ."(SELECT IFNULL(SUM(NOMINAL+PPN),0) FROM job_order_detail jd "
                            ."WHERE jd.ID_HEADER = h.ID) AS TOTAL_BILLING, "
                            ."(SELECT IFNULL(SUM(NOMINAL),0) FROM pembayaran_detail pd "
                            ."INNER JOIN pembayaran p ON pd.ID_HEADER = p.ID "
                            ."WHERE pd.JOB_ORDER_ID = h.ID AND DK = 'D') AS TOTAL_BIAYA, "
                            ."(SELECT IFNULL(SUM(NOMINAL),0) FROM pembayaran_detail pd "
                            ."INNER JOIN pembayaran p ON pd.ID_HEADER = p.ID "
                            ."WHERE pd.JOB_ORDER_ID = h.ID AND DK = 'K') AS TOTAL_PAYMENT,"
                            ."IFNULL(DATE_FORMAT(TGL_TIBA, '%d-%m-%Y'),'') AS TGL_TIBA,"
                            ."IFNULL(DATE_FORMAT(TGL_SPPB, '%d-%m-%Y'),'') AS TGL_SPPB,"
                            ."IFNULL(DATE_FORMAT(TGL_JOB, '%d-%m-%Y'), '') AS TGL_JOB,"
                            ."IFNULL(DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y'),'') AS TGL_NOPEN")
                    ->leftJoin(DB::raw("tb_customer i"), "h.CUSTOMER", "=", "i.id_customer")
                    ->orderBy("JOB_ORDER");
        if (trim($where) != ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function arusKas($customer, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {
        $array1 = Array("No Job" => "JOB_ORDER", "No Dok" => "NO_DOK");
        $array2 = Array("Tanggal Transaksi" => "TANGGAL",
                        "Tanggal Job" => "TGL_JOB");
        $where = " 1 = 1";
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
        if (trim($customer) != ""){
            $where .= " AND CUSTOMER = '" .$customer ."'";
        }

        $data = DB::table(DB::raw("pembayaran_detail d"))
                    ->selectRaw("p.ID, JOB_ORDER, NO_DOK, t.URAIAN AS TRANSAKSI, DK, NOMINAL,"
                            ."IFNULL(DATE_FORMAT(TANGGAL, '%d-%m-%Y'),'') AS TANGGAL,"
                            ."IFNULL(DATE_FORMAT(TGL_JOB, '%d-%m-%Y'), '') AS TGL_JOB")
                    ->join(DB::raw("pembayaran p"), "p.ID","=","d.ID_HEADER")
                    ->join(DB::raw("job_order h"), "h.ID", "=", "d.JOB_ORDER_ID")
                    ->join(DB::raw("ref_kode_transaksi t"), "t.KODETRANSAKSI_ID", "=", "d.KODE_TRANSAKSI")
                    ->join(DB::raw("tb_customer i"), "h.CUSTOMER", "=", "i.id_customer")
                    ->orderBy("TANGGAL");
        if (trim($where) != ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function getPembayaran($id = "")
    {
        if ($id == ""){
            $header = new Pembayaran;
            $detail = [];
        }
        else {
            $header = DB::table(DB::raw("pembayaran h"))
                        ->selectRaw("h.*, (SELECT IFNULL(SUM(NOMINAL),0) FROM pembayaran_detail d "
                                   ."WHERE d.ID_HEADER = h.ID AND DK = 'D') AS TOTAL_DEBET,"
                                   ."(SELECT IFNULL(SUM(NOMINAL),0) FROM pembayaran_detail d "
                                   ."WHERE d.ID_HEADER = h.ID AND DK = 'K') AS TOTAL_KREDIT")
                        ->leftJoin(DB::raw("rekening rek"), "h.REKENING_ID","=", "rek.REKENING_ID")
                        ->where("id", $id);
            if ($header->exists()){
                $header = $header->first();
            }
            else {
                return false;
            }
            $detail = DB::table(DB::raw("pembayaran_detail db"))
                        ->selectRaw("db.*, r.URAIAN as TRANSAKSI, h.JOB_ORDER, h.NO_DOK")
                        ->join(DB::raw("job_order h"), "h.ID", "=", "db.JOB_ORDER_ID")
                        ->leftJoin(DB::raw("ref_kode_transaksi r"), "db.KODE_TRANSAKSI", "=", "r.KODETRANSAKSI_ID")
                        ->where("db.ID_HEADER", $id)
                        ->get();
        }
        if ($header){
            $header->TANGGAL = $header->TANGGAL == "" ? "" : Date("d-m-Y", strtotime($header->TANGGAL));
        }
        return Array("header" => $header, "detail" => $detail);
    }
    public static function savePembayaran($header, $detail)
    {
        $arrHeader = Array("TANGGAL" => trim($header["tanggal"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tanggal"])),
                           "REKENING_ID" => $header["rekening"]
                         );

        if (trim($header["idtransaksi"]) == ""){
            $idtransaksi = DB::table("pembayaran")->insertGetId($arrHeader);
        }
        else {
            $idtransaksi = $header["idtransaksi"];
            DB::table("pembayaran")->where("ID", $idtransaksi)->update($arrHeader);
            DB::table("pembayaran_detail")->where("ID_HEADER", $idtransaksi)->delete();
        }
        $arrDetail = Array();
        if (is_array($detail) && count($detail) > 0){
            foreach ($detail as $item){
                $arrDetail[] = Array("ID_HEADER" => $idtransaksi,
                                    "JOB_ORDER_ID" => $item["JOB_ORDER_ID"],
                                    "KODE_TRANSAKSI" => $item["KODE_TRANSAKSI"],
                                    "NOMINAL" => $item["NOMINAL"] != "" ? str_replace(",","",$item["NOMINAL"]) : 0,
                                    "DK" => $item["DK"]
                                    );
            }
            DB::table("pembayaran_detail")->insert($arrDetail);
        }
    }
    public static function getFiles($id, $type = 0)
    {
        $dtFiles = DB::table("tbl_files")
                     ->selectRaw("tbl_files.*, jenisfile.JENIS")
                     ->leftJoin("jenisfile", "tbl_files.JENISFILE_ID", "=", "jenisfile.ID")
                     ->where("ID_HEADER", $id)
                     ->where("AKTIF", 'Y')
                     ->where("TYPE", $type);
        return $dtFiles->get();
    }
    public static function getMaxFileId($prefix)
    {
        $data = DB::table("tbl_files")
                    ->selectRaw("MAX(ID) as MAX")
                    ->whereRaw("ID LIKE '$prefix%'");
        if ($data->count() > 0){
            return $data->first()->MAX;
        }
        else {
            return false;
        }
    }
    public static function saveFile($realname, $extension, $type = 0)
    {
        $timestamp = Date("YmdHis");
        $id = Transaksi::getMaxFileId($timestamp);
        if ($id != false){
            $max = str_pad(intval(substr($id,14)) + 1,3,"0",STR_PAD_LEFT);
        }
        else {
            $max = '001';
        }
        $id = $timestamp .$max;
        $array = ["ID" => $id, "FILENAME" => $id ."." .$extension, "FILEREALNAME" => $realname, "TYPE" => $type];
        DB::table("tbl_files")->insert($array);
        return $id;
    }
    public static function deleteFile($id)
    {
        $dtFile = DB::table("tbl_files")->select("SELECT FILENAME")
                    ->where("ID", $id);
        if ($dtFile->count() > 0){
            $filename = storage_path() ."/uploads/" .$dtFile->first()->FILENAME;
            unlink($filename);
            DB::table("tbl_files")->where("ID", $id)->delete();
        }
    }
}
