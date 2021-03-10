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

function nullval($value)
{
    return trim($value) == "" ? NULL : $value;
}

class Transaksi extends Model
{
    protected $table  = 'tbl_penarikan_header';
    protected $primaryKey = 'ID';
    protected $guarded = ['ID'];
    public $timestamps = false;


    public static function getJenisKemasan()
    {
        $data = JenisKemasan::select('*')->orderBy("uraian");
        return $data->get();
    }
    public static function getCustomer($id = "")
    {
        $data = DB::table('plbbandu_app15.tb_customer')
                    ->select("*")
                    ->orderBy("nama_customer");

        if ($id == ""){
            return $data->get();
        }
        else {
            $data = $data->where("id_customer", $id);
            return $data->first();
        }
    }
    public static function getJumlahKontainer()
    {
        $data = DB::table("ref_jumlah_kontainer")
                    ->select("*")
                    ->orderBy(DB::raw('CAST(jumlah AS unsigned)'));
        return $data->get();
    }
    public static function getImportir($id = "", $list = false)
    {
        $data = Importir::select("*")->orderBy("nama");
        $user = auth()->user();
        if ($id == ""){
            if ($user->hasRole('company')){
                $company = $user->hasCompany();
                if ($company){
                  $data->where("IMPORTIR_ID", $company->id);
                }
            }
            return $data->get();
        }
        else {
            $data = $data->where("IMPORTIR_ID", $id);
            return $list ? $data->get() : $data->first();
        }
    }
    public static function getPembeli($id = "", $list = false)
    {
        $data = Pembeli::select("*")->orderBy(DB::raw("NAMA, KODE"));
        if ($id == ""){
            return $data->get();
        }
        else {
            $data = $data->where("ID", $id);
            return $list ? $data->get() : $data->first();
        }
    }
    public static function getJenisBarang()
    {
        $data = JenisBarang::select("*")->orderBy("uraian");
        return $data->get();
    }
    public static function getStatusRevisi()
    {
        $data = DB::table('ref_status_revisi')->select("*");
        return $data->get();
    }
    public static function getPelmuat()
    {
        $data = PelabuhanMuat::select("*")->orderBy("uraian");
        return $data->get();
    }
    public static function getShipper($shipper = "")
    {
        $data = DB::table("plbbandu_app15.tb_pemasok")->select("*")
                    ->orderBy("nama_pemasok");
        if ($shipper == ""){
            return $data->get();
        }
        else {
            $data = $data->where("id_pemasok", $shipper);
            return $data->first();
        }
    }
    public static function getUkuranKontainer()
    {
        $data = DB::table("ref_ukuran_kontainer")->select("*")->orderBy("uraian");
        return $data->get();
    }
    public static function getJenisDokumen()
    {
        $data = JenisDokumen::select("*")->orderBy("uraian");
        return $data->get();
    }
    public static function getJenisFile()
    {
        $data = DB::table("jenisfile")->select("*")->orderBy("JENIS");
        return $data->get();
    }
    public static function getPenerima()
    {
        $data = Penerima::select("*")->orderBy("uraian");
        return $data->get();
    }
    public static function getKantor($id = "")
    {
        $data = Kantor::select("*")->orderBy("uraian");
        if ($id == ""){
            return $data->get();
        }
        else {
            $data = $data->where("KANTOR_ID", $id);
            return $data->first();
        }
    }
    public static function getSatuan()
    {
        $data = Satuan::select("id", "kode", "satuan")->orderBy("satuan");
        return $data->get();
    }
    public static function getMataUang()
    {
        $data = DB::table("ref_matauang")->select("*")->orderBy("MATAUANG");
        return $data->get();
    }
    public static function getTOP()
    {
        $data = DB::table("ref_top")->select("*")->orderBy("TOP_ID");
        return $data->get();
    }
    public static function getBank()
    {
        $data = Bank::orderBy("BANK");
        return $data->get();
    }
    public static function getRekening()
    {
        $data = Rekening::select("rekening.*", "bank.BANK")
                        ->join("bank", "rekening.BANK_ID", "=", "bank.BANK_ID")
                        ->orderBy("NO_REKENING");
        return $data->get();
    }
    public static function getInv($value)
    {
        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("c.nama_customer, h.NO_INV, h.ID, h.NOAJU,"
                              ."h.NOPEN, h.TGL_NOPEN, imp.NAMA as NAMAIMPORTIR,"
                              ."shipper.nama_pemasok AS NAMASHIPPER")
                    ->join(DB::raw("plbbandu_app15.tb_customer c"), "c.id_customer", "=", "h.CUSTOMER")
                    ->join(DB::raw("importir imp"), "imp.IMPORTIR_ID", "=", "h.IMPORTIR")
                    ->join(DB::raw("plbbandu_app15.tb_pemasok shipper"), "shipper.id_pemasok", "=", "h.SHIPPER")
                    ->where("NO_INV", $value);
        if ($data->count() > 0){
            return $data->first();
        }
        else {
            return false;
        }
    }
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
    public static function search($term, $searchtype)
    {
        if (trim($term) == ""){
            return [];
        }
        if ($searchtype == "kontainer"){
            $data = DB::table("tbl_penarikan_kontainer")
                        ->selectRaw("id_header AS id")
                        ->where("NOMOR_KONTAINER", $term);
        }
        else {
            $data = DB::table(DB::raw("tbl_penarikan_header h"))
                        ->selectRaw("h.id as id")
                        ->leftJoin(DB::raw("ref_kantor k"), "h.kantor_id","=", "k.kantor_id")
                        ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "c.id_customer", "=", "h.customer")
                        ->where("NO_BL","LIKE", "%" .$term ."%")
                        ->orWhere("NOAJU", "LIKE", "%" .$term ."%")
                        ->orWhere("NOPEN","LIKE", "%" .$term ."%")
                        ->orWhere("NO_INV", "LIKE", "%" .$term ."%")
                        ->orWhere("c.nama_customer", "LIKE", "%" .$term ."%");

            if (ctype_digit($term)){
                $data = $data->orWhere("h.JUMLAH_KEMASAN", $term);
            }

        }
        if ($data->count() > 0){
            return $data->get();
        }
        else {
            return [];
        }
    }
    public static function searchsptnp($nopen, $tglnopen)
    {
        $data = Transaksi::select("ID")
                        ->where("NOPEN", $nopen)
                        ->where("TGL_NOPEN", Date("Y-m-d", strtotime($tglnopen)));
        if ($data->count() > 0){
            return $data->get();
        }
        else {
            return [];
        }
    }
    public static function getTransaksiSPTNP($id)
    {
        $data = Transaksi::selectRaw("ID, KANTOR_ID, IMPORTIR, NOPEN, TGL_NOPEN, NOAJU,"
                                      ."NO_SPTNP, TGL_SPTNP, BMKITE, PPNBM,"
                                      ."BMTB, BMTTB, PPNTB, PPHTB, DENDA_TB,"
                                      ."IFNULL(BMTB,0) + IFNULL(BMTTB,0) + IFNULL(PPNTB,0) + IFNULL(PPHTB,0) + IFNULL(PPNBM,0) + IFNULL(BMKITE,0) + IFNULL(DENDA_TB,0) AS TOTAL_TB,"
                                      ."JENIS_SPTNP, TGL_JATUH_TEMPO_SPTNP, TGL_LUNAS,"
                                      ."TGL_BRT, HSL_BRT, NO_KEPBRT, TGL_KEPBRT, TGL_JTHTEMPO_BDG,"
                                      ."NO_BDG, TGL_BDG, MAJELIS, SDG01, SDG02, SDG03,"
                                      ."SDG04, SDG05, SDG06, SDG07, HASIL_BDG, NO_KEP_BDG, TGL_KEP_BDG")
                    ->where("ID", $id)
                    ->first();
        $data->TGL_NOPEN = $data->TGL_NOPEN == "" ? "" : Date("d-m-Y", strtotime($data->TGL_NOPEN));
        $data->TGL_LUNAS = $data->TGL_LUNAS == "" ? "" : Date("d-m-Y", strtotime($data->TGL_LUNAS));
        $data->TGL_SPTNP = $data->TGL_SPTNP == "" ? "" : Date("d-m-Y", strtotime($data->TGL_SPTNP));
        $data->TGL_JATUH_TEMPO_SPTNP = $data->TGL_JATUH_TEMPO_SPTNP == "" ? "" : Date("d-m-Y", strtotime($data->TGL_JATUH_TEMPO_SPTNP));
        $data->TGL_BRT = $data->TGL_BRT == "" ? "" : Date("d-m-Y", strtotime($data->TGL_BRT));
        $data->TGL_KEPBRT = $data->TGL_KEPBRT == "" ? "" : Date("d-m-Y", strtotime($data->TGL_KEPBRT));
        $data->TGL_JTHTEMPO_BDG = $data->TGL_JTHTEMPO_BDG == "" ? "" : Date("d-m-Y", strtotime($data->TGL_JTHTEMPO_BDG));
        $data->SDG01 = $data->SDG01 == "" ? "" : Date("d-m-Y", strtotime($data->SDG01));
        $data->SDG02 = $data->SDG02 == "" ? "" : Date("d-m-Y", strtotime($data->SDG02));
        $data->SDG03 = $data->SDG03 == "" ? "" : Date("d-m-Y", strtotime($data->SDG03));
        $data->SDG04 = $data->SDG04 == "" ? "" : Date("d-m-Y", strtotime($data->SDG04));
        $data->SDG05 = $data->SDG05 == "" ? "" : Date("d-m-Y", strtotime($data->SDG05));
        $data->SDG06 = $data->SDG06 == "" ? "" : Date("d-m-Y", strtotime($data->SDG06));
        $data->SDG07 = $data->SDG07 == "" ? "" : Date("d-m-Y", strtotime($data->SDG07));
        $data->TGL_BDG = $data->TGL_BDG == "" ? "" : Date("d-m-Y", strtotime($data->TGL_BDG));
        $data->TGL_KEPBDG = $data->TGL_KEPBDG == "" ? "" : Date("d-m-Y", strtotime($data->TGL_KEPBDG));

        return $data;
    }
    /*
    public static function getTransaksiBC($id)
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
    */
    public static function getTransaksi($id, $includeKontainer = true)
    {
        if ($id == ""){
            $header = new Transaksi;
        }
        else {
            $header = Transaksi::select("tbl_penarikan_header.*",
                                    DB::raw("BMTB+BMTTB+PPNTB+PPHTB+DENDA_TB AS TOTAL_TB"))
                            ->where("ID", $id)->first();
        }
        if ($includeKontainer){
            $kontainer = DB::table("tbl_penarikan_kontainer")
                            ->join("ref_ukuran_kontainer", "tbl_penarikan_kontainer.UKURAN_KONTAINER","=", "ref_ukuran_kontainer.KODE")
                            ->where("ID_HEADER", $id)->get();
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
    public static function getTransaksiBayar($id)
    {
        if ($id == ""){
            $header = new Pembayaran;
            $detail = [];
        }
        else {
            $header = DB::table(DB::raw("tbl_header_bayar h"))
                        ->selectRaw("h.*")
                        ->join(DB::raw("rekening rek"), "h.REKENING_Id","=", "rek.REKENING_ID")
                        ->where("id", $id)->first();
            $detail = DB::table(DB::raw("tbl_detail_bayar db"))
                        ->selectRaw("db.*, r.MATAUANG, KURS, ROUND(KURS*db.NOMINAL) AS RUPIAH,"
                                    ."c.nama_customer AS CUSTOMER, h.NO_INV AS NOINV,"
                                    ."h.NOAJU, h.NOPEN, h.TGL_NOPEN,"
                                    ."imp.NAMA AS IMPORTIR, shipper.nama_pemasok AS SHIPPER")
                        ->join(DB::raw("tbl_penarikan_header h"), "h.ID", "=", "db.NO_INV")
                        ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "c.id_customer", "=", "h.CUSTOMER")
                        ->leftJoin(DB::raw("importir imp"), "imp.IMPORTIR_ID", "=", "h.IMPORTIR")
                        ->leftJoin(DB::raw("plbbandu_app15.tb_pemasok shipper"), "shipper.id_pemasok", "=", "h.SHIPPER")
                        ->leftJoin(DB::raw("ref_matauang r"), "db.CURR", "=", "r.MATAUANG_ID")
                        ->where("db.ID_HEADER", $id)->get();
        }
        if ($header){
            $header->TGL_PENARIKAN = $header->TGL_PENARIKAN == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PENARIKAN));
        }
        return Array("header" => $header, "detail" => $detail);
    }
    public static function getTransaksiDo($id)
    {
        $header = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("h.ID, NO_INV, NO_PO, NO_SC, NO_BL, NO_FORM,JUMLAH_KEMASAN,"
                                ."TGL_INV, TGL_PO, TGL_SC, TGL_BL, TGL_FORM,TGL_JATUH_TEMPO,"
                                ."KAPAL, PEL_MUAT, TGL_BERANGKAT, TGL_TIBA, TGL_DOK_TRM,"
                                ."PEMBAYARAN, TOP, CURR, CIF, FAKTUR")
                    ->where("h.ID", $id)->first();
        if ($header){
            $header->TGL_INV = $header->TGL_INV == "" ? "" : Date("d-m-Y", strtotime($header->TGL_INV));
            $header->TGL_DOK_TRM = $header->TGL_DOK_TRM == "" ? "" : Date("d-m-Y", strtotime($header->TGL_DOK_TRM));
            $header->TGL_PO = $header->TGL_PO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PO));
            $header->TGL_JATUH_TEMPO = $header->TGL_JATUH_TEMPO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_JATUH_TEMPO));
            $header->TGL_SC = $header->TGL_SC == "" ? "" : Date("d-m-Y", strtotime($header->TGL_SC));
            $header->TGL_BL = $header->TGL_BL == "" ? "" : Date("d-m-Y", strtotime($header->TGL_BL));
            $header->TGL_FORM = $header->TGL_FORM == "" ? "" : Date("d-m-Y", strtotime($header->TGL_FORM));
            $header->TGL_BERANGKAT = $header->TGL_BERANGKAT == "" ? "" : Date("d-m-Y", strtotime($header->TGL_BERANGKAT));
            $header->TGL_TIBA = $header->TGL_TIBA == "" ? "" : Date("d-m-Y", strtotime($header->TGL_TIBA));
        }
        return $header;
    }
    public static function getTransaksiVo($id)
    {
        $header = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->leftJoin(DB::raw("tbl_header_quota q"), "h.NO_PI","=", "q.ID")
                    ->selectRaw("h.ID, KODE_HS_VO, h.CONSIGNEE, h.NO_PI AS ID_PI, q.NO_PI,"
                                ."TGL_LS, NO_VO, TGL_VO, TGL_PERIKSA_VO,"
                                ."h.STATUS, CATATAN")
                    ->where("h.ID", $id)->first();
        $header->TGL_LS = $header->TGL_LS == "" ? "" : Date("d-m-Y", strtotime($header->TGL_LS));
        $header->TGL_VO = $header->TGL_VO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_VO));
        $header->TGL_PERIKSA_VO = $header->TGL_PERIKSA_VO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_PERIKSA_VO));
        return $header;
    }
    public static function saveTransaksi($action, $header, $kontainer, $detail){
        $check = Transaksi::select("NO_INV");

        $check = $check->where(function($q) use ($header){
            if (trim($header["noinv"]) != ""){
                $q->orWhere("NO_INV", $header["noinv"]);
            }
            if (trim($header["nobl"]) != ""){
                $q->orWhere("NO_BL", $header["nobl"]);
            }
        });
        if ($action == "update"){
            $check->where("ID", "<>", $header["idtransaksi"]);
        }
        if ($check->count() > 0){
            throw new \Exception("Nomer Invoice / Nomor BL sudah ada");
        }

        $arrHeader = Array("KANTOR_ID" => $header["kantor"],
                           "CUSTOMER" => isset($header["customer"]) && trim($header["customer"]) != "" ? $header["customer"] : NULL,
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
                           //"NO_SPTNP" => $header["nostpnp"],
                           //"TGL_SPTNP" => trim($header["tglstpnp"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglstpnp"])),
                           /*
                           "TOTAL_SPTNP" => str_replace(",","",$header["total"]),
                           "JENIS_KESALAHAN" => $header["jeniskesalahan"],
                           */
                           "LEVEL_DOK" => isset($header["leveldok"]) ? $header["leveldok"] : NULL, "NO_PI" => trim($header["idpi"]) == "" ? NULL : $header["idpi"],
                           //"TGL_JATUH_TEMPO_SPTNP" => trim($header["tgljthtemposptnp"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgljthtemposptnp"])),
                           //"BMTB" => trim($header["bmtb"]) == "" ? 0 : str_replace(",","",$header["bmtb"]),
                           //"BMTTB" => trim($header["bmttb"]) == "" ? 0 : str_replace(",","",$header["bmttb"]),
                           //"PPNTB" => trim($header["ppntb"]) == "" ? 0 : str_replace(",","",$header["ppntb"]),
                           //"PPHTB" => trim($header["pphtb"]) == "" ? 0 : str_replace(",","",$header["pphtb"]),
                           //"DENDA_TB" => trim($header["dendatb"]) == "" ? 0 : str_replace(",","",$header["dendatb"]),
                           //"JENIS_SPTNP" => trim($header["jenissptnp"]) == "" ? NULL : $header["jenissptnp"],
                           //"HSL_BRT" => $header["hslbrt"],
                           //"TGL_LUNAS" => trim($header["tgllunas"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgllunas"])),
                          );

        if ($action == "insert"){
            $idtransaksi = Transaksi::insertGetId($arrHeader);
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
                                            "KODE_HS" => $item["KODE_HS"],
                                            "BOOKING" => nullval(str_replace(",","", $item["BOOKING"])),
                                            "SATUAN_ID" => nullval($item["SATUAN_ID"])
                                );
                }
            }
            if (count($arrDetail) > 0){
                DB::table("tbl_realisasi_quota")
                    ->insert($arrDetail);
            }

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
                    if (!isset($item["ID"])
                        || $item["ID"] == ""){
                        $arrDetail[] = Array("ID_HEADER" => $idtransaksi,
                                             "KODE_HS" => $item["KODE_HS"],
                                             "SATUAN_ID" => nullval($item["SATUAN_ID"]),
                                             "BOOKING" => nullval(str_replace(",","", $item["BOOKING"])));
                    }
                    else {
                        $editDetail = Array("KODE_HS" => $item["KODE_HS"],
                                             "SATUAN_ID" => nullval($item["SATUAN_ID"]),
                                             "BOOKING" => nullval(str_replace(",","", $item["BOOKING"])));
                        DB::table("tbl_realisasi_quota")
                            ->where("ID", $item["ID"])
                            ->update($editDetail);
                    }
                }
            }
            if (count($arrDetail) > 0){
                DB::table("tbl_realisasi_quota")
                    ->insert($arrDetail);
            }
            if ($header["deletedetail"] != ""){
                $iddelete = explode(";", $header["deletedetail"]);
                foreach ($iddelete as $iddel){
                    if ($iddel != ""){
                        DB::table("tbl_realisasi_quota")
                            ->where("ID", $iddel)
                            ->delete();
                    }
                }
            }

        }
    }
    public static function saveTransaksiDo($header, $files){

        $check = Transaksi::select("NO_INV");

        $check = $check->where(function($q) use ($header){
            if (trim($header["noinv"]) != ""){
                $q->orWhere("NO_INV", $header["noinv"]);
            }
            if (trim($header["nobl"]) != ""){
                $q->orWhere("NO_BL", $header["nobl"]);
            }
        })
        ->where("ID", "<>", $header["idtransaksi"]);

        if ($check->count() > 0){
            throw new \Exception("Nomer Invoice / Nomor BL sudah ada");
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
                           "JUMLAH_KEMASAN" => nullval(str_replace(",","",$header["jmlkemasan"])),
                           "PEMBAYARAN" => $header["pembayaran"],
                           "TOP" => nullval($header["top"]),
                           "CURR" => nullval($header["curr"]), "FAKTUR" => $header["faktur"],
                           "CIF" => floatval(str_replace(",","",$header["cif"]))
                          );
        $idtransaksi = $header["idtransaksi"];
        Transaksi::where("ID", $idtransaksi)->update($arrHeader);
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
            DB::table("tbl_files")->whereRaw("ID IN " .$strFile)
                ->delete();
        }
        foreach ($fileIds as $key=>$id){
            DB::table("tbl_files")
                ->where("ID", $id)
                ->update(["JENISFILE_ID" => $files[$key]["jenisfile"]]);
        }
    }
    public static function saveTransaksiBarang($header, $detail/*, $files*/){
        $arrHeader = Array("TGL_KELUAR" => trim($header["tglkeluar"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkeluar"])),
                           "TGL_TERIMA" => trim($header["tglterimabrg"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglterimabrg"])),
                           "NDPBM" => nullval(str_replace(",","", $header["ndpbm"])),
                           "CIF" => str_replace(",","",$header["nilai"]),
                           "JUMLAH_KEMASAN" => nullval(str_replace(",","",$header["jumlahkemasan"])),
                           "TGL_NOPEN" => trim($header["tglnopen"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglnopen"])),
                            "PENGIRIM" => $header["pengirim"],"JENIS_DOKUMEN" => nullval($header["jenisdokumen"]),
                            "CUSTOMER" => nullval($header["customer"]), "IMPORTIR" => nullval($header["importir"]),
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
            $arrHeader["BM"] = nullval(str_replace(",","",$header["bm"]));
            $arrHeader["BMT"] = nullval(str_replace(",","",$header["bmt"]));
            $arrHeader["PPN"] = nullval(str_replace(",","",$header["ppn"]));
            $arrHeader["PPH"] = nullval(str_replace(",","",$header["pph"]));
            $arrHeader["PPH_BEBAS"] = nullval(str_replace(",","",$header["pphbebas"]));
            $arrHeader["TOTAL"] = intval($arrHeader["BM"]) +  intval($arrHeader["BMT"]) +intval($arrHeader["PPN"]) + intval($arrHeader["PPH"]);
        }
        if (isset($header["nols"])){
            $arrHeader["NO_LS"] = $header["nols"];
            $arrHeader["TGL_LS"] = trim($header["tglls"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglls"]));
        }
        if (isset($header["tglkonversi"])){
            $arrHeader["TGL_KONVERSI"] = trim($header["tglkonversi"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkonversi"]));
        }
        $id = $header["idtransaksi"];
        Transaksi::where("ID", $id)
                 ->update($arrHeader);
        /*
        $this->setTableName("tbl_files");
        $oldFiles = Array();
        if (!is_array($files)){
            $files = Array();
        }
        $dtFiles = $this->query("SELECT GROUP_CONCAT(ID) AS STR FROM tbl_files WHERE ID_HEADER = '$id'");
        if ($dtFiles->count() > 0){
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

        $deletedetail = $header["deletedetail"];
        $deletedetail = trim($deletedetail,";");
        $deleted = str_replace(";", "','", $deletedetail);
        $deleted = "'" .$deleted ."'";

        DB::table("tbl_detail_barang")
            ->whereRaw("ID IN (" .$deleted .")")
            ->delete();

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
                    DB::table("tbl_detail_barang")
                        ->where("ID", $item["ID"])
                        ->update($data);
                }
            }
            if (count($arrDetail) > 0){
                DB::table("tbl_detail_barang")
                    ->insert($arrDetail);
            }
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
    public static function saveTransaksiVo($header, $detail){
        $arrHeader = Array(
                "TGL_LS" => trim($header["tglls"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglls"])),
                "KODE_HS_VO" => $header["kodehs"], "NO_PI" => $header["idpi"],
                "CATATAN" => $header["catatan"],
                "NO_VO" => $header["novo"],
                "STATUS" => $header["status"],
                "TGL_VO" => trim($header["tglvo"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglvo"])),
                "TGL_PERIKSA_VO" => trim($header["tglperiksa"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglperiksa"])),
            );
        $idtransaksi = $header["idtransaksi"];
        Transaksi::where("ID", $idtransaksi)
                    ->update($arrHeader);

        if (is_array($detail) && count($detail) > 0){
            foreach ($detail as $item){
                $editDetail = Array("KODE_HS" => $item["KODE_HS"],
                                     "SATUAN_ID" => nullval($item["SATUAN_ID"]),
                                     "BOOKING" => nullval(str_replace(",","", $item["BOOKING"])),
                                     "REALISASI" => nullval(str_replace(",","", $item["REALISASI"])));
                DB::table("tbl_realisasi_quota")
                    ->where("ID", $item["ID"])
                    ->update($editDetail);
            }
        }
        if ($header["deletedetail"] != ""){
            $iddelete = explode(";", $header["deletedetail"]);
            foreach ($iddelete as $iddel){
                if ($iddel != ""){
                    DB::table("tbl_realisasi_quota")
                        ->where("ID", $iddel)
                        ->delete();
                }
            }
        }
    }
    public static function saveTransaksiBC($header){
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

        $idtransaksi = $header["idtransaksi"];
        Transaksi::where("ID", $idtransaksi)->update($arrHeader);
    }
    public static function saveTransaksiSPTNP($header){
        $arrHeader = Array(
                "KANTOR_ID" => intval(trim($header["kantor"])), "IMPORTIR" => intval(trim($header["importir"])),
                "TGL_NOPEN" => trim($header["tglnopen"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglnopen"])),
                "TGL_LUNAS" => trim($header["tgllunas"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgllunas"])),
                "TGL_JATUH_TEMPO_SPTNP" => trim($header["tgljthtemposptnp"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgljthtemposptnp"])),
                "TGL_SPTNP" => trim($header["tglsptnp"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglsptnp"])),
                "NOPEN" => $header["nopen"], "NO_SPTNP" => $header["nosptnp"],"NOAJU" => $header["noaju"],
                "NO_SPTNP" => $header["nosptnp"], "HSL_BRT" => nullval($header["hslbrt"]),
                "JENIS_SPTNP" => nullval($header["jenissptnp"]),
                "BMTTB" => floatval(str_replace(",","",$header["bmttb"])), "PPNTB" => floatval(str_replace(",","",$header["ppntb"])),
                "PPHTB" => floatval(str_replace(",","",$header["pphtb"])),"DENDA_TB" => floatval(str_replace(",","",$header["dendatb"])),
                "BMKITE" => floatval(str_replace(",","",$header["bmkite"])),"PPNBM" => floatval(str_replace(",","",$header["ppnbm"])),
                "BMTB" => floatval(str_replace(",","",$header["bmtb"])),
                "NO_BDG" => trim(str_replace("_","",$header["nobdg"]),"."), "MAJELIS" => $header["majelis"],"NO_KEP_BDG" => $header["nokepbdg"],
                "NO_KEPBRT" => $header["nokepbrt"], "HASIL_BDG" => trim($header["hasilbdg"]),
                "TGL_BDG" => trim($header["tglbdg"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglbdg"])),
                "TGL_KEP_BDG" => trim($header["tglkepbdg"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkepbdg"])),
                "TGL_BRT" => trim($header["tglbrt"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglbrt"])),
                "TGL_KEPBRT" => trim($header["tglkepbrt"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkepbrt"])),
                "TGL_JTHTEMPO_BDG" => trim($header["tgljthtmpbdg"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgljthtmpbdg"])),
                "SDG01" => trim($header["sdg01"]) == "" ? NULL : Date("Y-m-d", strtotime($header["sdg01"])),
                "SDG02" => trim($header["sdg02"]) == "" ? NULL : Date("Y-m-d", strtotime($header["sdg02"])),
                "SDG03" => trim($header["sdg03"]) == "" ? NULL : Date("Y-m-d", strtotime($header["sdg03"])),
                "SDG04" => trim($header["sdg04"]) == "" ? NULL : Date("Y-m-d", strtotime($header["sdg04"])),
                "SDG05" => trim($header["sdg05"]) == "" ? NULL : Date("Y-m-d", strtotime($header["sdg05"])),
                "SDG06" => trim($header["sdg06"]) == "" ? NULL : Date("Y-m-d", strtotime($header["sdg06"])),
                "SDG07" => trim($header["sdg07"]) == "" ? NULL : Date("Y-m-d", strtotime($header["sdg07"])),
            );
        $idtransaksi = trim($header["idtransaksi"]);
        if ($idtransaksi != ""){
          Transaksi::where("ID", $idtransaksi)->update($arrHeader);
        }
        else {
          Transaksi::insert($arrHeader);
        }
    }
    public static function updateHasilBongkar($header){

        $check = $this->query("SELECT NO_BL FROM tbl_penarikan_header WHERE NO_BL = '" .$header["nobl"] ."' AND ID <> '" .$header["idtransaksi"] ."'");
        if ($check->count() > 0){
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
    public static function getMonitoring($kantor, $gudang, $customer, $importir, $kategori, $dari, $sampai )
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
    public static function getHarian($kantor, $gudang, $customer, $kategori1, $dari1,
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
    public static function deleteTransaksi($id)
    {
        Transaksi::where("ID", $id)->delete();
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
    public static function browsedo($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2, $kategori3, $dari3, $sampai3)
    {
        $array1 =  Array("No Inv" => "NO_INV","No BL" => "NO_BL","No Vo" => "NO_VO", "Nopen" => "NOPEN","No Aju" => "NOAJU");

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

        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("ID, NO_INV, NO_PO, NO_SC, NO_BL, NO_FORM, NOAJU, NOPEN,"
                            ."i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER,"
                            ."DATE_FORMAT(TGL_BL, '%d-%m-%Y') AS TGLBL,"
                            ."DATE_FORMAT(TGL_LS, '%d-%m-%Y') AS TGLLS,"
                            ."DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,"
                            ."DATE_FORMAT(TGL_DOK_TRM, '%d-%m-%Y') AS TGLDOKTRM,"
                            ."DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN")
                    ->leftJoin(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "h.CUSTOMER", "=", "c.id_customer");
        if (trim($where) != ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function hasilbongkar($kantor, $gudang, $customer, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
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
    public static function browsevo($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2, $kategori3, $dari3, $sampai3)
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

        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("ID, k.KODE AS KANTOR, NO_INV, i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER,"
                              ."IF(STATUS = '' OR STATUS IS NULL, '',"
                              ."CASE  WHEN STATUS = 'K' THEN 'Konfirmasi' "
                              ."WHEN STATUS = 'B' THEN 'Belum Inspect' "
                              ."WHEN STATUS = 'S' THEN 'Sudah Inspect' "
                              ."WHEN STATUS = 'R' THEN 'Revisi FD' "
                              ."WHEN STATUS = 'F' THEN 'FD' "
                              ."WHEN STATUS = 'L' THEN 'LS Terbit' "
                              ."END) AS STATUSVO, NOPEN,"
                              ."DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,"
                              ."DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,"
                              ."DATE_FORMAT(TGL_PERIKSA_VO, '%d-%m-%Y') AS TGLPERIKSAVO,"
                              ."DATE_FORMAT(TGL_VO, '%d-%m-%Y') AS TGLVO,"
                              ."DATE_FORMAT(TGL_LS, '%d-%m-%Y') AS TGLLS, KODE_HS_VO, NO_VO")
                    ->join(DB::raw("ref_kantor k"), "h.KANTOR_ID", "=", "k.KANTOR_ID")
                    ->leftJoin(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "h.CUSTOMER", "=", "c.id_customer");
        if (trim($where) != ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function browsebc($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
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
        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("ID, KANTOR_ID, i.importir_id, c.id_customer,"
                              ."NOPEN, i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER,"
                              ."DATE_FORMAT(TGL_PERIKSA, '%d-%m-%Y') AS TGLPERIKSA,"
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
                              ."IF(LEVEL_DOK = '' OR LEVEL_DOK IS NULL, '',"
                              ."CASE  WHEN LEVEL_DOK = 'K' THEN 'Kuning' "
                              ."WHEN LEVEL_DOK = 'M' THEN 'Merah' "
                              ."WHEN LEVEL_DOK = 'H' THEN 'Hijau' "
                              ."END) AS LEVEL_DOK,"
                              ."HASIL_PERIKSA,  (SELECT GROUP_CONCAT(NOMOR_KONTAINER) "
                              ."FROM tbl_penarikan_kontainer d where d.ID_HEADER = h.ID) "
                              ."AS NOMOR_KONTAINER ")
                    ->leftJoin(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "h.CUSTOMER", "=", "c.id_customer");
        if (trim($where) != ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function browsebayar($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
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

        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("h.ID, h.NO_INV, i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER,"
                              ."IF(PEMBAYARAN = '' OR PEMBAYARAN IS NULL, '',"
                              ."CASE  WHEN PEMBAYARAN = 'Y' THEN 'TT'"
                              ."WHEN PEMBAYARAN = 'T' THEN 'Non TT'"
                              ."END) AS PEMBAYARAN, u.MATAUANG,"
                              ."t.TOP AS TERM,"
                              ."DATE_FORMAT(TGL_JATUH_TEMPO, '%d-%m-%Y') AS TGLJTHTEMPO,"
                              ."IF(FAKTUR = '' OR FAKTUR IS NULL, '',"
                              ."CASE  WHEN FAKTUR = 'Y' THEN 'Ya'"
                              ."WHEN FAKTUR = 'T' THEN 'Tidak'"
                              ."WHEN FAKTUR = 'P' THEN 'Sebagian'"
                              ."END) AS FAKTUR,"
                              ."h.CIF, (SELECT IFNULL(SUM(NOMINAL),0)"
                              ."FROM tbl_detail_bayar WHERE NO_INV = h.ID)"
                              ."AS BAYAR")
                    ->leftJoin(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "h.CUSTOMER", "=", "c.id_customer")
                    ->leftJoin(DB::raw("ref_top t"), "h.TOP", "=", "t.TOP_ID")
                    ->leftJoin(DB::raw("ref_matauang u"), "h.CURR", "=", "u.MATAUANG_ID");

        if (trim($where) != ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function saveTransaksiBayar($action, $header, $detail)
    {
        $arrHeader = Array("TGL_PENARIKAN" => trim($header["tglpenarikan"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tglpenarikan"])),
                           "NOMINAL" => str_replace(",","",$header["totpayment"]),
                           "REKENING_ID" => $header["rekening"],
                           "NO_CEK" => $header["nocek"],
                          );

        if ($action == "insert"){
            $idtransaksi = DB::table("tbl_header_bayar")->insertGetId($arrHeader);
        }
        else if ($action == "update"){
            $idtransaksi = $header["idtransaksi"];
            DB::table("tbl_header_bayar")->where("ID", $idtransaksi)->update($arrHeader);
            DB::table("tbl_detail_bayar")->where("ID_HEADER", $idtransaksi)->delete();
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
            DB::table("tbl_detail_bayar")->insert($arrDetail);
        }
    }
    public static function calculateBayar($id)
    {
        $dtTotal = DB::table("tbl_detail_bayar")
                    ->selectRaw("IFNULL(SUM(ROUND(KURS*NOMINAL)),0) AS TOTAL")
                    ->where("ID_HEADER", $id);
        $total = 0;
        if ($dtTotal->count() > 0){
            $total = $dtTotal->first()->TOTAL;
        }
        DB::table("tbl_header_bayar")->where("ID", $id)
            ->update(["NOMINAL" => $total]);
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
    public static function kartuhutang($kantor, $customer, $importir, $shipper, $kategori2, $dari2, $sampai2)
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
        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("h.ID, h.NO_INV, h.NOPEN, h.CIF, jd.KODE as JENISDOKUMEN, i.nama AS IMPORTIR,"
                              ."c.nama_customer AS CUSTOMER, k.URAIAN AS KANTOR,"
                              ."ship.nama_pemasok AS SHIPPER,"
                              ."DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,"
                              ."DATE_FORMAT(TGL_INV, '%d-%m-%Y') AS TGLINV,"
                              ."DATE_FORMAT(TGL_JATUH_TEMPO, '%d-%m-%Y') AS TGLJTHTEMPO,"
                              ."IFNULL(db.TOT_PAYMENT,0) AS TOT_PAYMENT, h.CIF - IFNULL(db.TOT_PAYMENT,0) AS SALDO, u.MATAUANG")
                    ->join(DB::raw("ref_kantor k"), "k.KANTOR_ID", "=", "h.KANTOR_ID")
                    ->leftJoin(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id")
                    ->leftJoinSub(DB::table("tbl_detail_bayar")
                                    ->selectRaw("NO_INV, IFNULL(SUM(NOMINAL),0) AS TOT_PAYMENT")
                                    ->groupBy("NO_INV"), "db",
                                    function($join){
                                        $join->on("h.ID", "=", "db.NO_INV");
                                    }
                    )
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "h.CUSTOMER", "=", "c.id_customer")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_pemasok ship"), "h.SHIPPER", "=", "ship.id_pemasok")
                    ->leftJoin(DB::raw("ref_jenis_dokumen jd"), "h.JENIS_DOKUMEN", "=", "jd.JENISDOKUMEN_ID")
                    ->leftJoin(DB::raw("ref_matauang u"), "h.CURR", "=", "u.MATAUANG_ID");
        if (trim($where) !== ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function getDetailBayar($id)
    {
        $data = DB::table(DB::raw("tbl_detail_bayar db"))
                    ->selectRaw("ID_HEADER, DATE_FORMAT(TGL_PENARIKAN, '%d-%m-%Y') AS TGLBAYAR,"
                               ."NO_PPU, u.MATAUANG,"
                               ."KURS, db.NOMINAL, KURS*db.NOMINAL AS RUPIAH")
                    ->join(DB::raw("tbl_header_bayar h"), "db.ID_HEADER", "=", "h.ID")
                    ->leftJoin(DB::raw("ref_matauang u"), "db.CURR", "=", "u.MATAUANG_ID")
                    ->where("NO_INV", $id);
        return $data->get();
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
    public static function browseKonversi($kantor, $customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2, $kategori3, $dari3, $sampai3)
    {
        $array1 =  Array("No Inv" => "NO_INV", "Nopen" => "NOPEN","No BL" => "NO_BL", "No Kontainer" => "NOMOR_KONTAINER","Hasil Periksa" => "HASIL_PERIKSA","No Aju" => "NOAJU");

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

        $data = DB::table(DB::raw("tbl_penarikan_header h"))
                    ->selectRaw("ID, KANTOR_ID, i.importir_id, c.id_customer,"
                              ."NOPEN, i.nama AS IMPORTIR, c.nama_customer AS CUSTOMER,"
                              ."DATE_FORMAT(TGL_TIBA, '%d-%m-%Y') AS TGLTIBA,"
                              ."DATE_FORMAT(TGL_DOK_TRM, '%d-%m-%Y') AS TGLDOKTRM,"
                              ."DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,"
                              ."DATE_FORMAT(TGL_SPPB, '%d-%m-%Y') AS TGLSPPB,"
                              ."DATE_FORMAT(TGL_KELUAR, '%d-%m-%Y') AS TGLKELUAR,"
                              ."DATE_FORMAT(TGL_KONVERSI, '%d-%m-%Y') AS TGLKONVERSI,"
                              ."NO_BL, NO_INV, JUMLAH_KEMASAN,"
                              ."IF(JALUR = '' OR JALUR IS NULL, '',"
                              ."CASE  WHEN JALUR = 'K' THEN 'Kuning' "
                              ."WHEN JALUR = 'M' THEN 'Merah' "
                              ."WHEN JALUR = 'H' THEN 'Hijau' "
                              ."END) AS JALURDOK,"
                              ."(SELECT GROUP_CONCAT(NOMOR_KONTAINER) "
                              ."FROM tbl_penarikan_kontainer d where d.ID_HEADER = h.ID) "
                              ."AS NOMOR_KONTAINER")
                    ->leftJoin(DB::raw("importir i"), "h.IMPORTIR", "=", "i.importir_id")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "h.CUSTOMER", "=", "c.id_customer");
        if (trim($where) != ""){
            $data = $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function getKonversi($id)
    {
        $header = DB::table(DB::raw("tbl_detail_barang h"))
                    ->selectRaw("h.ID, h.KODEBARANG, pb.NDPBM, h.URAIAN, h.ID_HEADER, h.HARGA")
                    ->join(DB::raw("tbl_penarikan_header pb"), "h.ID_HEADER", "=", "pb.ID")
                    ->where("h.ID", $id)->first();
        $konversi = DB::table(DB::raw("tbl_konversi k"))
                    ->selectRaw("k.*, DATE_FORMAT(TGL_TERIMA, '%d-%m-%Y') AS TGLTERIMA,"
                                ."p.kode AS KODEPRODUK,p.nama AS NAMAPRODUK")
                    ->join(DB::raw("produk p"), "p.id", "=", "k.PRODUK_ID")
                    ->where("ID_HEADER", $id)->get();

        return Array("header" => $header, "konversi" => $konversi);
    }
    public static function getMutasiBarang($id)
    {
        $header = $this->query("SELECT db.*,
                                    DATE_FORMAT(TGL_KELUAR,'%d-%m-%Y') AS TGLKELUAR,
                                    s.satuan FROM tbl_detail_mutasi db
                                   INNER JOIN satuan s
                                   on db.satuan_id = s.id
                                   WHERE ID_HEADER = '" .$id ."'")->get();

        return Array($header);
    }
    public static function saveMutasiBarang($header, $detail, $files){
        $check = $this->query("SELECT ID FROM tbl_perekaman_barang WHERE ID_HEADER = '" .$header["idtransaksi"] ."'");
        $arrHeader = Array("TGL_KELUAR" => trim($header["tglkeluar"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglkeluar"])),
                           "TGL_TERIMA" => trim($header["tglterimabrg"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglterimabrg"]))
                          );
        $this->setTableName("tbl_perekaman_barang");
        if ($check->count() == 0){
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
        if ($dtFiles->count() > 0){
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
    public static function stokProduk($importir, $dari, $sampai, $kategori1, $isikategori1)
    {
        $array1 =  Array("Kode Produk" => "p.kode");

        $where = "1 = 1 ";
        $whereProduk = "";
        $joinType = "LEFT JOIN";
        if ($kategori1 != ""){
            if (trim($isikategori1) != ""){
                $where  .=  " AND " .$array1[$kategori1] ." Like '%" .$isikategori1 ."%'";
                $whereProduk = "p.kode LIKE '%" .$isikategori1 ."%'";
            }
        }
        if (trim($importir) != ""){
            $where .= " AND IMPORTIR = '" .$importir ."'";
            $joinType = "INNER JOIN";
        }
        $awal1 = DB::table(DB::raw("tbl_konversi tk"))
                  ->selectRaw("p.id, JMLKEMASAN AS kemasansawal, 0 as kemasanmasuk, 0 as kemasankeluar,"
                             ."JMLSATHARGA AS satuansawal, 0 as satuanmasuk, 0 as satuankeluar,"
                             ."'' as satuankemasan, s.satuan as satuan")
                    ->join(DB::raw("tbl_detail_barang tb"), "tk.ID_HEADER", "=", "tb.ID")
                    ->join(DB::raw("tbl_penarikan_header th"), "tb.ID_HEADER", "=", "th.ID")
                    ->join(DB::raw("produk p"), "tk.produk_id", "=", "p.id")
                    ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=", "s.id")
                    ->whereRaw("tk.TGL_TERIMA < '" .Date("Y-m-d", strtotime($dari)) ."'");
        $awal2 = DB::table(DB::raw("tbl_konversi tk"))
                    ->selectRaw("p.id, -JMLKMSKELUAR AS kemasansawal, 0 as kemasanmasuk, 0 as kemasankeluar,"
                                ."-JMLSATHARGAKELUAR satuansawal, 0 as satuanmasuk, 0 as satuankeluar,"
                                ."'' as satuankemasan, s.satuan as satuan")
                    ->join(DB::raw("tbl_detail_barang tb"), "tk.ID_HEADER", "=", "tb.ID")
                    ->join(DB::raw("detail_do do"), "tb.ID", "=", "do.KODEBARANG")
                    ->join(DB::raw("tbl_penarikan_header th"), "tb.ID_HEADER", "=", "th.ID")
                    ->join(DB::raw("produk p"), "tk.produk_id", "=", "p.id")
                    ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=", "s.id")
                    ->whereRaw("do.TGL_KELUAR < '" .Date("Y-m-d", strtotime($dari)) ."'");

        $masuk1 = DB::table(DB::raw("tbl_konversi tk"))
                    ->selectRaw("p.id, 0 AS kemasansawal, JMLKEMASAN as kemasanmasuk, 0 as kemasankeluar,"
                                ."0 AS satuansawal,  JMLSATHARGA as satuanmasuk, 0 as satuankeluar,"
                                ."'' as satuankemasan, s.satuan as satuan")
                    ->join(DB::raw("tbl_detail_barang tb"), "tk.ID_HEADER", "=", "tb.ID")
                    ->join(DB::raw("tbl_penarikan_header th"), "tb.ID_HEADER", "=", "th.ID")
                    ->join(DB::raw("produk p"), "tk.produk_id", "=", "p.id")
                    ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=", "s.id")
                    ->whereRaw("tk.TGL_TERIMA BETWEEN '" .Date("Y-m-d", strtotime($dari)) ."' AND '" .Date("Y-m-d", strtotime($sampai)) ."'");

        $masuk2 = DB::table(DB::raw("tbl_konversi tk"))
                    ->selectRaw("p.id, 0 AS kemasansawal, 0 as kemasanmasuk, JMLKMSKELUAR as kemasankeluar,"
                                ."0 satuansawal, 0 as satuanmasuk, JMLSATHARGAKELUAR as satuankeluar,"
                                ."'' as satuankemasan, s.satuan as satuan")
                    ->join(DB::raw("tbl_detail_barang tb"), "tk.ID_HEADER", "=", "tb.ID")
                    ->join(DB::raw("detail_do do"), "tb.ID", "=", "do.KODEBARANG")
                    ->join(DB::raw("tbl_penarikan_header th"), "tb.ID_HEADER", "=", "th.ID")
                    ->join(DB::raw("produk p"), "tk.produk_id", "=", "p.id")
                    ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=", "s.id")
                    ->whereRaw("do.TGL_KELUAR BETWEEN '" .Date("Y-m-d", strtotime($dari)) ."' AND '" .Date("Y-m-d", strtotime($sampai)) ."'");
        if (trim($where) != ""){
            $awal1 = $awal1->whereRaw($where);
            $awal2 = $awal2->whereRaw($where);
            $masuk1 = $masuk1->whereRaw($where);
            $masuk2 = $masuk2->whereRaw($where);
        }
        $subJoin = $awal1->unionAll($awal2)->unionAll($masuk1)->unionAll($masuk2);

        $data = DB::table(DB::raw("produk p"))
                  ->selectRaw("p.id, kode,"
                          ."SUM(IFNULL(t.kemasansawal,0)) as kemasansawal,"
                          ."SUM(IFNULL(t.kemasanmasuk,0)) as kemasanmasuk,"
                          ."SUM(IFNULL(t.kemasankeluar,0)) As kemasankeluar,"
                          ."SUM(IFNULL(t.kemasansawal,0)) + SUM(IFNULL(t.kemasanmasuk,0)) - SUM(IFNULL(t.kemasankeluar,0)) as kemasansakhir,"
                          ."SUM(IFNULL(t.satuansawal,0)) as satuansawal,"
                          ."SUM(IFNULL(t.satuanmasuk,0)) as satuanmasuk,"
                          ."SUM(IFNULL(t.satuankeluar,0)) As satuankeluar,"
                          ."SUM(IFNULL(t.satuansawal,0)) + SUM(IFNULL(t.satuanmasuk,0)) - SUM(IFNULL(t.satuankeluar,0)) as satuansakhir,"
                          ."t.satuankemasan, t.satuan")
                    ->groupBy("p.id", "p.kode", "t.satuankemasan", "t.satuan");
        if (trim($whereProduk != "")){
            $data = $data->whereRaw($whereProduk);
        }
        if ($joinType == "LEFT JOIN"){
            $data = $data->leftJoinSub($subJoin, "t", "p.id", "=", "t.id");
        }
        else if ($joinType == "INNER JOIN"){
            $data = $data->joinSub($subJoin, "t", "p.id", "=", "t.id");
        }
        return $data->get();
    }
    public static function detailStokProduk($importir, $dari, $sampai, $id)
    {
        $where = "p.id = '" .$id ."'" .($importir != "" ? " AND IMPORTIR = $importir " : "");
        $data = DB::table(DB::raw("tbl_konversi tk"))
                    ->selectRaw("p.id, tb.KODEBARANG, i.NAMA, tk.TGL_TERIMA AS TANGGAL,"
                          ."0 AS kemasansawal, JMLKEMASAN as kemasanmasuk, 0 as kemasankeluar,"
                          ."0 AS satuansawal,  JMLSATHARGA as satuanmasuk, 0 as satuankeluar,"
                          ."'' as satuankemasan, s.satuan as satuan")
                    ->join(DB::raw("tbl_detail_barang tb"), "tk.ID_HEADER", "=", "tb.ID")
                    ->join(DB::raw("tbl_penarikan_header th"), "tb.ID_HEADER", "=", "th.ID")
                    ->join(DB::raw("produk p"), "tk.produk_id", "=", "p.id")
                    ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=", "s.id")
                    ->join(DB::raw("importir i"), "th.IMPORTIR", "=", "i.IMPORTIR_ID")
                    ->whereRaw("tk.TGL_TERIMA BETWEEN '" .Date("Y-m-d", strtotime($dari)) ."' AND '" .Date("Y-m-d", strtotime($sampai)) ."'")
                    ->whereRaw($where)
                    ->union(
                        DB::table(DB::raw("tbl_konversi tk"))
                            ->selectRaw("p.id, tb.KODEBARANG, i.NAMA, do.TGL_KELUAR AS TANGGAL,"
                                        ."0 AS kemasansawal, 0 as kemasanmasuk, JMLKMSKELUAR as kemasankeluar,"
                                        ."0 satuansawal, 0 as satuanmasuk, JMLSATHARGAKELUAR as satuankeluar,"
                                        ."'' as satuankemasan, s.satuan as satuan")
                            ->join(DB::raw("tbl_detail_barang tb"), "tk.ID_HEADER", "=", "tb.ID")
                            ->join(DB::raw("detail_do do"), "tb.ID", "=", "do.KODEBARANG")
                            ->join(DB::raw("tbl_penarikan_header th"), "tb.ID_HEADER", "=", "th.ID")
                            ->join(DB::raw("produk p"), "tk.produk_id", "=", "p.id")
                            ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=", "s.id")
                            ->join(DB::raw("importir i"), "th.IMPORTIR", "=", "i.IMPORTIR_ID")
                            ->whereRaw("do.TGL_KELUAR BETWEEN '" .Date("Y-m-d", strtotime($dari)) ."' AND '" .Date("Y-m-d", strtotime($sampai)) ."'")
                            ->whereRaw($where)
                    )
                    ->orderBy("TANGGAL");
        //echo $data->printquery();die();
        return $data->get();
    }
    public static function stokBarang($customer, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {
        $array1 =  Array("Saldo Akhir" => "t.sakhir", "Kode Barang" => "tb.KODEBARANG");
        $array2 = Array("Tanggal Terima" => "t.TGL_TERIMA", 'Tanggal DO' => "do.TGL_DO");
        $dari2 = Date("Y-m-d", strtotime($dari2));
        $sampai2 = Date("Y-m-d", strtotime($sampai2));

        $where = " 1 = 1 ";
        if ($kategori1 != ""){
            $where  .=  " AND " .$array1[$kategori1] ." LIKE '%" .trim($isikategori1) ."%'";
        }
        if (trim($customer) != ""){
            $where .= " AND th.CUSTOMER = '" .$customer ."'";
        }
        if (trim($importir) != ""){
            $where .= " AND IMPORTIR = '" .$importir ."'";
        }
        $awal1 = DB::table(DB::raw("tbl_konversi tk"))
                    ->selectRaw("tb.ID, tb.KODEBARANG, tk.produk_id, th.CUSTOMER, th.NOAJU, 0 AS TGLDO,"
                            ."IF(th.FAKTUR = 'A', 'Semua', IF(th.FAKTUR = 'P', 'Sebagian', "
                            ."IF (th.FAKTUR = 'T', 'Tidak', ''))) AS FAKTUR, th.NDPBM,"
                            ."tb.HARGA, tk.DPP, tk.TGL_TERIMA,"
                            ."JMLKEMASAN AS kemasansawal, 0 as kemasanmasuk, 0 as kemasankeluar,"
                            ."JMLSATHARGA AS satuansawal, 0 as satuanmasuk, 0 as satuankeluar,"
                            ."'' as satuankemasan, s.satuan as satuan")
                    ->join(DB::raw("tbl_detail_barang tb"), "tk.ID_HEADER", "=", "tb.ID")
                    ->join(DB::raw("tbl_penarikan_header th"), "tb.ID_HEADER", "=", "th.ID")
                    ->join(DB::raw("produk p"), "tk.produk_id", "=", "p.id")
                    ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=", "s.id")
                    ->whereRaw($where)
                    ->where("tk.TGL_TERIMA", "<", $dari2);

        $awal2 = DB::table(DB::raw("detail_do do"))
                    ->selectRaw("tb.ID, tb.KODEBARANG, tk.produk_id, th.CUSTOMER, th.NOAJU, 0 AS TGLDO,"
                            ."IF(th.FAKTUR = 'A', 'Semua', IF(th.FAKTUR = 'P', 'Sebagian', IF (th.FAKTUR = 'T', 'Tidak', ''))) AS FAKTUR, th.NDPBM,"
                            ."tb.HARGA, tk.DPP, tk.TGL_TERIMA,"
                            ."-do.JMLKMSKELUAR AS kemasansawal, 0 as kemasanmasuk, 0 as kemasankeluar,"
                            ."-do.JMLSATHARGAKELUAR AS satuansawal, 0 as satuanmasuk, 0 as satuankeluar,"
                            ."'' as satuankemasan, s.satuan as satuan")
                    ->join(DB::raw("tbl_detail_barang tb"), "do.KODEBARANG", "=", "tb.ID")
                    ->join(DB::raw("tbl_konversi tk"), "tk.ID_HEADER", "=", "tb.ID")
                    ->join(DB::raw("tbl_penarikan_header th"), "tb.ID_HEADER", "=", "th.ID")
                    ->join(DB::raw("produk p"), "tk.produk_id", "=", "p.id")
                    ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=", "s.id")
                    ->whereRaw($where)
                    ->where("do.TGL_KELUAR", "<", $dari2);

        $saldo1 = DB::table(DB::raw("tbl_konversi tk"))
                    ->selectRaw("tb.ID, tb.KODEBARANG, tk.produk_id, th.CUSTOMER, th.NOAJU, 0 AS TGLDO,"
                            ."IF(th.FAKTUR = 'A', 'Semua', IF(th.FAKTUR = 'P', 'Sebagian', "
                            ."IF (th.FAKTUR = 'T', 'Tidak', ''))) AS FAKTUR, th.NDPBM,"
                            ."tb.HARGA, tk.DPP, tk.TGL_TERIMA,"
                            ."JMLKEMASAN AS kemasansawal, 0 as kemasanmasuk, 0 as kemasankeluar,"
                            ."JMLSATHARGA AS satuansawal, 0 as satuanmasuk, 0 as satuankeluar,"
                            ."'' as satuankemasan, s.satuan as satuan")
                    ->join(DB::raw("tbl_detail_barang tb"), "tk.ID_HEADER", "=", "tb.ID")
                    ->join(DB::raw("tbl_penarikan_header th"), "tb.ID_HEADER", "=", "th.ID")
                    ->join(DB::raw("produk p"), "tk.produk_id", "=", "p.id")
                    ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=", "s.id")
                    ->whereRaw($where)
                    ->whereBetween("tk.TGL_TERIMA", [$dari2, $sampai2]);

        $saldo2 = DB::table(DB::raw("detail_do do"))
                    ->selectRaw("tb.ID, tb.KODEBARANG, tk.produk_id, th.CUSTOMER, th.NOAJU, 0 AS TGLDO,"
                            ."IF(th.FAKTUR = 'A', 'Semua', IF(th.FAKTUR = 'P', 'Sebagian', "
                            ."IF (th.FAKTUR = 'T', 'Tidak', ''))) AS FAKTUR, th.NDPBM,"
                            ."tb.HARGA, tk.DPP, tk.TGL_TERIMA,"
                            ."JMLKEMASAN AS kemasansawal, 0 as kemasanmasuk, 0 as kemasankeluar,"
                            ."JMLSATHARGA AS satuansawal, 0 as satuanmasuk, 0 as satuankeluar,"
                            ."'' as satuankemasan, s.satuan as satuan")
                    ->join(DB::raw("tbl_detail_barang tb"), "do.KODEBARANG", "=", "tb.ID")
                    ->join(DB::raw("tbl_konversi tk"), "tk.ID_HEADER", "=", "tb.ID")
                    ->join(DB::raw("tbl_penarikan_header th"), "tb.ID_HEADER", "=", "th.ID")
                    ->join(DB::raw("produk p"), "tk.produk_id", "=", "p.id")
                    ->join(DB::raw("satuan s"), "tb.SATUAN_ID", "=", "s.id")
                    ->whereRaw($where)
                    ->whereBetween("do.TGL_KELUAR", [$dari2, $sampai2]);

        $sub = $awal1->unionAll($awal2)->unionAll($saldo1)->unionAll($saldo2);

        $data = DB::query()->fromSub($sub, "t")
                    ->selectRaw("t.ID, t.KODEBARANG, p.kode, c.nama_customer AS CUSTOMER, t.NDPBM, t.FAKTUR, "
                               ."t.NOAJU, SUM(TGLDO) AS CEKDO,"
                               ."t.HARGA, t.DPP, t.TGL_TERIMA,"
                               ."SUM(IFNULL(t.kemasansawal,0)) as kemasansawal,"
                               ."SUM(IFNULL(t.kemasanmasuk,0)) as kemasanmasuk,"
                               ."SUM(IFNULL(t.kemasankeluar,0)) As kemasankeluar,"
                               ."SUM(IFNULL(t.kemasansawal,0)) + SUM(IFNULL(t.kemasanmasuk,0)) - SUM(IFNULL(t.kemasankeluar,0)) as kemasansakhir,"
                               ."SUM(IFNULL(t.satuansawal,0)) as satuansawal,"
                               ."SUM(IFNULL(t.satuanmasuk,0)) as satuanmasuk,"
                               ."SUM(IFNULL(t.satuankeluar,0)) As satuankeluar,"
                               ."SUM(IFNULL(t.satuansawal,0)) + SUM(IFNULL(t.satuanmasuk,0)) - SUM(IFNULL(t.satuankeluar,0)) as satuansakhir,"
                               ."t.satuankemasan, t.satuan")
                    ->join(DB::raw("produk p"), "p.id","=","t.produk_id")
                    ->join(DB::raw("plbbandu_app15.tb_customer c"), "c.id_customer", "=", "t.CUSTOMER");

        if ($kategori2 == 'Tanggal Terima'){
            $data = $data->whereBetween($array2[$kategori2], [$dari2, $sampai2]);
        }
        $data = $data->groupBy("t.ID", "t.KODEBARANG", "p.kode", "c.nama_customer", "t.NDPBM", "t.FAKTUR", "t.NOAJU",
                               "t.HARGA", "t.DPP", "t.TGL_TERIMA", "t.satuankemasan", "t.satuan");
        if ($kategori2 == 'Tanggal DO'){
            $data = $data->havingRaw("SUM(TGLDO) > 0");
        }
        return $data->get();
    }
    public static function getTransaksiDOrder($id, $includeDetail = TRUE, $searchBy = "ID")
    {
        $header = DB::table(DB::raw("deliveryorder d"))
                    ->selectRaw("d.*, IFNULL(t.TOTJMLKEMASAN,0) AS TOTJMLKMSKELUAR,"
                                ."(SELECT IFNULL(SUM(JMLKEMASAN),0) FROM tbl_pengeluaran p "
                                ."WHERE p.ID_HEADER = d.ID) AS TOTALMUAT,"
                                ."IFNULL(t.TOTJMLSATHARGA,0) AS TOTJMLSATHARGAKELUAR")
                    ->leftJoinSub(DB::table("detail_do")
                                    ->selectRaw("ID_HEADER, SUM(IFNULL(JMLKMSKELUAR,0)) AS TOTJMLKEMASAN,"
                                               ."SUM(IFNULL(JMLSATHARGAKELUAR,0)) AS TOTJMLSATHARGA")
                                    ->groupBy("ID_HEADER"), "t", function($join){
                                        $join->on("d.ID", "=", "t.ID_HEADER");
                                    })
                    ->where("d.".$searchBy, $id);

        if ($header->count() > 0){
            $header = $header->first();
            $header->TGL_DO = $header->TGL_DO == "" ? "" : Date("d-m-Y", strtotime($header->TGL_DO));
            $header->TGL_INV_JUAL = $header->TGL_INV_JUAL == "" ? "" : Date("d-m-Y", strtotime($header->TGL_INV_JUAL));

            $return["header"] = $header;
            if ($includeDetail){
                $detail = DB::table(DB::raw("detail_do d"))
                            ->selectRaw("d.ID, d.KODEBARANG AS KODEBARANG_ID,"
                                    ."p.kode, db.KODEBARANG, JMLKMSKELUAR,"
                                    ."JMLSATHARGAKELUAR, DATE_FORMAT(TGL_KELUAR,'%d-%m-%Y') AS TGL_KELUAR,"
                                    ."dk.HARGAJUAL")
                            ->join(DB::raw("tbl_detail_barang db"), "d.KODEBARANG", "=", "db.ID")
                            ->join(DB::raw("tbl_konversi dk"), "dk.ID_HEADER", "=", "db.ID")
                            ->join(DB::raw("produk p"), "dk.PRODUK_ID", "=", "p.id")
                            ->where("d.ID_HEADER", $id)
                            ->get();
                $return["detail"] = $detail;
            }
            return $return;
        }
        else {
            return false;
        }
    }
    public static function saveTransaksiDorder($action, $header, $detail, $pengeluaran)
    {
        $arrHeader = Array("TGL_INV_JUAL" => trim($header["tglinvjual"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tglinvjual"])),
                           "NO_INV_JUAL" => trim($header["noinvjual"]), "NO_DO" => trim($header["nodo"]),
                           "TGL_DO" => trim($header["tgldo"]) == "" ? Date("Y-m-d") : Date("Y-m-d", strtotime($header["tgldo"])),
                           "PEMBELI" => trim($header["pembeli"]) == "" ? NULL : $header["pembeli"],
                           "TOTAL" => trim($header["total"]) == "" ? NULL : str_replace(",","", $header["total"]));

        if ($action == "insert"){
            $data = DeliveryOrder::create($arrHeader);
            $idtransaksi = $data->ID;
        }
        else if ($action == "update"){
            $idtransaksi = $header["idtransaksi"];
            DeliveryOrder::where("ID", $idtransaksi)->update($arrHeader);
            DB::table("detail_do")->where("ID_HEADER", $idtransaksi)->delete();
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
            DB::table("detail_do")->insert($arrDetail);
        }
        Transaksi::savePengeluaran($idtransaksi, $pengeluaran);
    }
    public static function getDataPengeluaran($id)
	  {
        $data = DB::table("tbl_pengeluaran")
                    ->selectRaw("ID,ID_HEADER, DATE_FORMAT(TGL_MUAT,'%d-%m-%Y') AS TGL_MUAT,"
                               ."NO_POL, NO_SJ, DRIVER, REMARKS,JMLKEMASAN")
                    ->where("ID_HEADER", $id);

		    return $data->get();
    }
    public static function savePengeluaran($id, $data)
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
    public static function saldoQuota($importir, $kategori1, $isiKategori1)
    {
        $kategori = Array("Kode HS" => "d.KODE_HS", "No VO" => "NO_VO", "No Inv" => "NO_INV");
        $union = DB::table(DB::raw("tbl_realisasi_quota d"))
                    ->selectRaw("h.NO_PI AS ID, h.IMPORTIR, d.KODE_HS, "
                               ."0 AS AWAL, IF (REALISASI IS NULL OR REALISASI <= 0, BOOKING, "
                               ."REALISASI) AS TERPAKAI, d.SATUAN_ID")
                    ->join(DB::raw("tbl_penarikan_header h"), "d.ID_HEADER", "=", "h.ID")
                    ->join(DB::raw("tbl_header_quota hq"),"h.NO_PI", "=", "hq.ID")
                    ->where("hq.STATUS", 'Y');
        if ($importir != ""){
            $union = $union->where("IMPORTIR", $importir);
        }
        if ($kategori1 != ""){
            $union = $union->whereRaw($kategori[$kategori1] ." LIKE '%{$isiKategori1}%'");
        }

        $sub = DB::table(DB::raw("tbl_detail_quota d"))
                    ->selectRaw("DISTINCT hq.ID AS ID, h.IMPORTIR, "
                                ."d.KODE_HS, d.SALDO_AWAL AS AWAL, 0 AS TERPAKAI, "
                                ."d.SATUAN_ID")
                    ->join(DB::raw("tbl_header_quota hq"), "d.ID_HEADER", "=", "hq.ID")
                    ->join(DB::raw("tbl_penarikan_header h"),  "hq.CONSIGNEE", "=", "h.CONSIGNEE")
                    ->where("hq.STATUS", 'Y');

        if ($importir != ""){
            $sub = $sub->where("IMPORTIR", $importir);
        }
        if ($kategori1 != ""){
            $sub = $sub->whereRaw($kategori[$kategori1] ." LIKE '%{$isiKategori1}%'");
        }
        $sub = $sub->unionAll($union);

        $data = DB::query()->fromSub($sub, "t")
                    ->selectRaw("t.ID, i.NAMA AS NAMAIMPORTIR, t.KODE_HS, s.satuan AS SATUAN, "
                               ."SUM(IFNULL(t.AWAL,0)) AS AWAL, SUM(IFNULL(t.TERPAKAI,0)) AS TERPAKAI,"
                               ."SUM(IFNULL(t.AWAL,0)) - SUM(IFNULL(t.TERPAKAI,0)) AS AKHIR")
                    ->join(DB::raw("tbl_header_quota hq"), "t.ID", "=", "hq.ID")
                    ->join(DB::raw("satuan s"), "s.id", "=", "t.SATUAN_ID")
                    ->join(DB::raw("importir i"), "t.IMPORTIR", "=", "i.IMPORTIR_ID")
                    ->groupBy("t.ID", "i.NAMA", "t.KODE_HS", "s.satuan");

        //print $data->toSql();die();
        return $data->get();
    }
    public static function detailSaldoQuota($id, $kodehs)
    {
        $data = DB::table(DB::raw("tbl_realisasi_quota d"))
                  ->selectRaw("i.NAMA AS NAMACONSIGNEE, c.nama_customer AS NAMACUSTOMER, "
                             ."h.NO_VO, h.NO_INV, h.NO_BL, d.BOOKING, d.REALISASI, "
                             ."s.satuan as NAMASATUAN")
                  ->join(DB::raw("tbl_penarikan_header h"), "d.ID_HEADER", "=", "h.ID")
                  ->join(DB::raw("tbl_header_quota hq"), "h.NO_PI", "=", "hq.ID")
                  ->join(DB::raw("importir i"), "i.IMPORTIR_ID", "=", "h.IMPORTIR")
                  ->join(DB::raw("plbbandu_app15.tb_customer c"), "c.id_customer", "=", "h.CUSTOMER")
                  ->join(DB::raw("satuan s"), "s.id", "=", "d.SATUAN_ID")
                  ->where("hq.ID", $id)
                  ->where("d.KODE_HS", $kodehs);
        return $data->get();
    }
    public static function getTransaksiVoucher($id)
    {
        $header = DB::table(DB::raw("tbl_voucher h"))
                    ->selectRaw("h.ID, h.TANGGAL, h.NO_BL, h.TOTAL, p.NO_INV, imp.NAMA AS NAMAIMPORTIR, c.nama_customer AS NAMACUSTOMER,"
                               ."p.NOAJU, p.NOPEN, DATE_FORMAT(p.TGL_NOPEN, '%d-%m-%Y) AS TGLNOPEN, p.JUMLAH_KEMASAN,"
                               ."(SELECT GROUP_CONCAT(k.NOMOR_KONTAINER) AS NO_KONTAINER FROM tbl_penarikan_kontainer k WHERE k.ID_HEADER = p.ID) AS NO_KONTAINER")
                    ->join(DB::raw("tbl_penarikan_header p"), "h.ID", "=", "p.NO_BL")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "p.CUSTOMER", "=", "c.id_customer")
                    ->leftJoin(DB::raw("importir imp"), "p.IMPORTIR", "=", "imp.IMPORTIR_ID")
                    ->where("h.ID", $id);
        $detail = DB::table("tbl_detail_voucher")->where("ID_HEADER", $id);
        if ($header->count() > 0){
            return Array("header" => $header->first(), "detail" => $detail->get());
        }
        else {
            return false;
        }
    }
    public static function getBL($no_bl)
    {
        $header = DB::table(DB::raw("tbl_penarikan_header p"))
                    ->selectRaw("p.NO_INV, imp.NAMA AS NAMAIMPORTIR, c.nama_customer AS NAMACUSTOMER,"
                               ."p.NOAJU, p.NOPEN, DATE_FORMAT(p.TGL_NOPEN, '%d-%m-%Y) AS TGLNOPEN, p.JUMLAH_KEMASAN,"
                               ."(SELECT GROUP_CONCAT(k.NOMOR_KONTAINER) AS NO_KONTAINER FROM tbl_penarikan_kontainer k WHERE k.ID_HEADER = p.ID) AS NO_KONTAINER")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "p.CUSTOMER","=","c.id_customer")
                    ->leftJoin(DB::raw("importir imp"), "p.IMPORTIR", "=", "imp.IMPORTIR_ID")
                    ->where("NO_BL", $no_bl);
        if ($header->count() > 0){
            return $header->first();
        }
        else {
            return false;
        }
    }
    public static function getRateDPP()
    {
        $rate = Rate::select("RATE")->orderBy("RATE")->get();
    		$datarate = Array();
    		if ($rate->count() > 0){
    		    foreach ($rate as $row){
        		    $datarate[] = $row->RATE;
    		    }
    		}
    		return $datarate;
    }
    public static function deleteTransaksiDOrder($id)
    {
        if (trim($id) != ""){
            DB::table("detail_do")->where("ID_HEADER", $id)->delete();
            DB::table("deliveryorder")->where("ID", $id)->delete();
            DB::table("tbl_pengeluaran")->where("ID_HEADER", $id)->delete();
        }
    }
    public static function deleteTransaksiBayar($id)
    {
        if (trim($id) != ""){
            DB::table("tbl_detail_bayar")->where("ID_HEADER", $id)->delete();
            DB::table("tbl_header_bayar")->where("ID", $id)->delete();
        }
    }
    public static function profilHarga($supplier, $importir, $kodebarang, $uraian, $kategori1, $dari1, $sampai1)
    {
        $array1 = Array("Tanggal Nopen" => "h.TGL_NOPEN",
                        "Tanggal BL" => "TGL_BL");
        $where = Array();
        if ($kategori1 != ""){
            if (trim($dari1) == "" && trim($sampai1) == ""){
                $where[]=  "(" .$array2[$kategori1] ." IS NULL OR " .$array2[$kategori1] ." = '')";
            }
            else {
                if (trim($dari1) == ""){
                    $dari1 = "0000-00-00";
                }
                if (trim($sampai1) == ""){
                    $sampai1 = "9999-99-99";
                }
                $where[]=  "(" .$array1[$kategori1] ." BETWEEN '" .Date("Y-m-d", strtotime($dari1)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai1)) ."')";
            }
        }
        if (trim($importir) != ""){
            $where[] = " h.IMPORTIR = '" .$importir ."' ";
        }
        if (trim($supplier) != ""){
            $where[] = " s.nama_pemasok LIKE '%" .$supplier ."%' ";
        }
        if (trim($kodebarang) != ""){
            $where[] = " KODEBARANG LIKE '%" .$kodebarang ."%' ";
        }
        if (trim($uraian) != ""){
            $where[] = " d.URAIAN LIKE '%" .$uraian ."%' ";
        }
        $strWhere = trim(implode(" AND ", $where));
        $data = DB::table(DB::raw("tbl_detail_barang d"))
                    ->selectRaw("k.KODE AS KODEKANTOR, s.nama_pemasok AS NAMASUPPLIER,"
                              ."c.nama_customer AS NAMACUSTOMER, h.NOPEN, d.KODEBARANG, d.URAIAN, d.HARGA, st.NO_SPTNP AS NOSPTNP, "
                              ."i.NAMA AS NAMAIMPORTIR,"
                              ."DATE_FORMAT(h.TGL_BL, '%d-%m-%Y') AS TGLBL,"
                              ."DATE_FORMAT(h.TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN")
                    ->join(DB::raw("tbl_penarikan_header h"), "h.ID", "=", "d.ID_HEADER")
                    ->join(DB::raw("ref_kantor k"), "h.KANTOR_ID", "=", "k.KANTOR_ID")
                    ->leftJoin(DB::raw("sptnp st"), DB::raw("UPPER(TRIM(h.NOPEN))"), "=", DB::raw("UPPER(TRIM(st.NOPEN))"))
                    ->leftJoin(DB::raw("plbbandu_app15.tb_pemasok s"), "s.id_pemasok", "=", "h.SHIPPER")
                    ->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "c.id_customer", "=", "h.CUSTOMER")
                    ->leftJoin(DB::raw("importir i"), "i.IMPORTIR_ID" ,'=' ,'h.IMPORTIR');
        if (trim($strWhere) != ""){
            $data->whereRaw($strWhere);
        }
        return $data->get();
    }
}
