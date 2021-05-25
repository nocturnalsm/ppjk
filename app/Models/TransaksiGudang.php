<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\JenisBarang;
use App\Models\JenisDokumen;
use App\Models\JenisKemasan;
use App\Models\Satuan;
use App\Models\PelabuhanMuat;
use App\Models\Kantor;
use App\Models\Penerima;
use App\Models\Importir;
use App\Models\Produk;
use App\Models\Rate;
use App\Models\Bank;
use App\Models\Rekening;
use App\Models\Pembeli;
use App\Models\Quota;
use App\Models\RealisasiQuota;
use App\Models\Pembayaran;

class TransaksiGudang extends Model
{
    protected $table  = 'tbl_penarikan_header';
    protected $primaryKey = 'ID';
    protected $guarded = ['ID'];
    public $timestamps = false;


    public static function getKodeBarang($value)
    {
        $data = DB::table(DB::raw("tbl_detail_barang db"))
                    ->select("db.ID", "p.kode", "dk.HARGAJUAL")
                    ->join(DB::raw("tbl_konversi dk"), "db.ID", "=", "dk.ID_HEADER")
                    ->join(DB::raw("produk p"), "p.id", "=", "dk.PRODUK_ID")
                    ->where("db.KODEBARANG", $value);
        if ($data->count() > 0){
            return $data->first();
        }
        else {
            return false;
        }
    }
    public static function getTransaksi($id, $includeKontainer = true, $includeDetail = true)
    {
        if ($id == ""){
            $header = new TransaksiGudang;
        }
        else {
            $header = TransaksiGudang::select("tbl_penarikan_header.*")
                            ->where("ID", $id);
            if (!$header->exists()){
                return false;
            }
            $header = $header->first();
        }
        if ($includeKontainer){
            $kontainer = DB::table("tbl_penarikan_kontainer")
                            ->join("ref_ukuran_kontainer", "tbl_penarikan_kontainer.UKURAN_KONTAINER","=", "ref_ukuran_kontainer.KODE")
                            ->where("ID_HEADER", $id)->get();
        }
        if ($includeDetail){
            $detail = DB::table("tbl_detail_barang")
                            ->select("tbl_detail_barang.*",
                                      DB::raw("ref_jenis_kemasan.URAIAN AS NAMAJENISKEMASAN"),
                                      DB::raw("satuan.satuan AS NAMASATUAN")
                                    )
                            ->leftJoin("ref_jenis_kemasan", "ref_jenis_kemasan.JENISKEMASAN_ID","=","tbl_detail_barang.JENISKEMASAN")
                            ->leftJoin("satuan", "satuan.id","=","tbl_detail_barang.SATUAN_ID")
                            ->where("ID_HEADER", $id)->get();
        }
        $header->TGL_NOPEN = $header->TGL_NOPEN == "" ? "" : Date("d-m-Y", strtotime($header->TGL_NOPEN));
        $header->TGL_SPPB = $header->TGL_SPPB == "" ? "" : Date("d-m-Y", strtotime($header->TGL_SPPB));

        return Array("header" => $header, "kontainer" => isset($kontainer) ? $kontainer : [],
                     "detail" => isset($detail) ? $detail : []);
    }
    public static function saveTransaksi($action, $header, $kontainer, $detail){
        $check = TransaksiGudang::select("NO_INV");

        $check = $check->where(function($q) use ($header){
            if (trim($header["noinv"]) != ""){
                $q->orWhere("NO_INV", $header["noinv"]);
            }
            if (trim($header["nopen"]) != ""){
                $q->orWhere("NOPEN", $header["nopen"]);
            }
        });
        if ($action == "update"){
            $check->where("ID", "<>", $header["idtransaksi"]);
        }
        if ($check->count() > 0){
            throw new \Exception("Nomer Invoice / Nopen sudah ada");
        }

        $arrHeader = Array("CUSTOMER" => isset($header["customer"]) && trim($header["customer"]) != "" ? $header["customer"] : NULL,
                           "IMPORTIR" => trim($header["importir"]) == "" ? NULL : $header["importir"],
                           "NO_INV" => $header["noinv"], "NOAJU" => $header["noaju"],
						               "TGL_NOPEN" => trim($header["tglentri"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglentri"])),
                           "NOPEN" => $header["nopen"],"NOAJU" => $header["noaju"],
                           "TGL_SPPB" => trim($header["tglsppb"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglsppb"])),
                           "NDPBM" => str_replace(",","",$header["kurs"]),
                           "KANTOR_ID" => $header["kantor"]
                          );

        if ($action == "insert"){
            $arrHeader["USERGUDANG"] = 'Y';
            $idtransaksi = TransaksiGudang::insertGetId($arrHeader);
            $arrKontainer = Array();
            if (is_array($kontainer) && count($kontainer) > 0){
                foreach ($kontainer as $item){
                    $arrKontainer[] = Array("ID_HEADER" => $idtransaksi,
                                            "NOMOR_KONTAINER" => $item["NOMOR_KONTAINER"],
                                            "UKURAN_KONTAINER" => $item["UKURAN_KONTAINER"]);
                }
            }
            if (count($arrKontainer) > 0){
                DB::table("tbl_penarikan_kontainer")
                    ->insert($arrKontainer);
            }
            $arrDetail = Array();
            if (is_array($detail) && count($detail) > 0){
                foreach ($detail as $item){
                    $arrDetail[] = Array("ID_HEADER" => $idtransaksi,
                                          "KODEBARANG" => trim($item["KODEBARANG"]),
                                          "URAIAN" => trim($item["URAIAN"]),
                                          "JENISKEMASAN" => $item["JENISKEMASAN"] != "" ? $item["JENISKEMASAN"] : 0,
                                          "SATUAN_ID" => $item["SATUAN_ID"] != "" ? $item["SATUAN_ID"] : null,
                                          "JMLKEMASAN" => $item["JMLKEMASAN"] != "" ? str_replace(",","",$item["JMLKEMASAN"]) : 0,
                                          "JMLSATHARGA" => $item["JMLSATHARGA"] != "" ? str_replace(",","",$item["JMLSATHARGA"]) : 0,
                                          "HARGA" => $item["HARGA"] != "" ? str_replace(",","",$item["HARGA"]) : 0,
                                          "CIF" => $item["CIF"] != "" ? str_replace(",","",$item["CIF"]) : 0
                                );
                }
            }
            if (count($arrDetail) > 0){
                DB::table("tbl_detail_barang")
                    ->insert($arrDetail);
            }
            return response()->json(["id" => $idtransaksi]);
        }
        else if ($action == "update"){
            $idtransaksi = $header["idtransaksi"];
            $data = Transaksi::where("ID", $idtransaksi)
                              ->update($arrHeader);
            $arrKontainer = Array();
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
                        DB::table("tbl_penarikan_kontainer")
                            ->where("ID", $item["ID"])
                            ->update($editKontainer);
                    }
                }
            }
            if (count($arrKontainer) > 0){
                DB::table("tbl_penarikan_kontainer")
                    ->insert($arrKontainer);
            }
            if ($header["deletekontainer"] != ""){
                $iddelete = explode(";", $header["deletekontainer"]);
                foreach ($iddelete as $iddel){
                    if ($iddel != ""){
                        DB::table("tbl_penarikan_kontainer")
                            ->where("ID", $iddel)
                            ->delete();
                    }
                }
            }
            $arrDetail = Array();

            if (is_array($detail) && count($detail) > 0){
                foreach ($detail as $item){
                    $arrDetail = Array("KODEBARANG" => trim($item["KODEBARANG"]),
                                        "URAIAN" => trim($item["URAIAN"]),
                                        "JENISKEMASAN" => $item["JENISKEMASAN"] != "" ? $item["JENISKEMASAN"] : 0,
                                        "SATUAN_ID" => $item["SATUAN_ID"] != "" ? $item["SATUAN_ID"] : null,
                                        "JMLKEMASAN" => $item["JMLKEMASAN"] != "" ? str_replace(",","",$item["JMLKEMASAN"]) : 0,
                                        "JMLSATHARGA" => $item["JMLSATHARGA"] != "" ? str_replace(",","",$item["JMLSATHARGA"]) : 0,
                                        "HARGA" => $item["HARGA"] != "" ? str_replace(",","",$item["HARGA"]) : 0,
                                        "CIF" => $item["CIF"] != "" ? str_replace(",","",$item["CIF"]) : 0
                                      );
                    if (!isset($item["ID"])
                        || $item["ID"] == ""){
                        $arrDetail["ID_HEADER"] = $idtransaksi;
                        $insertDetail[] = $arrDetail;
                    }
                    else {
                        DB::table("tbl_detail_barang")
                            ->where("ID", $item["ID"])
                            ->update($arrDetail);
                    }
                }
            }
            if (isset($insertDetail) && count($insertDetail) > 0){
                DB::table("tbl_detail_barang")
                    ->insert($insertDetail);
            }
            if ($header["deletedetail"] != ""){
                $iddelete = explode(";", $header["deletedetail"]);
                foreach ($iddelete as $iddel){
                    if ($iddel != ""){
                        DB::table("tbl_detail_barang")
                            ->where("ID", $iddel)
                            ->delete();
                    }
                }
            }
            return response()->json(["id" => $idtransaksi]);
        }
    }
    public static function saveTransaksiKonversi($header, $detail){
        $id = $header["idtransaksi"];

        $deletedetail = $header["deletedetail"];
        $deletedetail = trim($deletedetail,";");
        $deleted = str_replace(";", "','", $deletedetail);
        $deleted = "'" .$deleted ."'";

        DB::table("tbl_konversi")
            ->whereRaw("ID IN (" .$deleted .")")
            ->delete();

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
                    DB::table("tbl_konversi")
                        ->where("ID", $item["ID"])
                        ->update($data);
                }
            }
            if (count($arrDetail) > 0){
                DB::table("tbl_konversi")
                    ->insert($arrDetail);
            }
        }
    }

    public static function deleteTransaksi($id)
    {
        TransaksiGudang::where("ID", $id)->delete();
        DB::table("tbl_penarikan_kontainer")
            ->where("ID_HEADER", $id)
            ->delete();
    }
    public static function browse($kantor, $customer, $importir, $kategori1, $dari1,
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
        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("k.KODE AS KODEKANTOR, NO_INV, NO_BL, JUMLAH_KEMASAN, nama_customer AS NAMA,"
                              ."i.nama AS IMPORTIR, NOAJU,"
                              ."DATE_FORMAT(TGL_SPPB, '%d-%m-%Y') AS TGLSPPB,"
                              ."DATE_FORMAT(TGL_TERIMA, '%d-%m-%Y') AS TGLTERIMA,"
                              ."DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,"
                              ."DATE_FORMAT(TGL_KELUAR, '%d-%m-%Y') AS TGLKELUAR,"
                              ."NOPEN, DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN, JUMLAH_KONTAINER")
                    ->join(DB::raw("ref_kantor k"), "h.KANTOR_ID","=","k.KANTOR_ID")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "h.CUSTOMER" ,"=", "c.id_customer")
                    ->leftJoin(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id");
        if (trim($where) !== ""){
            $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function browseBongkar($importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {
        $array1 =  Array("Nopen" => "NOPEN","No Aju" => "NOAJU");

        $array2 = Array("Tanggal Bongkar" => "TGL_BONGKAR", "Tanggal Nopen" => "TGL_NOPEN");
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
        if (trim($importir) != ""){
            $where .= " AND IMPORTIR = '" .$importir ."'";
        }

        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("h.ID, NOAJU, NOPEN,"
                            ."i.nama AS IMPORTIR, "
                            ."(SELECT g.KODE FROM kontainer_masuk km "
                            ."INNER JOIN tbl_penarikan_kontainer k ON km.NO_KONTAINER = k.ID "
                            ."INNER JOIN ref_gudang g ON g.GUDANG_ID = km.GUDANG_ID "
                            ."WHERE k.ID_HEADER = h.ID LIMIT 1) AS NAMAGUDANG,"
                            ."DATE_FORMAT(TGL_BONGKAR, '%d-%m-%Y') AS TGLBONGKAR,"
                            ."IF(HASIL_BONGKAR = 'S', 'Sesuai', IF(HASIL_BONGKAR = 'K', 'Kurang',"
                            ."IF(HASIL_BONGKAR = 'L', 'Lebih',''))) AS HASILBONGKAR,"
                            ."DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN")
                    ->join(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id")
                    ->leftJoin(DB::raw("tbl_header_bongkar b"), "h.ID", "=", "b.ID_HEADER")
                    ->whereExists(function ($query) {
                       $query->select(DB::raw(1))
                             ->from(DB::raw('tbl_penarikan_kontainer k'))
                             ->join(DB::raw("kontainer_masuk km"), "km.NO_KONTAINER","=","k.ID")
                             ->whereRaw("km.TGL_MASUK IS NOT NULL")
                             ->whereColumn('h.ID', 'k.ID_HEADER');
                    });
        if (trim($where) != ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
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
    public static function browsebarang($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2, $detail = false)
    {
        $array1 =  Array("No Inv" => "NO_INV", "Nopen" => "NOPEN","No BL" => "NO_BL",
                         "No Kontainer" => "NOMOR_KONTAINER","Hasil Periksa" => "HASIL_PERIKSA","No Aju" => "NOAJU");

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
        $headerFields = ",  DATE_FORMAT(h.TGL_INV, '%d-%m-%Y') AS TGLINV, PENGIRIM, NO_LS, DATE_FORMAT(TGL_LS, '%d-%m-%Y') AS TGLLS,
                            BM, BMT, PPN, PPH, TOTAL, PPH_BEBAS, r.MATAUANG, jd.kode AS JENISDOKUMEN, h.CIF AS NILAI,
                            DATE_FORMAT(TGL_BL, '%d-%m-%Y') AS TGLBL, NO_FORM, DATE_FORMAT(TGL_FORM, '%d-%m-%Y') AS TGLFORM,
                            JENIS_DOKUMEN, DATE_FORMAT(h.TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,
                            DATE_FORMAT(TGL_AJU, '%d-%m-%Y') AS TGLAJU, DATE_FORMAT(TGL_TERIMA, '%d-%m-%Y') AS TGLTERIMA,
                            NDPBM, t.KODEBARANG, t.JMLKEMASAN, t.JMLSATHARGA, t.HARGA, t.CIF, t.URAIAN,
                            t.SATUAN_ID, t.JENISKEMASAN, t.NOSPTNP, DATE_FORMAT(TGLSPTNP, '%d-%m-%Y') AS TGLSPTNP, sk.satuan as SATUANKEMASAN, st.satuan";

        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("h.ID, KANTOR_ID, i.importir_id, c.id_customer,"
                              ."NOPEN, i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER, NOAJU,"
                              ."DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,"
                              ."DATE_FORMAT(TGL_DOK_TRM, '%d-%m-%Y') AS TGLDOKTRM,"
                              ."DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,"
                              ."DATE_FORMAT(TGL_SPPB, '%d-%m-%Y') AS TGLSPPB,"
                              ."DATE_FORMAT(TGL_KELUAR, '%d-%m-%Y') AS TGLKELUAR,"
                              ."NO_BL, NO_INV, JUMLAH_KEMASAN,"
                              ."IF(JALUR = '' OR JALUR IS NULL, '',"
                              ."CASE  WHEN JALUR = 'K' THEN 'Kuning' "
                              ."WHEN JALUR = 'M' THEN 'Merah' "
                              ."WHEN JALUR = 'H' THEN 'Hijau' "
                              ."END) AS JALURDOK,"
                              ."(SELECT GROUP_CONCAT(NOMOR_KONTAINER) "
                              ."FROM tbl_penarikan_kontainer d where d.ID_HEADER = h.ID)"
                              ."AS NOMOR_KONTAINER "
                            .($detail ? $headerFields : ""))
                    ->leftJoin(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "h.CUSTOMER", "=", "c.id_customer");

        if ($detail){
            $data = $data
                    ->leftJoin(DB::raw("ref_matauang r"), "h.CURR", "=", "r.MATAUANG_ID")
                    ->leftJoin(DB::raw("tbl_detail_barang t"), "h.ID", "=", "t.ID_HEADER")
                    ->leftJoin(DB::raw("ref_jenis_dokumen jd"), "h.JENIS_DOKUMEN", "=", "jd.JENISDOKUMEN_ID")
                    ->leftJoin(DB::raw("satuan sk"), "t.JENISKEMASAN", "=", "sk.id")
                    ->leftJoin(DB::raw("satuan st"), "t.SATUAN_ID", "=", "st.id");
        }
        if (trim($where) != ""){
            $data->whereRaw($where);
        }
        //print($data->printquery());die();
        return $data->get();
    }
    public static function getTransaksiBarang($id)
    {
        $header = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("h.ID, h.CUSTOMER, c.nama_customer as NAMACUSTOMER,"
                                ."h.IMPORTIR, h.NO_INV, h.TGL_INV, PENGIRIM, NO_LS, TGL_LS,"
                                ."BM, BMT, PPN, PPH, TOTAL, PPH_BEBAS, JENIS_KEMASAN, CONSIGNEE,"
                                ."NO_BL, TGL_BL, NO_FORM, TGL_FORM, CIF, TGL_KONVERSI,"
                                ."TGL_SPPB, TGL_KELUAR,JENIS_DOKUMEN,JUMLAH_KEMASAN,"
                                ."h.NOPEN, h.TGL_NOPEN, h.NOAJU, TGL_AJU, TGL_TERIMA,"
                                ."NDPBM, h.CURR, JALUR")
                    ->leftJoin(DB::raw("importir i"), "h.IMPORTIR", "=", "i.IMPORTIR_ID")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "h.CUSTOMER", "=", "c.id_customer")
                    ->where("h.ID", $id)->first();
        $cif = $header->CIF;
        $detail = DB::table(DB::raw("tbl_detail_barang db"))
                    ->selectRaw("db.*, s.satuan AS NAMASATUAN, db.CIF,"
                                ."db.CIF/db.JMLSATHARGA as HARGASATUAN,"
                                ."jk.URAIAN AS NAMAJENISKEMASAN")
                    ->join(DB::raw("tbl_penarikan_header h"), "h.ID", "=", "db.ID_HEADER")
                    ->leftJoin(DB::raw("satuan s"), "db.SATUAN_ID", "=", "s.id")
                    ->leftJoin(DB::raw("ref_jenis_kemasan jk"), "db.JENISKEMASAN", "=", "jk.JENISKEMASAN_ID")
                    ->where("h.ID", $id)->get();
        foreach ($detail as $key=>$det){
            $detail[$key]->files = DB::table("tbl_files")
                                      ->selectRaw("ID, FILEREALNAME")
                                      ->where("AKTIF", 'Y')
                                      ->where("ID_HEADER", $det->ID)
                                      ->where("TYPE", 1)
                                      ->get();
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
    public static function getDataPengeluaran($id)
	  {
        $data = DB::table("tbl_pengeluaran")
                    ->selectRaw("ID,ID_HEADER, DATE_FORMAT(TGL_MUAT,'%d-%m-%Y') AS TGL_MUAT,"
                               ."NO_POL, NO_SJ, DRIVER, REMARKS,JMLKEMASAN")
                    ->where("ID_HEADER", $id);

		    return $data->get();
    }
    public static function saveTransaksiPengeluaran($header, $detail)
    {
        DB::table("tbl_pengeluaran")->where("ID_HEADER", $id)->delete();
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
            DB::table("tbl_pengeluaran")->insert($arrDetail);
        }
    }
    public static function getDetailStokBarang($id, $kategori, $dari, $sampai)
    {
        $dari = Date("Y-m-d", strtotime($dari));
        $sampai = Date("Y-m-d", strtotime($sampai));
        $data = DB::table(DB::raw("detail_do do"))
                    ->selectRaw("tb.KODEBARANG, dorder.ID, NO_DO, DATE_FORMAT(TGL_DO,'%d-%m-%Y') AS TGL_DO,"
                            ."NO_INV_JUAL, DATE_FORMAT(TGL_KELUAR,'%d-%m-%Y') AS TGL_KELUAR,"
                            ."IFNULL(JMLKMSKELUAR,0) As kemasankeluar,"
                            ."IFNULL(JMLSATHARGAKELUAR,0) as satuankeluar,"
                            ."'' as satuankemasan, s.satuan as satuan")
                    ->join(DB::raw("tbl_detail_barang tb"),"do.KODEBARANG", "=", "tb.ID")
                    ->join(DB::raw("deliveryorder dorder"), "do.ID_HEADER", "=", "dorder.ID")
                    ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=","s.id")
                    ->where("do.KODEBARANG", $id)
                    ->whereBetween("TGL_KELUAR", [$dari, $sampai]);
        return $data->get();
    }
    public static function getTransaksiQuota($id, $includeDetail = true)
    {
        $header = Quota::selectRaw("ID, CONSIGNEE, NO_PI, TGL_PI, TGL_BERLAKU, STATUS")
                        ->where("ID", $id);

        if ($header->count() > 0){
            $header = $header->first();
            $header->TGL_PI = $header->TGL_PI == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PI));
            $header->TGL_BERLAKU = $header->TGL_BERLAKU == "" ? "" : Date("d-m-Y", strtotime($header->TGL_BERLAKU));

            $return["header"] = $header;
            if ($includeDetail){
                $detail = DB::table(DB::raw("tbl_detail_quota d"))
                            ->selectRaw("d.NO, d.KODE_HS, d.SALDO_AWAL, d.SATUAN_ID, s.satuan")
                            ->join(DB::raw("satuan s"), "d.SATUAN_ID", "=", "s.id")
                            ->where("d.ID_HEADER", $id)
                            ->get();
                $return["detail"] = $detail;
            }
            return $return;
        }
        else {
            return false;
        }

        return $header;
    }
    public static function saveTransaksiQuota($action, $header, $detail, $files)
    {
        $arrHeader = Array("TGL_PI" => trim($header["tglpi"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tglpi"])),
                           "NO_PI" => trim($header["nopi"]),  "STATUS" => $header["status"],
                           "TGL_BERLAKU" => trim($header["tglberlaku"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tglberlaku"])),
                           "CONSIGNEE" => $header["consignee"]);
        $oldId = "";
        $check = Quota::select("ID")->where("STATUS",'Y')
                      ->where("CONSIGNEE", $header["consignee"]);
        if ($check->count() > 0){
            $oldId = $check->first()->ID;
        }
        if ($action == "insert"){
            $arrHeader["STATUS"] = "Y";
            $data = Quota::create($arrHeader);
            $idtransaksi = $data->ID;
            if ($header["status"] == "Y"){
                Quota::where("ID", $oldId)
                     ->update(Array("STATUS" => 'T', 'TGL_EXPIRE' => Date("Y-m-d")));
            }
        }
        else if ($action == "update"){
            $idtransaksi = $header["idtransaksi"];
            Quota::where("ID", $idtransaksi)->update($arrHeader);
            DB::table("tbl_detail_quota")->where("ID_HEADER", $idtransaksi)->delete();
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
            DB::table("tbl_detail_quota")->insert($arrDetail);
        }
        $oldFiles = Array();
        if (!is_array($files)){
            $fileIds = Array();
        }
        else {
            $fileIds = array_map(function($elem){
                return $elem["id"];
            }, $files);
        }
        $dtFiles = DB::table("tbl_files")
                     ->selectRaw("GROUP_CONCAT(ID) AS STR")
                     ->where("TYPE", 2)
                     ->where("ID_HEADER", $idtransaksi);
        if ($dtFiles->count() > 0){
            $oldFiles = explode(",",$dtFiles->first()->STR);
        }
        $diff = array_diff($fileIds, $oldFiles);
        if (count($diff) > 0){
            $strFile = "('" .implode("','", $diff) ."')";
            DB::table("tbl_files")
                ->whereRaw("ID IN " .$strFile)
                ->update(["ID_HEADER" => $idtransaksi, "AKTIF" => "Y"]);
        }
        $diff = array_diff($oldFiles, $fileIds);
        if (count($diff) > 0){
            $strFile = "('" .implode("','", $diff) ."')";
            DB::table("tbl_files")->whereRaw("ID IN " .$strFile)->delete();
        }
    }
    public static function getRealisasiQuota($id)
    {
        $data = RealisasiQuota::select("tbl_realisasi_quota.ID", "ID_HEADER", "KODE_HS", "BOOKING", "REALISASI",
                                        "SATUAN_ID", "satuan.satuan")
                              ->join("satuan","tbl_realisasi_quota.SATUAN_ID", "=", "satuan.id")
                              ->where("ID_HEADER", $id);
        return $data->get();
    }
    public static function getPI($consignee = "")
    {
        $data = Quota::select("ID", "NO_PI", DB::raw("DATE_FORMAT(TGL_PI,'%d-%m-%Y') AS TGLPI"))
                      ->where("STATUS", "Y");
        if ($consignee != ""){
            $data = $data->where("CONSIGNEE", $consignee);
        }
        if ($data->count() > 0){
            if ($consignee == ""){
                return new Quota;
            }
            else {
                return $data->first();
            }
        }
        else {
            return false;
        }
    }
    public static function saveKontainerMasuk($input)
    {
        if (isset($input['idkontainer'])){
            $id = DB::table("kontainer_masuk")
                    ->updateOrInsert(
                        ["NO_KONTAINER" => $input['idkontainer']],
                        ["NOPOL" => $input['nopol'],
                         "GUDANG_ID" => $input['gudang'],
                         "TGL_MASUK" => Date("Y-m-d", strtotime($input['tglmasuk']))]
                      );
            return $id;
        }
    }
    public static function getTransaksiBongkar($id)
    {
          $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("h.ID, i.NAMA AS NAMAIMPORTIR, h.NOAJU, NOPEN, TGL_NOPEN, "
                               ."TGL_BONGKAR, b.ID AS IDBONGKAR, b.CATATAN, b.HASIL_BONGKAR, "
                               ."(SELECT g.KODE FROM kontainer_masuk km "
                               ."INNER JOIN tbl_penarikan_kontainer k ON km.NO_KONTAINER = k.ID "
                               ."INNER JOIN ref_gudang g ON g.GUDANG_ID = km.GUDANG_ID "
                               ."WHERE k.ID_HEADER = h.ID) AS NAMAGUDANG"
                               )
                    ->join(DB::raw("importir i"), "i.IMPORTIR_ID","=","h.IMPORTIR")
                    ->leftJoin(DB::raw("tbl_header_bongkar b"), "b.ID_HEADER","=","h.ID")
                    ->where("h.ID", $id)
                    ->whereExists(function ($query) {
                       $query->select(DB::raw(1))
                             ->from(DB::raw('tbl_penarikan_kontainer k'))
                             ->join(DB::raw("kontainer_masuk km"), "km.NO_KONTAINER","=","k.ID")
                             ->whereRaw("km.TGL_MASUK IS NOT NULL")
                             ->whereColumn('h.ID', 'k.ID_HEADER');
                    });

          if ($data->exists()){
              $data = $data->first();
              $data->TGLNOPEN = $data->TGL_NOPEN && $data->TGL_NOPEN != "" ? Date("d-m-Y", strtotime($data->TGL_NOPEN)) : "";
              $data->TGLBONGKAR = $data->TGL_BONGKAR && $data->TGL_BONGKAR != "" ? Date("d-m-Y", strtotime($data->TGL_BONGKAR)) : "";

              $detail = DB::table(DB::raw("tbl_detail_barang br"))
                          ->selectRaw("b.ID, b.ID_HEADER, br.ID AS KODEBARANG, br.KODEBARANG AS KODEBRG,"
                                     ."satuan.satuan, JMLKEMASAN, JMLSATHARGA, JMLKEMASANBONGKAR, JMLSATHARGABONGKAR")
                          ->leftJoin("satuan", "satuan.id","=", "br.SATUAN_ID")
                          ->leftJoin(DB::raw("tbl_detail_bongkar b"), "br.ID", "=","b.KODEBARANG")
                          ->where("br.ID_HEADER", $data->ID);
              $data->detail = $detail->get();
              return $data;
          }
          else {
              return false;
          }
    }
    public static function saveTransaksiBongkar($header, $files)
    {
          if (isset($header["idtransaksi"])){
              DB::table("tbl_header_bongkar")
                ->updateOrInsert(
                    ["ID_HEADER" => $header["idtransaksi"]],
                    ["TGL_BONGKAR" => Date("Y-m-d", strtotime($header["tglbongkar"])),
                     "HASIL_BONGKAR"  => trim($header["hasilbongkar"]),
                     "CATATAN" => trim($header["catatan"])
                    ]
                  );
              $idHeader = DB::table("tbl_header_bongkar")
                      ->where("ID_HEADER", $header["idtransaksi"])
                      ->value("ID");
              $item = 0;
              foreach($header["kodebarang"] as $barang){
                  $kmsbkr = trim($header["kmsbkr"][$item]);
                  $satbkr = trim($header['satbkr'][$item]);
                  DB::table("tbl_detail_bongkar")
                     ->updateOrInsert(
                        ["KODEBARANG" => $barang, "ID_HEADER" => $idHeader],
                        ["JMLKEMASANBONGKAR" => $kmsbkr != "" ? str_replace(",","", $kmsbkr) : 0,
                         "JMLSATHARGABONGKAR" => $satbkr != "" ? str_replace(",","", $satbkr) : 0
                        ]
                     );
                  $item++;
              }
              $oldFiles = Array();
              if (!is_array($files)){
                  $fileIds = Array();
              }
              else {
                  $fileIds = array_map(function($elem){
                      return $elem["id"];
                  }, $files);
              }
              $dtFiles = DB::table("tbl_files")
                            ->selectRaw("GROUP_CONCAT(ID) AS STR")
                            ->where("ID_HEADER", $idHeader);
              if ($dtFiles->count() > 0){
                  $oldFiles = explode(",",$dtFiles->first()->STR);
              }
              $diff = array_diff($fileIds, $oldFiles);
              if (count($diff) > 0){
                  $strFile = "('" .implode("','", $diff) ."')";
                  DB::table("tbl_files")
                      ->whereRaw("ID IN " .$strFile)
                      ->update(["ID_HEADER" => $idHeader, "AKTIF" => "Y"]);
              }
              $diff = array_diff($oldFiles, $fileIds);
              if (count($diff) > 0){
                  $strFile = "('" .implode("','", $diff) ."')";
                  DB::table("tbl_files")->whereRaw("ID IN " .$strFile)
                      ->delete();
              }
          }
    }
    public static function searchNopen($nopen)
    {
        $data = TransaksiGudang::where("NOPEN", "LIKE", "%" .trim($nopen) ."%")
                               ->select("ID");
        if ($data->exists()){
            return $data->first();
        }
        else {
            return false;
        }
    }
    public static function browsePengeluaran($importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {
        $array1 = Array("Nopen" => "NOPEN","No Aju" => "NOAJU");

        $array2 = Array("Tanggal Kirim" => "TGL_KIRIM", "Tanggal Nopen" => "TGL_NOPEN");
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
        if (trim($importir) != ""){
            $where .= " AND IMPORTIR = '" .$importir ."'";
        }

        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("h.ID, NOAJU, NOPEN,"
                            ."i.nama AS IMPORTIR, "
                            ."DATE_FORMAT(TGL_BONGKAR, '%d-%m-%Y') AS TGLBONGKAR,"
                            ."DATE_FORMAT(TGL_KIRIM, '%d-%m-%Y') AS TGLKIRIM,"
                            ."IF(HASIL_BONGKAR = 'S', 'Sesuai', IF(HASIL_BONGKAR = 'K', 'Kurang',"
                            ."IF(HASIL_BONGKAR = 'L', 'Lebih',''))) AS HASILBONGKAR,"
                            ."DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN")
                    ->join(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id")
                    ->leftJoin(DB::raw("tbl_header_bongkar b"), "h.ID", "=", "b.ID_HEADER")
                    ->leftJoin(DB::raw("tbl_header_pengeluaran pu"), "h.ID", "=", "pu.ID_HEADER")
                    ->whereRaw("TGL_BONGKAR IS NOT NULL");
        if (trim($where) != ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function getPengeluaran($id)
    {
          $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("h.ID, i.NAMA AS NAMAIMPORTIR, h.NOAJU, NOPEN, TGL_NOPEN, "
                               ."b.ID AS IDPENGELUARAN, b.CATATAN, b.TGL_KIRIM")
                    ->join(DB::raw("importir i"), "i.IMPORTIR_ID","=","h.IMPORTIR")
                    ->leftJoin(DB::raw("tbl_header_pengeluaran b"), "b.ID_HEADER","=","h.ID")
                    ->where("h.ID", $id);

          if ($data->exists()){
              $data = $data->first();
              $data->TGLNOPEN = $data->TGL_NOPEN && $data->TGL_NOPEN != "" ? Date("d-m-Y", strtotime($data->TGL_NOPEN)) : "";
              $data->TGLKIRIM = $data->TGL_KIRIM && $data->TGL_KIRIM != "" ? Date("d-m-Y", strtotime($data->TGL_KIRIM)) : "";

              $detail = DB::table(DB::raw("tbl_detail_pengeluaran dp"))
                          ->select("NOPOL","SOPIR","JMLROLL","NOSJ",DB::raw("e.NAMA AS NAMAEKSPEDISI"),"JENISTRUK",
                                  "EKSPEDISI","JENIS_TRUK",
                                   DB::raw("DATE_FORMAT(TGL_KELUAR, '%d-%m-%Y') AS TGL_KELUAR"))
                          ->leftJoin(DB::raw("ref_jenis_truk jt"), "jt.JENISTRUK_ID","=","dp.JENISTRUK")
                          ->leftJoin(DB::raw("ekspedisi e"), "e.EKSPEDISI_ID","=","dp.EKSPEDISI")
                          ->where("ID_HEADER", $data->IDPENGELUARAN);
              $data->detail = $detail->get();
              return $data;
          }
          else {
              return false;
          }
    }
    public static function savePengeluaran($header, $detail)
    {
        DB::table("tbl_header_pengeluaran")
          ->updateOrInsert(
              ["ID_HEADER" => $header["idtransaksi"]],
              ["TGL_KIRIM" => Date("Y-m-d", strtotime($header["tglkirim"])),
               "CATATAN" => $header["catatan"]
              ]
            );
        $idHeader = DB::table("tbl_header_pengeluaran")
                ->where("ID_HEADER", $header["idtransaksi"])
                ->value("ID");
        DB::table("tbl_detail_pengeluaran")->where("ID_HEADER", $idHeader)
           ->delete();
        $arrDetail = Array();
        foreach($detail as $item){
            $sopir = trim($item["SOPIR"]);
            $jmlroll = trim($item['JMLROLL']);
            $tglkeluar = trim($item['TGL_KELUAR']);
            $nopol = trim($item["NOPOL"]);
            $nosj = trim($item["NOSJ"]);
            $ekspedisi = isset($item["EKSPEDISI"]) ? $item["EKSPEDISI"] : NULL;
            $jenistruk = isset($item["JENISTRUK"]) ? $item["JENISTRUK"] : NULL;
            $arrDetail[] = Array(
                  "ID_HEADER" => $idHeader, "NOPOL" => $nopol, "SOPIR" => $sopir,
                  "TGL_KELUAR" => Date("Y-m-d", strtotime($tglkeluar)),
                  "NOSJ" => $nosj, "JENISTRUK" => $jenistruk, "EKSPEDISI" => $ekspedisi,
                  "JMLROLL" => $jmlroll != "" ? str_replace(",","", $jmlroll) : 0
               );
        }
        if (count($arrDetail) > 0){
            DB::table("tbl_detail_pengeluaran")
              ->insert($arrDetail);
        }

    }
    public static function konversiStok($customer, $importir, $kodebarang, $kategori2, $dari2, $sampai2, $kategori3, $dari3, $sampai3)
    {
        $arrayKategori =  Array("Tanggal Bongkar" => "TGL_BONGKAR","Tanggal Konversi" => "ks.TGL_KONVERSI");

        $where = "1 = 1";
        if ($kategori2 != ""){
            if (trim($dari2) == "" && trim($sampai2) == ""){
                $where  .=  " AND (" .$arrayKategori[$kategori2] ." IS NULL OR " .$arrayKategori[$kategori2] ." = '')";
            }
            else {
                if (trim($dari2) == ""){
                    $dari2 = "0000-00-00";
                }
                if (trim($sampai2) == ""){
                    $sampai2 = "9999-99-99";
                }
                $where  .=  " AND (" .$arrayKategori[$kategori2] ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
            }
        }
        if ($kategori3 != ""){
            if (trim($dari3) == "" && trim($sampai3) == ""){
                $where  .=  " AND (" .$arrayKategori[$kategori3] ." IS NULL OR " .$arrayKategori[$kategori3] ." = '')";
            }
            else {
                if (trim($dari3) == ""){
                    $dari3 = "0000-00-00";
                }
                if (trim($sampai3) == ""){
                    $sampai3 = "9999-99-99";
                }
                $where  .=  " AND (" .$arrayKategori[$kategori3] ." BETWEEN '" .Date("Y-m-d", strtotime($dari3)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai3)) ."')";
            }
        }
        if (trim($customer) != ""){
            $where .= " AND CUSTOMER = '" .$customer ."'";
        }
        if (trim($importir) != ""){
            $where .= " AND IMPORTIR = '" .$importir ."'";
        }
        if (trim($kodebarang) != ""){
            $where .= " AND db.KODEBARANG LIKE '%" .$kodebarang ."%'";
        }

        $data = DB::table(DB::raw("tbl_detail_bongkar bd"))
                    ->selectRaw("db.ID, db.KODEBARANG, pr.KODE AS KODEPRODUK, ks.PRODUK_ID,"
                            ."c.nama_customer AS NAMACUSTOMER, db.JMLSATHARGA, ks.JMLSATKONVERSI,"
                            ."ks.SATKONVERSI, db.HARGA*h.NDPBM AS RUPIAH, db.SATUAN_ID AS SATHARGA, TAX,"
                            ."(SELECT satuan FROM satuan WHERE id =  db.SATUAN_ID) AS NAMASATHARGA,"
                            ."(SELECT satuan FROM satuan WHERE id =  ks.SATKONVERSI) AS NAMASATKONVERSI,"
                            ."DATE_FORMAT(bh.TGL_BONGKAR, '%d-%m-%Y') AS TGLBONGKAR,"
                            ."DATE_FORMAT(ks.TGL_KONVERSI, '%d-%m-%Y') AS TGLKONVERSI")
                    ->leftJoin(DB::raw("konversistok ks"),"bd.KODEBARANG","=","ks.KODEBARANG")
                    ->leftJoin(DB::raw("produk pr"),"pr.id","=","ks.PRODUK_ID")
                    ->leftJoin(DB::raw("tbl_header_bongkar bh"),"bh.ID","=","bd.ID_HEADER")
                    ->join(DB::raw("tbl_detail_barang db"), "db.ID","=","bd.KODEBARANG")
                    ->join(DB::raw("tbl_penarikan_header h"), "h.ID","=","db.ID_HEADER")
                    ->leftJoin(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "h.CUSTOMER", "=", "c.id_customer");
        if (trim($where) != ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function saveKonversiStok($input)
    {
        if (isset($input["iddetail"])){
            DB::table("konversistok")
            ->updateOrInsert(
                ["KODEBARANG" => $input['iddetail']],
                ["PRODUK_ID" => $input['produk'],
                 "JMLSATKONVERSI" => str_replace(",","", $input["jmlsatkonversi"]),
                 "SATKONVERSI" => $input["satkonversi"],
                 "TAX" => str_replace(",","",$input['tax']),
                 "TGL_KONVERSI" => Date("Y-m-d", strtotime($input['tglkonversi']))]
              );
        }
    }
}
