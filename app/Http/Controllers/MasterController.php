<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTable;
use App\Models\JenisDokumen;
use App\Models\KodeTransaksi;
use App\Models\Bank;
use App\Models\Rekening;
use App\Models\Customer;

class MasterController extends Controller
{
	public function __construct()
	{
		if (!auth()->user()->can('master.*')){
			abort(403, 'User does not have the right roles.');
		}
	}
	public function kodetransaksi(Request $request)
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Kode Transaksi");
		return view("master.kodetransaksi", ["breads" => $breadcrumb,
        								   "columns" => Array("Kode","Uraian")]);
	}
	public function getdata_kodetransaksi(Request $request)
	{
		$dataSource = KodeTransaksi::select('KODETRANSAKSI_ID','KODE','URAIAN');
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function getdata_bank()
	{
			$dataSource = Bank::select('bank_id','bank');
			$dataTable = datatables()->of($dataSource);
			return $dataTable->toJson();
	}
	public function bank()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Bank");
		return view("master.bank",   ["breads" => $breadcrumb,
									 "columns" => Array("Bank")]);
	}
	public function getdata_rekening()
	{
		$dataSource = Rekening::select('REKENING_ID','NO_REKENING','NAMA','rekening.BANK_ID','BANK')
							   ->join('bank','bank.bank_id','=','rekening.bank_id');
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function rekening()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Rekening");
		$dtBank = Bank::select('BANK_ID','BANK')->get();
		return view("master.rekening",   ["breads" => $breadcrumb,
									 "columns" => Array("Bank","No Rekening","Nama"),
									 "databank" => $dtBank]);
	}
	public function pelmuat()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Pelabuhan Muat");
		return view("master.pelmuat",   ["breads" => $breadcrumb,
									 "columns" => Array("Kode","Uraian")]);
	}
	public function getdata_pelmuat()
	{
		$dataSource = PelabuhanMuat::select('pelmuat_id','kode','uraian');
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function importir()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Importir");
		return view("master.importir",
									 ["breads" => $breadcrumb,
									 "columns" => Array("NPWP","Nama","Alamat","Telepon","Email")]);
	}
	public function getdata_importir()
	{
		$dataSource = Importir::select('importir_id','npwp','nama','alamat','telepon','email');
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function jenisdokumen()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Jenis Dokumen");
		return view("master.jenisdokumen",  ["breads" => $breadcrumb,
									 "columns" => Array("Kode","Jenis Dokumen")]);
	}
	public function getdata_jenisdokumen()
	{
		$dataSource = JenisDokumen::select('jenisdokumen_id','kode','uraian');
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function kantor()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Kode Kantor");
		return view("master.kantor",   ["breads" => $breadcrumb,
									 "columns" => Array("Kode","Nama Kantor")]);
	}
	public function getdata_kantor()
	{
		$dataSource = Kantor::select('kantor_id','kode','uraian');
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function ratedpp()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Rate DPP");
		return view("master.ratedpp",   ["breads" => $breadcrumb,
									 "columns" => Array("Rate")]);
	}
	public function getdata_ratedpp()
	{
		$dataSource = Rate::select('RATE_ID','RATE');
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function pembeli()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Pembeli");
		$customer = DB::table("tb_customer")
					   ->select("id_customer", "nama_customer")
					   ->orderBy("nama_customer")->get();
		return view("master.pembeli",   ["breads" => $breadcrumb,
									     "customer" => $customer, "columns" => Array("Kode","Nama Pembeli","Customer","Alamat")]);
	}
	public function getdata_pembeli()
	{
		$dataSource = Pembeli::select('ID','KODE', 'CUSTOMER', 'NAMA', 'ALAMAT', 'KTPNPWP', 'KETERANGAN',
								DB::raw('customer.nama_customer as NAMACUSTOMER'))
							   ->joinSub(DB::table('tb_customer')
							   				->select('id_customer', 'nama_customer'),
							             'customer', function($join){
											$join->on('customer.id_customer', '=', 'ref_pembeli.CUSTOMER');
										 });
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function pemasok()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Pemasok");
		$negara = DB::table("plbbandu_app15.tb_country")->select("id_country","country_name")->get();
		return view("master.pemasok",
									 ["breads" => $breadcrumb, "negara" => $negara,
									 "columns" => Array("Nama","Alamat","Telepon","Negara","Fax","Website")]);
	}
	public function getdata_pemasok()
	{
		$dataSource = Pemasok::select("*", DB::raw("negara.country_name as negara"))
												  ->leftJoin("plbbandu_app15.tb_country as negara", "negara_pemasok","=","negara.id_country")
													->get();
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function customer()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Customer");
		$negara = DB::table("plbbandu_app15.tb_country")->select("id_country","country_name")->get();
		return view("master.customer",
									 ["breads" => $breadcrumb, "negara" => $negara,
									 "columns" => Array("Nama","Alamat","Telepon","Negara","Fax","Website","Kode")]);
	}
	public function getdata_customer()
	{
		$dataSource = Customer::select("*", DB::raw("negara.country_name as negara"))
												  ->leftJoin("plbbandu_app15.tb_country as negara", "negara_customer","=","negara.id_country")
													->get();
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function jenistruk()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Jenis Truk");
		return view("master.jenistruk",
									 ["breads" => $breadcrumb,
									 "columns" => Array("Jenis Truk")]);
	}
	public function getdata_jenistruk()
	{
		$dataSource = JenisTruk::all();
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function ekspedisi()
	{
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Ekspedisi");
		return view("master.ekspedisi",
									 ["breads" => $breadcrumb,
									 "columns" => Array("Kode","Nama","Alamat","Telepon")]);
	}
	public function getdata_ekspedisi()
	{
		$dataSource = Ekspedisi::all();
		$dataTable = datatables()->of($dataSource);
		return $dataTable->toJson();
	}
	public function crud(Request $request)
	{
		$action = $request->input("action");
		$message = Array();
		if ($action){
			$fields = $request->input("input");
			parse_str($fields, $input);
			try {
				switch ($action){
					case "kodetransaksi":
						if ($input["input-action"] == "add"){
							$result = KodeTransaksi::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = KodeTransaksi::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = KodeTransaksi::drop($input["id"]);
						}
						break;
					case "produk":
						if ($input["input-action"] == "add"){
							$result = Produk::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Produk::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Produk::drop($input["id"]);
						}
						break;
					case "jenisbarang":
						if ($input["input-action"] == "add"){
							$result = JenisBarang::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = JenisBarang::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = JenisBarang::drop($input["id"]);
						}
						break;
					case "jeniskemasan":
						if ($input["input-action"] == "add"){
							$result = JenisKemasan::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = JenisKemasan::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = JenisKemasan::drop($input["id"]);
						}
						break;
					case "gudang":
						if ($input["input-action"] == "add"){
							$result = Gudang::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Gudang::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Gudang::drop($input["id"]);
						}
						break;
					case "importir":
						if ($input["input-action"] == "add"){
							$result = Importir::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Importir::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Importir::drop($input["id"]);
						}
						break;
				  case "pemasok":
						if ($input["input-action"] == "add"){
							$result = Pemasok::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Pemasok::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Pemasok::drop($input["id"]);
						}
					break;
					case "customer":
						if ($input["input-action"] == "add"){
							$result = Customer::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Customer::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Customer::drop($input["id"]);
						}
					break;
					case "jenisdokumen":
						if ($input["input-action"] == "add"){
							$result = JenisDokumen::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = JenisDokumen::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = JenisDokumen::drop($input["id"]);
						}
						break;
					case "kantor":
						if ($input["input-action"] == "add"){
							$result = Kantor::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Kantor::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Kantor::drop($input["id"]);
						}
						break;
					case "pelmuat":
						if ($input["input-action"] == "add"){
							$result = PelabuhanMuat::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = PelabuhanMuat::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = PelabuhanMuat::drop($input["id"]);
						}
						break;
					case "bank":
						if ($input["input-action"] == "add"){
							$result = Bank::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Bank::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Bank::drop($input["id"]);
						}
						break;
					case "rekening":
						if ($input["input-action"] == "add"){
							$result = Rekening::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Rekening::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Rekening::drop($input["id"]);
						}
						break;
					case "penerima":
						if ($input["input-action"] == "add"){
							$result = Penerima::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Penerima::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Penerima::drop($input["id"]);
						}
						break;
					case "pembeli":
						if ($input["input-action"] == "add"){
							$result = Pembeli::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Pembeli::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Pembeli::drop($input["id"]);
						}
						break;
					case "ratedpp":
						if ($input["input-action"] == "add"){
							$result = Rate::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = Rate::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = Rate::drop($input["id"]);
						}
						break;
					case "jenistruk":
						if ($input["input-action"] == "add"){
							$result = JenisTruk::add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = JenisTruk::edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = JenisTruk::drop($input["id"]);
						}
						break;
					case "ekspedisi":
							if ($input["input-action"] == "add"){
								$result = Ekspedisi::add($input);
							}
							else if ($input["input-action"] == "edit"){
								$result = Ekspedisi::edit($input);
							}
							else if ($input["input-action"] == "delete"){
								$result = Ekspedisi::drop($input["id"]);
							}
							break;
				}
				$message["result"] = $result;
			}
			catch (\Exception $e){
				$message["error"] = $e->getMessage();
			}
		}
		return response()->json($message);
	}
}
