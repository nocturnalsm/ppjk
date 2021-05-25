<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Sptnp;

function nullcast($value)
{
    return trim($value) == "" ? NULL : $value;
}

class Sptnp extends Model
{
    protected $table  = 'sptnp';
    protected $primaryKey = 'ID';
    protected $guarded = ['ID'];
    public $timestamps = false;


    public static function getTransaksiSPTNP($id)
    {
        $data = Sptnp::selectRaw("ID, KANTOR_ID, IMPORTIR, NOPEN, TGL_NOPEN, NOAJU,"
                                      ."NO_SPTNP, TGL_SPTNP, BMKITE, PPNBM,"
                                      ."BMTB, BMTTB, PPNTB, PPHTB, DENDA_TB,"
                                      ."IFNULL(BMTB,0) + IFNULL(BMTTB,0) + IFNULL(PPNTB,0) + IFNULL(PPHTB,0) + IFNULL(PPNBM,0) + IFNULL(BMKITE,0) + IFNULL(DENDA_TB,0) AS TOTAL_TB,"
                                      ."JENIS_SPTNP, TGL_JATUH_TEMPO_SPTNP, TGL_LUNAS,"
                                      ."TGL_BRT, HSL_BRT, NO_KEPBRT, TGL_KEPBRT, TGL_JTHTEMPO_BDG,"
                                      ."NO_BDG, TGL_BDG, MAJELIS, SDG01, SDG02, SDG03,"
                                      ."SDG04, SDG05, SDG06, SDG07, HASIL_BDG, NO_KEP_BDG, TGL_KEP_BDG")
                    ->where("ID", $id);
        $user = auth()->user();
    		if ($user->hasRole('impor_company') || $user->hasRole('company')){
    				$company = $user->hasCompany();
    				$data = $data->where("IMPORTIR", $company->id);
    		}
        if ($data->exists()){
            $data = $data->first();
        }
        else {
            return false;
        }
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
    public static function saveTransaksiSPTNP($header){
        $idtransaksi = trim($header["idtransaksi"]);
        $check = Sptnp::where("NO_SPTNP", $header["nosptnp"])->value("ID");
        if ($check){
          if ($idtransaksi == "" || ($idtransaksi != "" && $idtransaksi != $check)){
            throw new \Exception('No SPTNP sudah ada');
          }
        }
        $arrHeader = Array(
                "KANTOR_ID" => intval(trim($header["kantor"])), "IMPORTIR" => intval(trim($header["importir"])),
                "TGL_NOPEN" => trim($header["tglnopen"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglnopen"])),
                "TGL_LUNAS" => trim($header["tgllunas"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgllunas"])),
                "TGL_JATUH_TEMPO_SPTNP" => trim($header["tgljthtemposptnp"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tgljthtemposptnp"])),
                "TGL_SPTNP" => trim($header["tglsptnp"]) == "" ? NULL : Date("Y-m-d", strtotime($header["tglsptnp"])),
                "NOPEN" => $header["nopen"], "NO_SPTNP" => $header["nosptnp"],"NOAJU" => $header["noaju"],
                "NO_SPTNP" => $header["nosptnp"], "HSL_BRT" => $header["hslbrt"],
                "JENIS_SPTNP" => $header["jenissptnp"],
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
        if ($idtransaksi != ""){
          Sptnp::where("ID", $idtransaksi)->update($arrHeader);
        }
        else {
          Sptnp::insert($arrHeader);
        }
    }
    public static function deleteTransaksi($id)
    {
        Sptnp::where("ID", $id)->delete();
    }
    public static function browseSPTNP($kantor, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2, $kategori3, $dari3, $sampai3)
    {
        $array1 =  Array("Nopen" => "NOPEN","No SPTNP" => "NO_SPTNP","Jenis Notul" => "JENIS_SPTNP");

        $array2 = Array("Tanggal Nopen" => "TGL_NOPEN", "Tanggal Lunas" => "TGL_LUNAS","Tanggal Jatuh Tempo" => "TGL_JATUH_TEMPO_SPTNP", "Tanggal SPTNP" => "TGL_SPTNP", "Tanggal BRT" => "TGL_BRT");
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
        if ($kategori3 != ""){
            if (trim($dari3) == "" && trim($sampai3) == ""){
                $where  .=  " AND (" .$array2[$kategori3] ." IS NULL OR " .$array2[$kategori3] ." = '')";
            }
            else {
                if (trim($dari3) == ""){
                    $dari3 = "0000-00-00";
                }
                if (trim($sampai3) == ""){
                    $sampai3 = "9999-99-99";
                }
                $where  .=  " AND (" .$array2[$kategori3] ." BETWEEN '" .Date("Y-m-d", strtotime($dari3)) ."'
                                            AND '" .Date("Y-m-d", strtotime($sampai3)) ."')";
            }
        }
        if (trim($importir) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."IMPORTIR = '" .$importir ."'";
        }
        if (trim($kantor) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."k.KANTOR_ID = '" .$kantor ."'";
        }
        $data = DB::table(DB::raw("sptnp h"))
                    ->selectRaw("ID, k.KODE AS KODEKANTOR, NO_SPTNP, NOPEN, NOAJU, DENDA_TB, "
                              ."i.NAMA AS NAMAIMPORTIR, BMTB, BMTTB, PPNTB, PPNBM, PPHTB, JENIS_SPTNP, "
                              ."DATE_FORMAT(TGL_JATUH_TEMPO_SPTNP, '%d-%m-%Y') AS TGLJTHTEMPOSPTNP,"
                              ."DATE_FORMAT(TGL_SPTNP, '%d-%m-%Y') AS TGLSPTNP,"
                              ."DATE_FORMAT(TGL_BRT, '%d-%m-%Y') AS TGLBRT,NO_KEP_BDG,"
                              ."DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,"
                              ."DATE_FORMAT(TGL_LUNAS, '%d-%m-%Y') AS TGLLUNAS,"
                              ."IFNULL(BMTB,0)+IFNULL(PPNBM,0)+IFNULL(BMKITE,0)+IFNULL(BMTTB,0)+IFNULL(PPNTB,0)+IFNULL(PPHTB,0)+IFNULL(DENDA_TB,0) AS TOTAL_TB")
                    ->join(DB::raw("ref_kantor k"), "h.KANTOR_ID", "=", "k.KANTOR_ID")
                    ->leftJoin(DB::raw("importir i"), "i.IMPORTIR_ID" ,'=' ,'h.IMPORTIR');
        if (trim($where) != ""){
            $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function browseKeberatan($kantor, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2)
    {
        $array1 =  Array("Nopen" => "NOPEN","No SPTNP" => "NO_SPTNP");

        $array2 = Array("Tanggal Jatuh Tempo" => "TGL_JATUH_TEMPO_SPTNP", "Tanggal Nopen" => "TGL_NOPEN",
                        "Tanggal SPTNP" => "TGL_SPTNP", "Tanggal BRT" => "TGL_BRT",
                        "Tanggal Lunas" => "TGL_LUNAS", "Tgl Jatuh Tempo Bdg" => "TGL_JTHTEMPO_BDG");

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
        if (trim($importir) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."IMPORTIR = '" .$importir ."'";
        }
        if (trim($kantor) != ""){
            $where .= (trim($where) != "" ? " AND " : "") ."KANTOR_ID = '" .$kantor ."'";
        }
        $data = DB::table(DB::raw("sptnp h"))
                    ->selectRaw("ID, k.KODE AS KODEKANTOR, NO_SPTNP, HSL_BRT, NOPEN,"
                              ."i.NAMA AS NAMAIMPORTIR, NO_KEPBRT,"
                              ."DATE_FORMAT(TGL_SPTNP, '%d-%m-%Y') AS TGLSPTNP,"
                              ."DATE_FORMAT(TGL_JATUH_TEMPO_SPTNP, '%d-%m-%Y') AS TGLJTHTEMPOSPTNP,"
                              ."DATE_FORMAT(TGL_JTHTEMPO_BDG, '%d-%m-%Y') AS TGLJTHTMPBDG,"
                              ."DATE_FORMAT(TGL_BRT, '%d-%m-%Y') AS TGLBRT,NO_KEP_BDG,"
                              ."DATE_FORMAT(TGL_KEP_BDG, '%d-%m-%Y') AS TGLKEPBDG,"
                              ."DATE_FORMAT(TGL_KEPBRT, '%d-%m-%Y') AS TGLKEPBRT,"
                              ."DATE_FORMAT(TGL_LUNAS, '%d-%m-%Y') AS TGLLUNAS,"
                              ."IFNULL(BMTB,0)+IFNULL(PPNBM,0)+IFNULL(BMKITE,0)+IFNULL(BMTTB,0)+IFNULL(PPNTB,0)+IFNULL(PPHTB,0)+IFNULL(DENDA_TB,0) AS TOTAL_TB")
                    ->join(DB::raw("ref_kantor k"), "h.KANTOR_ID", "=", "k.KANTOR_ID")
                    ->leftJoin(DB::raw("importir i"), "i.IMPORTIR_ID" ,'=' ,'h.IMPORTIR');
        if (trim($where) != ""){
            $data->whereRaw($where);
        }
        return $data->get();
    }
    public static function browseBanding($kantor, $importir, $kategori1, $isikategori1, $kategori2, $dari2, $sampai2, $kategori3, $dari3, $sampai3)
    {
        $array1 =  Array("Nopen" => "NOPEN","No Kep Brt" => "NO_KEPBRT","No Sengk" => "NO_BDG","Mjls" => "MAJELIS");

        $array2 = Array("Tanggal Nopen" => "TGL_NOPEN",
                        "Tanggal Sengk" => "TGL_BDG",
                        "Tanggal Kep Brt" => "TGL_KEPBRT");

        $where = "NO_SPTNP IS NOT NULL AND TRIM(NO_SPTNP) <> '' AND NO_KEPBRT IS NOT NULL AND TRIM(NO_KEPBRT) <> ''";
        if ($kategori1 != ""){
            if (trim($isikategori1) == ""){
                $where  .=  " AND (" .$array1[$kategori1] ." IS NULL OR " .$array1[$kategori1] ." = '')";
            }
            else {
                $where  .=  " AND (" .$array1[$kategori1] ." LIKE '%" .$isikategori1 ."%')";
            }

        }
        if ($kategori2 != ""){
            if ($kategori2 == "Tanggal Sidang"){
                if ($dari2 != "" && $sampai2 != ""){
                  $filterSdg1 = Array();
                  for ($tgl=0;$tgl<7;$tgl++){
                    $filterSdg1[] = "(SDG0" .strval($tgl+1) ." BETWEEN '" .Date("Y-m-d", strtotime($dari2)) ."'
                                                AND '" .Date("Y-m-d", strtotime($sampai2)) ."')";
                  }
                  $where .= " AND (" .implode(" OR ", $filterSdg1) .")";
                }
            }
            else {
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
        }
        if ($kategori3 != ""){
            if ($kategori3 == "Tanggal Sidang"){
                if (trim($dari3) != "" && trim($sampai3) != ""){
                  $filterSdg2 = Array();
                  for ($tgl=0;$tgl<7;$tgl++){
                    $filterSdg2[] = "(SDG0" .strval($tgl+1) ." BETWEEN '" .Date("Y-m-d", strtotime($dari3)) ."'
                                                AND '" .Date("Y-m-d", strtotime($sampai3)) ."')";
                  }
                  $where .= " AND (" .implode(" OR ", $filterSdg2) .")";
                }
            }
            else {
                if (trim($dari3) == "" && trim($sampai3) == ""){
                    $where  .=  " AND (" .$array2[$kategori3] ." IS NULL OR " .$array2[$kategori3] ." = '')";
                }
                else {
                    if (trim($dari3) == ""){
                        $dari3 = "0000-00-00";
                    }
                    if (trim($sampai3) == ""){
                        $sampai3 = "9999-99-99";
                    }
                    $where  .=  " AND (" .$array2[$kategori3] ." BETWEEN '" .Date("Y-m-d", strtotime($dari3)) ."'
                                                AND '" .Date("Y-m-d", strtotime($sampai3)) ."')";
                }
            }
        }
        if (trim($kantor) != ""){
            $where .= " AND KANTOR_ID = '" .$kantor ."'";
        }
        if (trim($importir) != ""){
            $where .= " AND IMPORTIR = '" .$importir ."'";
        }
        $data = DB::table(DB::raw("sptnp h"))
                    ->selectRaw("ID, k.KODE AS KODEKANTOR, NO_KEPBRT, HASIL_BDG, NOPEN,"
                              ."i.NAMA AS NAMAIMPORTIR, NO_BDG, MAJELIS, "
                              ."DATE_FORMAT(TGL_NOPEN, '%d-%m-%Y') AS TGLNOPEN,"
                              ."DATE_FORMAT(TGL_KEPBRT, '%d-%m-%Y') AS TGLKEPBRT,"
                              ."DATE_FORMAT(TGL_BDG, '%d-%m-%Y') AS TGLBDG,"
                              ."DATE_FORMAT(SDG01, '%d-%m-%Y') AS SDG01,"
                              ."DATE_FORMAT(SDG02, '%d-%m-%Y') AS SDG02, NO_KEP_BDG,"
                              ."DATE_FORMAT(TGL_KEP_BDG, '%d-%m-%Y') AS TGLKEPBDG,"
                              ."DATE_FORMAT(SDG03, '%d-%m-%Y') AS SDG03,"
                              ."DATE_FORMAT(SDG04, '%d-%m-%Y') AS SDG04,"
                              ."DATE_FORMAT(SDG05, '%d-%m-%Y') AS SDG05,"
                              ."DATE_FORMAT(SDG06, '%d-%m-%Y') AS SDG06,"
                              ."DATE_FORMAT(SDG07, '%d-%m-%Y') AS SDG07")
                    ->join(DB::raw("ref_kantor k"), "h.KANTOR_ID", "=", "k.KANTOR_ID")
                    ->leftJoin(DB::raw("importir i"), "i.IMPORTIR_ID" ,'=' ,'h.IMPORTIR');
        if (trim($where) != ""){
            $data->whereRaw($where);
        }
        return $data->get();
    }
}
