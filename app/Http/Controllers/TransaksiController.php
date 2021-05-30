<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DB;
use DataTable;
use App\Models\Transaksi;
use App\Models\Sptnp;
use App\Models\Produk;
use App\Models\Quota;
use App\Models\DeliveryOrder;
use App\User;

class TransaksiController extends Controller {

	public function schedule(Request $request, $id = "")
	{
    $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Transaksi");

		$dtImportir = Transaksi::getImportir();
		$dtCustomer = Transaksi::getCustomer();
		$dtJenisBarang = Transaksi::getJenisBarang();
		$dtJenisKemasan = Transaksi::getJenisKemasan();
		$dtJumlahKontainer = Transaksi::getJumlahKontainer();
		$dtUkuranKontainer = Transaksi::getUkuranKontainer();
		$dtJenisDokumen = Transaksi::getJenisDokumen();
		$dtShipper = Transaksi::getShipper();
		$dtPelmuat = Transaksi::getPelmuat();
		$dtKantor = Transaksi::getKantor();
		$dtSatuan = Transaksi::getSatuan();

		$dtTransaksi = Array();
		$pi = [];
		$dtTransaksi = Transaksi::getTransaksi($id);
		if (!$dtTransaksi){
				abort(404, 'Data tidak ada');
		}
		$pi = Transaksi::getPI($dtTransaksi["header"]->CONSIGNEE);

		if ($id != ""){
			$notransaksi = " No. " .sprintf('%08d', $dtTransaksi["header"]->ID);
			$detailQuota = json_encode(Transaksi::getRealisasiQuota($id));
		}
		else {
			$notransaksi = " (Baru)";
		}
		$data = [
				"header" => $dtTransaksi["header"], "breads" => $breadcrumb, "importir" => $dtImportir,
				"kontainer" => isset($dtTransaksi["kontainer"]) ? json_encode($dtTransaksi["kontainer"]) : "{}",
				"jeniskemasan" => $dtJenisKemasan, "customer" => $dtCustomer,
				"jenisdokumen" => $dtJenisDokumen, "datasatuan" => $dtSatuan,
				"kodekantor" => $dtKantor, "pelmuat" => $dtPelmuat, "shipper" => $dtShipper,
				"jumlahkontainer" => $dtJumlahKontainer, "quota" => isset($detailQuota) ? $detailQuota : "{}", "pi" => $pi,
				"jenisbarang" => $dtJenisBarang, "idtransaksi" => $id,
				"ukurankontainer" => $dtUkuranKontainer, "notransaksi" => $notransaksi,
				"canDelete" => $id != "", "readonly" => auth()->user()->cannot('schedule.transaksi') ? "readonly" : ""
			];
		return view("transaksi.transaksi", $data);
	}
	public function transaksibayar(Request $request)
  {
		$canBrowse = auth()->user()->can('pembayaran.browse');
		$canEdit = auth()->user()->can('pembayaran.transaksi');

		$dtTransaksi = Array();
		$id = $request->id ?? "";
		if ($id != ""){
			if (!$canBrowse){
					abort(403, "User does not have the right roles");
			}
		}
		else {
			if (!$canEdit){
					abort(403, "User does not have the right roles");
			}
			$notransaksi = " (Baru)";
		}
		$dtTransaksi = Transaksi::getTransaksiBayar($id);
		if (!$dtTransaksi){
				abort(404, 'Data tidak ada');
		}

		$breadcrumb[] = Array("link" => "/", "text" => "Home");

		$breadcrumb[] = Array("text" => "Transaksi Pembayaran");

		$dtRekening = Transaksi::getRekening();
		$dtMataUang = Transaksi::getMataUang();
		$dtKantor = Transaksi::getKantor();

		$data = [
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb,
				"rekening" => $dtRekening,
				"detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
				"matauang" => $dtMataUang,
				"readonly" => $canEdit ? '' : 'readonly'
			];
		return view("transaksi.transaksibayar", $data);
	}
	public function userdo(Request $request)
  {
		if(!auth()->user()->can('dokumen.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/perekamando", "text" => "Browse Do");
		$breadcrumb[] = Array("text" => "Perekaman Do");

		$id = $request->id;
		if (trim($id) == ""){
				abort(404, 'Data tidak ada');
		}
		$dtPelmuat = Transaksi::getPelmuat();
		$dtTransaksi = Transaksi::getTransaksiDo($id);
		if (!$dtTransaksi){
				abort(404, 'Data tidak ada');
		}
		$dtMataUang = Transaksi::getMataUang();

		$dtTOP = Transaksi::getTOP();
		$dtFiles = Transaksi::getFiles($id);
		$dtJenisFile = Transaksi::getJenisFile();
		$notransaksi = " No. " .sprintf('%08d', $dtTransaksi->ID);
		$data = [
				"header" => isset($dtTransaksi) ? $dtTransaksi : "{}" , "breads" => $breadcrumb,
				"idtransaksi" => $id,"notransaksi" => $notransaksi, "pelmuat" => $dtPelmuat,
				"files" => $dtFiles,"top" => $dtTOP, "matauang" => $dtMataUang,
				"jenisfile" => $dtJenisFile, "readonly" => auth()->user()->can('dokumen.transaksi') ? "" : "readonly"
			];
		return view("transaksi.transaksido", $data);
	}
	public function uservo(Request $request, $id)
  {
		if(!auth()->user()->can('vo.browse')){
			abort(403, 'User does not have the right roles.');
		}
		if (trim($id) == ""){
				abort(404, 'Data tidak ada');
		}
    $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/perekamanvo", "text" => "Browse VO");
		$breadcrumb[] = Array("text" => "Perekaman VO");
		$dtTransaksi = Transaksi::getTransaksiVo($id);
		if (!$dtTransaksi){
				abort(404, 'Data not found');
		}
		$dtImportir = Transaksi::getImportir();
		$dtSatuan = Transaksi::getSatuan();
		$detailQuota = json_encode(Transaksi::getRealisasiQuota($id));
		$notransaksi = " No. " .sprintf('%08d', $dtTransaksi->ID);
		if ($dtTransaksi->NO_PI == ''){
		    $dataPI = Transaksi::getPI($dtTransaksi->CONSIGNEE);
		    if ($dataPI){
		        $dtTransaksi->ID_PI = $dataPI->ID;
		        $dtTransaksi->NO_PI = $dataPI->NO_PI;
		    }
		}
		$data = [
				"header" => $dtTransaksi, "breads" => $breadcrumb,
				"quota" => $detailQuota, "datasatuan" => $dtSatuan,
				"idtransaksi" => $id, "importir" => $dtImportir,
				"notransaksi" => $notransaksi,
				"readonly" => auth()->user()->cannot('vo.transaksi') ? "readonly" : ""
			];
		return view("transaksi.transaksivo", $data);
	}
	public function usersptnp(Request $request, $id = "")
  {
		$canEdit = auth()->user()->can('sptnp.transaksi');
		$canBrowse = auth()->user()->can('sptnp.browse');

		if ($id != ""){
			if ($canBrowse){
					$dtTransaksi = Sptnp::getTransaksiSPTNP($id);
					if (!$dtTransaksi){
							abort(404, 'Data tidak ada');
					}
					$notransaksi = " No. " .sprintf('%08d', $dtTransaksi->ID);
			}
			else {
					abort(403, "User doesn't have the right roles");
			}
		}
		else {
			if ($canEdit){
					$dtTransaksi = new Sptnp;
					$notransaksi = "Baru";
			}
			else {
					abort(403, "User doesn't have the right roles");
			}
		}
    $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman SPTNP");

		$kantor = Transaksi::getKantor();
		$importir = Transaksi::getImportir();

		$data = [
				"header" => $dtTransaksi, "kodekantor" => $kantor, "importir" => $importir, "breads" => $breadcrumb,
				"idtransaksi" => $id, "notransaksi" => $notransaksi,
				"readonly" => $canEdit ? '' : 'readonly'
			];
		return view("transaksi.transaksisptnp", $data);
	}
	public function exportXls($spreadsheet, $prefix)
	{
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		return response()->streamDownload(function() use ($writer){
					$writer->save('php://output');
				}, $prefix ."_" .Date("YmdHis") .'.xlsx',
				['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
	}
	public function userbayar(Request $request)
  {
		if(!auth()->user()->can('pembayaran.transaksi')){
			abort(403, 'User does not have the right roles.');
		}
		$id = $request->id;
    $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman Pembayaran");

		$dtCustomer = Transaksi::getCustomer();
		$dtBank = Transaksi::getBank();
		$dtMataUang = Transaksi::getMataUang();
		$dtRekening = Transaksi::getRekening();
		$dtTOP = Transaksi::getTOP();
		$dtShipper = Transaksi::getShipper();
		$dtImportir = Transaksi::getImportir();
		$dtPenerima = Transaksi::getPenerima();
		Transaksi::calculateBayar($id);
		$dtTransaksi = Array();
		$dtTransaksi = Transaksi::getTransaksiBayar($id);
		$notransaksi = " No. " .sprintf('%08d', $dtTransaksi["header"]->ID);
		$data = [
				"header" => $dtTransaksi["header"], "breads" => $breadcrumb,
				"idtransaksi" => $id,"datamatauang" => $dtMataUang,
				"notransaksi" => $notransaksi, "datatop" => $dtTOP, "dataimportir" => $dtImportir,
				"datashipper" => $dtShipper, "datacustomer" => $dtCustomer,
				"databank" => $dtBank, "datarekening" => $dtRekening,
				"datapenerima" => $dtPenerima,
				"detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
			];

		return view("transaksi.transaksibayar", $data);
	}
	public function search()
  {
		if(!auth()->user()->can('schedule.cari')){
			abort(403, 'User does not have the right roles.');
		}
    $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Cari Transaksi");
		$kantor = Transaksi::getKantor();
		$customer = Transaksi::getCustomer();

		return view("transaksi.search",["breads" => $breadcrumb,
									"kodekantor" => $kantor, "customer" => $customer]);
	}
	/*
	public function searchproduk()
  {
		if(!auth()->user()->can('cari_produk')){
			abort(403, 'User does not have the right roles.');
		}
    $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Cari Produk");
		return view("transaksi.searchbarang",["breads" => $breadcrumb]);
	}
	*/
	public function browse()
  {
		if(!auth()->user()->can('schedule.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Browse Schedule");
		$kantor = Transaksi::getKantor();
		$customer = Transaksi::getCustomer();
		$importir =Transaksi::getImportir();

		return view("transaksi.browse",["breads" => $breadcrumb,
									"datakantor" => $kantor, "datacustomer" => $customer,
									"dataimportir" => $importir,
									"datakategori" => Array("Tanggal Tiba","Tanggal Keluar",
															"Tanggal Nopen", "Tanggal SPPB")]);
	}
	public function perekamanvo(Request $request)
  {
		if(!auth()->user()->can('vo.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$canViewCustomer = auth()->user()->can('customer.view');
		$filter = $request->input('filter');
		if ($filter && $filter == "1"){
			$postKantor = $request->input("kantor");
			$postKategori = $request->input("kategori");
			$postCustomer = $request->input("customer");
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$postKategori2 = $request->input("kategori2");
			$dari2 = $request->input("dari2");
			$sampai2 = $request->input("sampai2");
			$postKategori3 = $request->input("kategori3");
			$dari3 = $request->input("dari3");
			$sampai3 = $request->input("sampai3");

			$data = Transaksi::browseVo($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2,
									$postKategori3, $dari3, $sampai3);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', Transaksi::getKantor($postKantor)->URAIAN);
					}
					else {
					    $sheet->setCellValue('C1', "Semua");
					}
					$row = 2;
					if ($canViewCustomer){
							$sheet->setCellValue('A2', 'CUSTOMER');
							if ($postCustomer && trim($postCustomer) != ""){
								$sheet->setCellValue('C2', Transaksi::getCustomer($postCustomer)->nama_customer);
							}
							else {
								$sheet->setCellValue('C2', "Semua");
							}
							$row += 1;
					}
					$sheet->setCellValue('A' .strval($row), 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C' .strval($row), Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C' .strval($row), "Semua");
					}
					$lastrow = $row;
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						if ($postKategori1 == "Status VO"){
							if ($isikategori1 != ""){
							    if ($isikategori1 == "K"){
							        $isikategori1 = "Konfirmasi";
							    }
							    else if ($isikategori1 == "B"){
							        $isikategori1 = "Belum Inspect";
							    }
							    else if ($isikategori1 == "S"){
							        $isikategori1 = "Sudah Inspect";
							    }
							    else if ($isikategori1 == "R"){
							        $isikategori1 = "Revisi FD";
							    }
							    else if ($isikategori1 == "F"){
							        $isikategori1 = "FD";
							    }
							    else if ($isikategori1 == "L"){
							        $isikategori1 = "LS Terbit";
							    }
							}
						}
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					if ($postKategori2 && trim($postKategori2) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori2);
						$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
					}
					if ($postKategori3 && trim($postKategori3) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori3);
						$sheet->setCellValue('C' .$lastrow, $dari3 == "" ? "-" : Date("d M Y", strtotime($dari3)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai3 == "" ? "-" : Date("d M Y", strtotime($sampai3)));
					}
					$lastrow += 2;
					$colNo = 0;
					$columns = 'ABCDEFGHIJKLM';
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Ktr');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Importir');
					if ($canViewCustomer){
							$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Customer');
					}
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No Inv');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No. VO');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl VO');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Tiba');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Nopen');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Kode HS');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Periksa');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl LS');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Status VO');

					foreach ($data as $dt){
						$lastrow += 1;
						$colNo = 0;
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->KANTOR);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->IMPORTIR);
						if ($canViewCustomer){
								$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CUSTOMER);
						}
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_INV);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_VO);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLVO);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLTIBA);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOPEN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->KODE_HS_VO);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLPERIKSAVO);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLLS);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->STATUSVO);
					}
					return $this->exportXls($spreadsheet, "browse_vo");
				}
				else {
					return response()->json($data);
				}
			}
			else {

				return response()->json([]);
			}
		}
		else {
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Perekaman VO");
			$kantor = Transaksi::getKantor();
			$customer = Transaksi::getCustomer();
			$importir = $this->getImportir();
			return view("transaksi.perekamanvo",["breads" => $breadcrumb,
										"datakantor" => $kantor, "datacustomer" => $customer,
										"dataimportir" => $importir,
										"datakategori1" => Array("No Inv","No VO",
																"Status VO"),
										"datakategori2" => Array("Tanggal Periksa", "Tanggal LS", "Tanggal VO","Tanggal Nopen","Tanggal Tiba"),
										"datakategori3" => Array("Tanggal Periksa", "Tanggal LS", "Tanggal VO","Tanggal Nopen","Tanggal Tiba")
										]);
		}
	}
	public function perekamanbayar(Request $request)
  {
		if(!auth()->user()->can('pembayaran.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$canViewCustomer = auth()->user()->can('customer.view');
		$filter = $request->input("filter");
		if ($filter && $filter == "1"){
			$postKantor = $request->input("kantor");
			$postKategori = $request->input("kategori");
			$postCustomer = $request->input("customer");
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$postKategori2 = $request->input("kategori2");
			$dari2 = $request->input("dari2");
			$sampai2 = $request->input("sampai2");

			$data = Transaksi::browseBayar($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2,$dari2, $sampai2);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){

					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', Transaksi::getKantor($postKantor)->URAIAN);
					}
					else {
					    $sheet->setCellValue('C1', "Semua");
					}
					$row = 2;
					if ($canViewCustomer){
							$sheet->setCellValue('A2', 'CUSTOMER');
							if ($postCustomer && trim($postCustomer) != ""){
								$sheet->setCellValue('C2', Transaksi::getCustomer($postCustomer)->nama_customer);
							}
							else {
								$sheet->setCellValue('C2', "Semua");
							}
							$row += 1;
					}
					$sheet->setCellValue('A' .strval($row), 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C' .strval($row), Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C' .strval($row), "Semua");
					}
					$lastrow = $row;
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						if ($postKategori1 == "Term of Payment"){
							if ($isikategori1 != ""){
							    if ($isikategori1 == "K"){
							        $isikategori1 = "Konfirmasi";
							    }
							    else if ($isikategori1 == "B"){
							        $isikategori1 = "Belum Inspect";
							    }
							    else if ($isikategori1 == "S"){
							        $isikategori1 = "Sudah Inspect";
							    }
							    else if ($isikategori1 == "R"){
							        $isikategori1 = "Revisi FD";
							    }
							    else if ($isikategori1 == "F"){
							        $isikategori1 = "FD";
							    }
							    else if ($isikategori1 == "L"){
							        $isikategori1 = "LS Terbit";
							    }
							}
						}
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					if ($postKategori2 && trim($postKategori2) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori2);
						$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
					}
					$lastrow += 2;
					$columns = "ABCDEFGHIJKLM";
					$colNo = 0;
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Importir');
					if ($canViewCustomer){
							$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Customer');
					}
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No Inv');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No Aju');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jml Kmsn');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'TT/Non TT');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'TOP');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jth Tempo');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Curr');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'CIF');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Nominal');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Saldo');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Faktur');

					foreach ($data as $dt){
						$lastrow += 1;
						$colNo = 0;
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->IMPORTIR);
						if ($canViewCustomer){
								$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CUSTOMER);
						}
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_INV);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOAJU);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JUMLAH_KEMASAN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->PEMBAYARAN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TERM);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLJTHTEMPO);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->MATAUANG);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CIF);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->BAYAR);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CIF - $dt->BAYAR);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->FAKTUR);
						$detail = Transaksi::getDetailBayar($dt->ID);
						if (count($detail) > 0){
							$lastrow += 2;
							$sheet->setCellValue('A' .$lastrow, "NO PPU");
							$sheet->setCellValue('B' .$lastrow, "MATA UANG");
							$sheet->setCellValue('C' .$lastrow, "KURS");
							$sheet->setCellValue('D' .$lastrow, "NOMINAL");
							$sheet->setCellValue('E' .$lastrow, "RUPIAH");
							$sheet->setCellValue('F' .$lastrow, "TGL BAYAR");
							foreach ($detail as $det){
								$lastrow += 1;
								$sheet->setCellValue('A' .$lastrow, $det->NO_PPU);
								$sheet->setCellValue('B' .$lastrow, $det->MATAUANG);
								$sheet->setCellValue('C' .$lastrow, $det->KURS);
								$sheet->setCellValue('D' .$lastrow, $det->NOMINAL);
								$sheet->setCellValue('E' .$lastrow, $det->RUPIAH);
								$sheet->setCellValue('F' .$lastrow, $det->TGLBAYAR);
							}
							$lastrow += 1;
						}
					}

					return $this->exportXls($spreadsheet, "browse_bayar");
				}
				else {

					return response()->json($data);
				}
			}
			else {

				return response()->json([]);
			}
		}
		else {
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Perekaman Pembayaran");
			$kantor = Transaksi::getKantor();
			$customer = Transaksi::getCustomer();
			$importir = $this->getImportir();
			$top = Transaksi::getTOP();

			return view("transaksi.perekamanbayar",["breads" => $breadcrumb,
										"datakantor" => $kantor, "datacustomer" => $customer,
										"dataimportir" => $importir,"top" => $top,
										"datakategori1" => Array("No Inv","TOP","TT/Non TT"),
										"datakategori2" => Array("Tanggal Jatuh Tempo")
										]);
		}
	}
	public function perekamando(Request $request)
  {
		if(!auth()->user()->can('dokumen.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		$canViewCustomer = auth()->user()->can('customer.view');
		if ($filter && $filter == "1"){
			$postKantor = $request->input("kantor");
			$postCustomer = $request->input("customer");
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$postKategori2 = $request->input("kategori2");
			$dari2 = $request->input("dari2");
			$sampai2 = $request->input("sampai2");
			$postKategori3 = $request->input("kategori3");
			$dari3 = $request->input("dari3");
			$sampai3 = $request->input("sampai3");

			$data = Transaksi::browseDo($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2, $postKategori3, $dari3, $sampai3);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');

					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', Transaksi::getKantor($postKantor)->URAIAN);
					}
					else {
					    $sheet->setCellValue('C1', "Semua");
					}
					$row = 2;
					if (auth()->user()->can('customer.view')){
							$sheet->setCellValue('A2', 'CUSTOMER');
							if ($postCustomer && trim($postCustomer) != ""){
								$sheet->setCellValue('C2', Transaksi::getCustomer($postCustomer)->nama_customer);
							}
							else {
								$sheet->setCellValue('C2', "Semua");
							}
							$row += 1;
					}
					$sheet->setCellValue('A' .strval($row), 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C' .strval($row), Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C' .strval($row), "Semua");
					}
					$lastrow = $row;
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					if ($postKategori2 && trim($postKategori2) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori2);
						$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
					}
					if ($postKategori3 && trim($postKategori3) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori3);
						$sheet->setCellValue('C' .$lastrow, $dari3 == "" ? "-" : Date("d M Y", strtotime($dari3)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai3 == "" ? "-" : Date("d M Y", strtotime($sampai3)));
					}

					$lastrow += 2;
					$columns = "ABCDEFGHIJKLMN";
					$colNo = 0;
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Importir');
					if ($canViewCustomer){
							$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Customer');
					}
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No Inv');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No PO');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No SC');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No BL');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl BL');
			    $sheet->setCellValue($columns[$colNo++] .$lastrow, 'No Aju');
			    $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Nopen');
			    $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Tiba');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No. Form');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl LS');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Dok Trm');

					foreach ($data as $dt){
						$lastrow += 1;
						$colNo = 0;
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->IMPORTIR);
						if ($canViewCustomer){
								$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CUSTOMER);
						}
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_INV);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_PO);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_SC);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_BL);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLBL);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOAJU);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOPEN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLTIBA);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_FORM);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLLS);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLDOKTRM);
					}
					return $this->exportXls($spreadsheet, "browse_do");
				}
				else {
					return response()->json($data);
				}
			}
			else {

				return response()->json([]);
			}
		}
		else {
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Perekaman Dokumen");
			$kantor = Transaksi::getKantor();
			$customer = Transaksi::getCustomer();
			$importir = Transaksi::getImportir();
			return view("transaksi.perekamando",["breads" => $breadcrumb,
										"datakantor" => $kantor, "datacustomer" => $customer,
										"dataimportir" => $importir,
										"datakategori1" => Array("No Inv","No BL","No VO", "Nopen","No Aju"),
										"datakategori2" => Array("Tanggal BL","Tanggal Tiba", "Tanggal Nopen", "Tgl Dok Terima")
										]);
		}
	}
	public function filter(Request $request)
	{
		$postKantor = $request->input("kantor");
		$postCustomer =$request->input("customer");
		$postImportir = $request->input("importir");
		$postKategori1 = $request->input("kategori1");
		$dari1 = $request->input("dari1");
		$sampai1 = $request->input("sampai1");
		$postKategori2 = $request->input("kategori2");
		$dari2 = $request->input("dari2");
		$sampai2 = $request->input("sampai2");
		$export = $request->input("export");

		$data = Transaksi::browse($postKantor, $postCustomer, $postImportir, $postKategori1,
								$dari1, $sampai1, $postKategori2, $dari2, $sampai2);
		$canViewCustomer = auth()->user()->can('customer.view');
		if ($data){
			if ($export == '1'){
				$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setCellValue('A1', 'KANTOR');
				if ($postKantor && trim($postKantor) != ""){
					$sheet->setCellValue('C1', Transaksi::getKantor($postKantor)->URAIAN);
				}
				else {
					$sheet->setCellValue('C1', "Semua");
				}
				$row = 2;
				if ($canViewCustomer){
						$sheet->setCellValue('A2', 'CUSTOMER');
						if ($postCustomer && trim($postCustomer) != ""){
							$sheet->setCellValue('C2', Transaksi::getCustomer($postCustomer)->nama_customer);
						}
						else {
							$sheet->setCellValue('C2', "Semua");
						}
						$row += 1;
				}
				$sheet->setCellValue('A' .strval($row), 'IMPORTIR');
				if ($postImportir && trim($postImportir) != ""){
					$sheet->setCellValue('C'.strval($row), Transaksi::getImportir($postImportir)->NAMA);
				}
				else {
					$sheet->setCellValue('C'.strval($row), "Semua");
				}
				$lastrow = $row;
				if ($postKategori1 && trim($postKategori1) != ""){
					$lastrow += 1;
					$sheet->setCellValue('A' .$lastrow, $postKategori1);
					$sheet->setCellValue('C' .$lastrow, $dari1 == "" ? "-" : Date("d M Y", strtotime($dari1)));
					$sheet->setCellValue('D' .$lastrow, "sampai");
					$sheet->setCellValue('E' .$lastrow, $sampai1 == "" ? "-" : Date("d M Y", strtotime($sampai1)));
				}
				if ($postKategori2 && trim($postKategori2) != ""){
					$lastrow += 1;
					$sheet->setCellValue('A' .$lastrow, $postKategori2);
					$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
					$sheet->setCellValue('D' .$lastrow, "sampai");
					$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
				}
				$lastrow += 2;
				$columns = 'ABCDEFGHIJKLMN';
				$colNo = 0;
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Kd Kantor');
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No. Inv');
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No. BL');
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jml Kmsn');
				if ($canViewCustomer){
						$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Customer');
				}
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Importir');
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Tiba');
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl SPPB');
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Keluar');
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Masuk');
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No.Aju');
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Nopen');
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Nopen');
				/*
				$sheet->setCellValue('K' .$lastrow, 'No. PO');
				$sheet->setCellValue('L' .$lastrow, 'No. SC');
				*/
				$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jml Kontainer');

				foreach ($data as $dt){
					$lastrow += 1;
					$colNo = 0;
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->KODEKANTOR);
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_INV);
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_BL);
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JUMLAH_KEMASAN);
					if ($canViewCustomer){
							$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NAMA);
					}
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->IMPORTIR);
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLTIBA);
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLSPPB);
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLKELUAR);
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLMASUK);
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOAJU);
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOPEN);
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLNOPEN);
					/*
					$sheet->setCellValue('K' .$lastrow, $dt->NO_PO);
					$sheet->setCellValue('L' .$lastrow, $dt->NO_SC);
					*/
					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JUMLAH_KONTAINER);
				}

				return $this->exportXls($spreadsheet, "browse");
			}
			else {
				return response()->json($data);
			}
		}
		else {
			if ($export == '1'){
				return response()->json(["message" => "Tidak ada data yang dieksport"]);
			}
			else {
				return response()->json([]);
			}
		}
	}
	public function find(Request $request)
	{
		$term = $request->input("term");
		$searchtype = $request->input("searchtype");

		$data = Transaksi::search($term,$searchtype);
        return response()->json($data);
	}
	public function findproduk(Request $request)
	{
		$hscode = $request->input("hscode");
		$rangefrom = $request->input("rangefrom");
		$rangeto = $request->input("rangeto");

		$data = DB::table("produk")->select("id");
		if ($hscode){
			$data = $data->where("hscode","like", "%{$hscode}%");
		}
		if ($rangefrom){
			$data = $data->where("harga",">=", str_replace(",", "", $rangefrom));
		}
		if ($rangeto){
			$data = $data->where("harga","<=", str_replace(",", "", $rangeto));
		}
		return $data->get();
	}
	public function searchsptnp(Request $request)
	{
		$nopen = $request->input("nopen");
		$tglnopen = $request->input("tglnopen");

		$data = Transaksi::searchsptnp($nopen,$tglnopen);

        return response()->json($data);
	}
	public function searchkontainer()
	{
		if(!auth()->user()->can('schedule.carikontainer')){
			abort(403, 'User does not have the right roles.');
		}
    $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Cari Transaksi Berdasarkan Nomor Kontainer");

		return view("transaksi.searchkontainer",["breads" => $breadcrumb]);
	}
	/*
	public function perekamansptnp()
	{

        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman SPTNP");

		$assets["scripts"][] = "/web/assets/app/sptnp.js";
		return view("transaksi.sptnp",["breads" => $breadcrumb,
									"stylesheets" => $assets["stylesheets"],
									"scripts" => $assets["scripts"]]);
	}
	*/
	public function browsesptnp(Request $request)
  {
		if(!auth()->user()->can('sptnp.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		if ($filter && $filter == "1"){
			$postKantor = $request->input("kantor");
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$postKategori2 = $request->input("kategori2");
			$dari2 = $request->input("dari2");
			$sampai2 = $request->input("sampai2");
			$postKategori3 = $request->input("kategori3");
			$dari3 = $request->input("dari3");
			$sampai3 = $request->input("sampai3");

			$data = Sptnp::browseSPTNP($postKantor, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2, $postKategori3, $dari3, $sampai3);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', Transaksi::getKantor($postKantor)->URAIAN);
					}
					else {
					    $sheet->setCellValue('C1', "Semua");
					}
					$sheet->setCellValue('A2', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C2', Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C2', "Semua");
					}
					$lastrow = 2;
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					if ($postKategori2 && trim($postKategori2) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori2);
						$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
					}
					$lastrow += 1;
					$sheet->setCellValue('A' .$lastrow, 'Kantor');
					$sheet->setCellValue('B' .$lastrow, 'Importir');
			    $sheet->setCellValue('C' .$lastrow, 'No Aju');
					$sheet->setCellValue('D' .$lastrow, 'No SPTNP');
					$sheet->setCellValue('E' .$lastrow, 'Tgl SPTNP');
			    $sheet->setCellValue('F' .$lastrow, 'Nopen');
			    $sheet->setCellValue('G' .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue('H' .$lastrow, 'BM');
			    $sheet->setCellValue('I' .$lastrow, 'BMT');
					$sheet->setCellValue('J' .$lastrow, 'PPN');
					$sheet->setCellValue('K' .$lastrow, 'PPNBm');
					$sheet->setCellValue('L' .$lastrow, 'PPH 22');
					$sheet->setCellValue('M' .$lastrow, 'Denda');
					$sheet->setCellValue('N' .$lastrow, 'Total');
					$sheet->setCellValue('O' .$lastrow, 'Jns Notul');
					$sheet->setCellValue('P' .$lastrow, 'Jth Tempo');
					$sheet->setCellValue('Q' .$lastrow, 'Tgl Lunas');
					$sheet->setCellValue('R' .$lastrow, 'Tgl BRT');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->KODEKANTOR);
						$sheet->setCellValue('B' .$lastrow, $dt->NAMAIMPORTIR);
						$sheet->setCellValue('C' .$lastrow, $dt->NOAJU);
						$sheet->setCellValue('D' .$lastrow, $dt->NO_SPTNP);
						$sheet->setCellValue('E' .$lastrow, $dt->TGLSPTNP);
						$sheet->setCellValue('F' .$lastrow, $dt->NOPEN);
						$sheet->setCellValue('G' .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue('H' .$lastrow, $dt->BMTB);
						$sheet->setCellValue('I' .$lastrow, $dt->BMTTB);
						$sheet->setCellValue('J' .$lastrow, $dt->PPNTB);
						$sheet->setCellValue('K' .$lastrow, $dt->PPNBM);
						$sheet->setCellValue('L' .$lastrow, $dt->PPHTB);
						$sheet->setCellValue('M' .$lastrow, $dt->DENDA_TB);
						$sheet->setCellValue('N' .$lastrow, $dt->TOTAL_TB);
						$sheet->setCellValue('O' .$lastrow, $dt->JENIS_SPTNP);
						$sheet->setCellValue('P' .$lastrow, $dt->TGLJTHTEMPOSPTNP);
						$sheet->setCellValue('Q' .$lastrow, $dt->TGLLUNAS);
						$sheet->setCellValue('R' .$lastrow, $dt->TGLBRT);
					}

					$writer = new Xlsx($spreadsheet);
					return response()->streamDownload(function() use ($writer){
							$writer->save('php://output');
						}, 'sptnp_' .Date("YmdHis") .'.xlsx',
						['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

				}
				else {
					return response()->json($data);
				}
			}
			else {
				return response()->json([]);
			}
		}
		else {

			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Browse SPTNP");
			$kantor = Transaksi::getKantor();
			$importir = Transaksi::getImportir();
			return view("transaksi.perekamansptnp",["breads" => $breadcrumb,
										"datakantor" => $kantor, "dataimportir" => $importir,
										"datakategori1" => Array("Nopen","No SPTNP","Jenis Notul"),
										"datakategori2" => Array("Tanggal Nopen", "Tanggal Lunas", "Tanggal Jatuh Tempo","Tanggal SPTNP", "Tanggal BRT"),
										"datakategori3" => Array("Tanggal Nopen", "Tanggal Lunas", "Tanggal Jatuh Tempo","Tanggal SPTNP", "Tanggal BRT")
										]);
		}
	}

	public function get_daftar(Request $request)
	{
		$dataSource = DB::table(DB::raw("tbl_penarikan_header h"))
										->selectRaw("h.id, no_bl, DATE_FORMAT(tgl_tiba,'%d-%m-%Y') AS tgl_tiba,"
																."FORMAT(jumlah_kemasan, '###,###,###') AS jumlah_kemasan,"
																."noaju, nopen, DATE_FORMAT(tgl_nopen,'%d-%m-%Y') AS tgl_nopen,"
																."nama_customer, DATE_FORMAT(tgl_keluar,'%d-%m-%Y') AS tgl_keluar, no_inv,"
																."no_form, no_po")
										->leftJoin(DB::raw("plbbandu_app15.tb_customer c"), "c.id_customer","=", "h.customer")
										->whereRaw("USERGUDANG IS NULL");
		$user = auth()->user();
		if ($user->hasRole('impor_company') || $user->hasRole('company')){
				$company = $user->hasCompany();
				$dataSource = $dataSource->where("IMPORTIR", $company->id);
		}
		$dataTable = datatables()->of($dataSource);
		if (isset($request->input('columns')[0]['search']['value'])){
			$dataTable = $dataTable->filterColumn('id', function($query, $keyword) {
				$keyword = json_decode($keyword, true);
				foreach($keyword as $value){
					$query->orWhere("id", $value);
				}
			});
		}
		return $dataTable->toJson();
	}
	public function daftarproduk(Request $request)
	{
		if(!auth()->user()->can('master.produk.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$dataSource = DB::table('produk')->select("produk.id","produk.kode","nama","hscode","satuan.satuan","harga")
						->leftJoin("satuan", "produk.satuan_id","=","satuan.id");

		$dataTable = datatables()->of($dataSource);
		if (isset($request->input('columns')[0]['search']['value'])){
			$dataTable = $dataTable->filterColumn('kode', function($query, $keyword) {
				$keyword = json_decode($keyword, true);
				foreach($keyword as $value){
					$query->orWhere("produk.id", $value);
				}
			});
		}
		return $dataTable->toJson();
	}
	public function crud(Request $request)
	{
		$postheader = $request->input("header");
		$type = $request->input("type");
		$message = Array();
		DB::beginTransaction();
		try {
			if (!$type){
				if ($postheader){
					$kontainer = $request->input("kontainer");
					$detail = $request->input("detail");
					parse_str($postheader, $header);
					if ($header["idtransaksi"] == ""){
						$action = "insert";
					}
					else {
						$action = "update";
					}
					$id = Transaksi::saveTransaksi($action, $header, $kontainer, $detail);
				}
				else {
					$id = $request->input("delete");
					if ($id && $id != ""){
						Transaksi::deleteTransaksi($id);
					}
				}
			}
			else if ($type == "bayar"){
				if ($postheader){
					$detail = $request->input("detail");
					parse_str($postheader, $header);
					if ($header["idtransaksi"] == ""){
						$action = "insert";
					}
					else {
						$action = "update";
					}
					$id = Transaksi::saveTransaksiBayar($action, $header, $detail);
				}
				else {
					$id = $request->input("delete");
					if ($id && $id != ""){
						Transaksi::deleteTransaksiBayar($id);
					}
				}
			}
			else if ($type == "deliveryorder"){
				if ($postheader){
					$detail = $request->input("detail");
					$pengeluaran = $request->input("pengeluaran");
					parse_str($postheader, $header);
					if ($header["idtransaksi"] == ""){
						$action = "insert";
					}
					else {
						$action = "update";
					}
					$id = Transaksi::saveTransaksiDOrder($action, $header, $detail, $pengeluaran);
				}
				else {
					$id = $request->input("delete");
					if ($id && $id != ""){
						Transaksi::deleteTransaksiDOrder($id);
					}
				}
			}
			else if ($type == "userquota"){
				if ($postheader){
					$detail = $request->input("detail");
					$files = $request->input('files');
					parse_str($postheader, $header);
					if ($header["idtransaksi"] == ""){
						$action = "insert";
					}
					else {
						$action = "update";
					}
					$id = Transaksi::saveTransaksiQuota($action, $header, $detail, $files);
				}
				else {
					$id = $request->input("delete");
					if ($id && $id != ""){
						Transaksi::deleteTransaksiQuota($id);
					}
				}
			}
			else if ($type == "pengeluaran"){
				if ($postheader){
					$id = $request->input("do_id");
					Transaksi::savePengeluaran($id, $postheader);
				}
			}
			else if ($type == "userdo"){
				if ($postheader){
					parse_str($postheader, $header);
					$postfiles = $request->input('files');
					$id = Transaksi::saveTransaksiDo($header, $postfiles);
				}
			}
			else if ($type == "barang"){
				if ($postheader){
					$detail = $request->input("detail");
					parse_str($postheader, $header);
					//$postfiles = $request->input('files');
					$id = Transaksi::saveTransaksiBarang($header, $detail /*, $postfiles*/);
				}
			}
			else if ($type == "konversi"){
				if ($postheader){
					$detail = $request->input("detail");
					parse_str($postheader, $header);
					$id = Transaksi::saveTransaksiKonversi($header, $detail);
				}
			}
			else if ($type == "mutasi"){
				if ($postheader){
					$detail = $request->input("detail");
					parse_str($postheader, $header);
					$postfiles = $request->input('files');
					$id = Transaksi::saveMutasiBarang($header, $detail, $postfiles);
				}
			}
			else if ($type == "uservo"){
				if ($postheader){
					parse_str($postheader, $header);
					$postDetail = $request->input("detail");
					$id = Transaksi::saveTransaksiVo($header, $postDetail);
				}
			}
			else if ($type == "usersptnp"){
				if ($postheader){
					parse_str($postheader, $header);
					$id = Sptnp::saveTransaksiSPTNP($header);
				}
			}
			else if ($type == "userbc"){
				if ($postheader){
					parse_str($postheader, $header);
					$id = Transaksi::saveTransaksiBC($header);
				}
			}
			DB::commit();
			$message["result"] = $id;
		}
		catch (\Exception $e){
			DB::rollback();
			$message["error"] = $e->getMessage();
		}

		return response()->json($message);
	}
	public function delete(Request $request)
	{
		if(!auth()->user()->can('schedule.hapus')){
			abort(403, 'User does not have the right roles.');
		}
		$id = $request->input("iddelete");
		$message = Array();
		if ($id && $id != ""){
			DB::beginTransaction();
			try {
				Transaksi::deleteTransaksi($id);
				$message["result"] = $id;
				DB::commit();
			}
			catch (Exception $e){
				$message["error"] = $e->getMessage();
				DB::rollBack();
			}
		}
    return response()->json($message);
	}
	public function upload(Request $request)
	{
		$file = $request->file('file');
		$type = $request->input("filetype");
		if (!$type){
		    $type = 0;
		}
		if ($file->isValid()){
			$realname = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			DB::beginTransaction();
			try {
				$id = Transaksi::saveFile($realname, $extension, $type);
				$file->move(storage_path() ."/uploads", $id."." .$extension);
				DB::commit();
				return response($id);
			}
			catch (Exception $e){
				DB::rollBack();
			}
		}
	}
	public function removeFile(Request $request)
	{
		$id = $request->input("id");
		DB::transaction(function() use ($id) {
			Transaksi::deleteFile($id);
		});
	}
	public function getFile(Request $request)
	{
		$file = $request->input("file");
		$dtFile = DB::table("tbl_files")
					 ->select("FILENAME")
					 ->where("ID", $file);
		if ($dtFile->count() > 0){
			$dtFile = $dtFile->first();
			$file = storage_path() .'/uploads/' .$dtFile->FILENAME;
			return response()->download($file, $dtFile->FILENAME);
		}
	}
	public function getPerekamanFiles(Request $request)
	{
		$perekaman_id = $request->input("id");
		$dtFile = DB::table("tbl_files")
					->where("ID_HEADER", $perekaman_id)->get();
		echo json_encode($dtFile);
	}
	public function cron(Request $request)
	{
		$action = $request->input('action');
		if ($action == 'deletefiles'){
			$data = DB::table("tbl_files")
					  ->select("FILENAME")
					  ->whereRaw("ID_HEADER IS NULL")
					  ->get();
			foreach ($data as $dt){
				unlink(storage_path() ."/uploads/" .$dt->FILENAME);
			}
			Transaksi::whereRaw("ID_HEADER IS NULL")->delete();
		}
	}
	public function searchinv(Request $request)
	{
		$inv = $request->input("inv");
		$data = Transaksi::getInv($inv);
		if (!$data){
			$response["error"] = "No Inv tidak ada";
		}
		else {
			$response = $data;
		}
		return response()->json($response);
	}
	public function detailbayar(Request $request)
	{
		$id = $request->input("id");
		$data = Transaksi::getDetailBayar($id);
		if (!$data){
			$response["data"] = [];
		}
		else {
			$response["data"] = $data;
		}

		return response()->json($response);
	}
	public function konversi(Request $request)
	{
		$id = $request->input("id");
		$data = Transaksi::getKonversi($id);
		if (!$data){
			$response["data"] = [];
		}
		else {
			$response["data"] = $data;
		}
		return response()->json($response);
	}
	public function kartuHutang(Request $request)
  {
		if(!auth()->user()->can('kartu_hutang')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		$canViewCustomer = auth()->user()->can('customer.view');
		if ($filter && $filter == "1"){
			$postKantor = $request->input("kantor");
			$postKategori = $request->input("kategori");
			$postCustomer = $request->input("customer");
      $postImportir = $request->input("importir");
      $postShipper = $request->input("shipper");
			//$postKategori1 = $request->input("kategori1");
			//$isikategori1 = $request->input("isikategori1");
			$postKategori2 = $request->input("kategori2");
			$dari2 = $request->input("dari2");
			$sampai2 = $request->input("sampai2");
			$data = Transaksi::kartuHutang($postKantor, $postCustomer, $postImportir, $postShipper,
									$postKategori2,$dari2, $sampai2);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();
          $sheet->setCellValue('A1', 'KANTOR');
          if ($postKantor && trim($postKantor) != ""){
              $sheet->setCellValue('A1', Transaksi::getKantor($postKantor)->URAIAN);
          }
          else {
              $sheet->setCellValue('C1', "Semua");
          }
					$row = 2;
					if ($canViewCustomer){
							$sheet->setCellValue('A2', 'CUSTOMER');
							if ($postCustomer && trim($postCustomer) != ""){
								$sheet->setCellValue('C2', Transaksi::getCustomer($postCustomer)->nama_customer);
							}
							else {
								$sheet->setCellValue('C2', "Semua");
							}
							$row += 1;
					}
					$sheet->setCellValue('A' .strval($row), 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C' .strval($row), Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C' .strval($row), "Semua");
          }
          $sheet->setCellValue('A' .strval($row), 'SHIPPER');
					if ($postShipper && trim($postShipper) != ""){
						$sheet->setCellValue('C' .strval($row), Transaksi::getShipper($postShipper)->nama_pemasok);
					}
					else {
						$sheet->setCellValue('C' .strval($row), "Semua");
					}
          $lastrow = $row;
                    /*
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						if ($postKategori1 == "Term of Payment"){
							if ($isikategori1 != ""){
							    if ($isikategori1 == "K"){
							        $isikategori1 = "Konfirmasi";
							    }
							    else if ($isikategori1 == "B"){
							        $isikategori1 = "Belum Inspect";
							    }
							    else if ($isikategori1 == "S"){
							        $isikategori1 = "Sudah Inspect";
							    }
							    else if ($isikategori1 == "R"){
							        $isikategori1 = "Revisi FD";
							    }
							    else if ($isikategori1 == "F"){
							        $isikategori1 = "FD";
							    }
							    else if ($isikategori1 == "L"){
							        $isikategori1 = "LS Terbit";
							    }
							}
						}
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
                    }
                    */
					if ($postKategori2 && trim($postKategori2) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori2);
						$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
					}
          $lastrow += 2;
					$columns = "ABCDEFGHIJKLMNOPQRSTU";
					$colNo = 0;
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'No');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Kantor');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Importir');
					if ($canViewCustomer){
          		$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Customer');
					}
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Shipper');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jns Dok');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Nopen');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No Inv');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Inv');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jth Tempo');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Curr');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'CIF');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Payment');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Saldo');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, "NO PPU");
					$sheet->setCellValue($columns[$colNo++] .$lastrow, "MATA UANG");
					$sheet->setCellValue($columns[$colNo++] .$lastrow, "KURS");
					$sheet->setCellValue($columns[$colNo++] .$lastrow, "NOMINAL");
					$sheet->setCellValue($columns[$colNo++] .$lastrow, "RUPIAH");
					$sheet->setCellValue($columns[$colNo++] .$lastrow, "TGL BAYAR");

          $lastrow += 1;
          $no = 0;
					foreach ($data as $dt){
						$detail = Transaksi::getDetailBayar($dt->ID);
						$no += 1;
						if (count($detail) > 0){
              $lastrow += 1;
							foreach ($detail as $det){
									$colNo = 0;
							    $sheet->setCellValue($columns[$colNo++] .$lastrow, $no);
                  $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->KANTOR);
      						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->IMPORTIR);
									if ($canViewCustomer){
                  		$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CUSTOMER);
									}
                  $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->SHIPPER);
                  $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JENISDOKUMEN);
                  $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOPEN);
                  $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLNOPEN);
                  $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_INV);
                  $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLINV);
	    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLJTHTEMPO);
	    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->MATAUANG);
	    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CIF);
	    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TOT_PAYMENT);
	    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->SALDO);
									$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->NO_PPU);
									$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->MATAUANG);
									$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->KURS);
									$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->NOMINAL);
									$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->RUPIAH);
									$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->TGLBAYAR);
									$lastrow += 1;
							}
							$lastrow += 1;
						}
						else {
							$colNo = 0;
					    $sheet->setCellValue($columns[$colNo++] .$lastrow, $no);
              $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->KANTOR);
  						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->IMPORTIR);
              $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CUSTOMER);
              $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->SHIPPER);
              $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JENISDOKUMEN);
              $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOPEN);
              $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLNOPEN);
              $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_INV);
              $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLINV);
  						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLJTHTEMPO);
  						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->MATAUANG);
  						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CIF);
  						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TOT_PAYMENT);
  						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->SALDO);
  						$lastrow += 1;
						}
					}
					return $this->exportXls($spreadsheet, "kartu_hutang");
				}
				else {
					return response()->json($data);
				}
			}
			else {

				return response()->json([]);
			}
		}
		else {
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Kartu Hutang");
			$kantor = Transaksi::getKantor();
			$customer = Transaksi::getCustomer();
      $importir = Transaksi::getImportir();
      $shipper = Transaksi::getShipper();

			return view("transaksi.kartuhutang",["breads" => $breadcrumb,
                                        "datakantor" => $kantor, "datacustomer" => $customer,
                                        "datashipper" => $shipper,
										"dataimportir" => $importir,
										//"datakategori1" => Array("No Inv","TOP","TT/Non TT"),
										"datakategori2" => Array("Tgl Jatuh Tempo","Tgl Inv","Tgl Nopen")
										]);
		}
	}
	public function userbarang(Request $request)
  {
		if(!auth()->user()->can('barang.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$id = $request->id;
		if (trim($id) == ""){
				abort(404,'Data tidak ada');
		}

		$dtTransaksi = Array();
		if ($id != ""){
			$dtTransaksi = Transaksi::getTransaksiBarang($id);
			if (!$dtTransaksi){
					abort(404, 'Data tidak ada');
			}
			$dtFiles = Transaksi::getFiles($dtTransaksi["header"]->ID, 1);
		}
		Transaksi::where("ID", $id)
				     ->update(["TOTAL" => DB::raw("BM + BMT + PPN + PPH")]);
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/perekamanbarang", "text" => "Perekaman Barang");
		$breadcrumb[] = Array("text" => "Transaksi Perekaman Barang");

		$dtCustomer = Transaksi::getCustomer();
		$dtSatuan = Transaksi::getSatuan();
		$dtJenisKemasan = Transaksi::getJenisKemasan();
		$dtJenisDokumen = Transaksi::getJenisDokumen();
		$dtImportir = Transaksi::getImportir();
		$dtMataUang = Transaksi::getMataUang();
		$dtJenisFile = Transaksi::getJenisFile();
		$dtProduk = Produk::get();
		$dtSatuan = Transaksi::getSatuan();
		$data = [
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb,
				"idtransaksi" => $id,"dataproduk" => $dtProduk, "importir" => $dtImportir,
				"jeniskemasan" => $dtJenisKemasan, "customer" => $dtCustomer,
				"jenisdokumen" => $dtJenisDokumen, "matauang" => $dtMataUang,
				"files" => isset($dtFiles) ? $dtFiles : "{}", "datasatuan" => $dtSatuan,
				"jenisfile" => $dtJenisFile,
				"detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
				"readonly" => auth()->user()->cannot('barang.transaksi') ? "readonly" : ""
			];
		return view("transaksi.transaksibarang", $data);
	}
	public function userbarangkonversi(Request $request)
  {
		if(!auth()->user()->can('konversi.transaksi')){
			abort(403, 'User does not have the right roles.');
		}
		$id = $request->id;
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/konversibarang", "text" => "Konversi Barang");
		$breadcrumb[] = Array("text" => "Transaksi Konversi Barang");
		$dtCustomer = Transaksi::getCustomer();
		$dtSatuan = Transaksi::getSatuan();
		$dtJenisKemasan = Transaksi::getJenisKemasan();
		$dtJenisDokumen = Transaksi::getJenisDokumen();
		$dtImportir = Transaksi::getImportir();
		$dtMataUang = Transaksi::getMataUang();
		$dtJenisFile = Transaksi::getJenisFile();
		$dtTransaksi = Array();
		if ($id != ""){
			$dtTransaksi = Transaksi::getTransaksiBarang($id);
			$dtFiles = Transaksi::getFiles($dtTransaksi["header"]->ID, 1);
		}
		$dtProduk = Produk::get();
		$dtSatuan = Transaksi::getSatuan();

		$data = [
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb,
				"idtransaksi" => $id,"dataproduk" => $dtProduk, "importir" => $dtImportir,
				"jeniskemasan" => $dtJenisKemasan, "customer" => $dtCustomer,
				"jenisdokumen" => $dtJenisDokumen, "matauang" => $dtMataUang,
				"files" => isset($dtFiles) ? $dtFiles : "{}", "datasatuan" => $dtSatuan,
				"jenisfile" => $dtJenisFile,
				"detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
			];
		return view("transaksi.transaksibarangkonversi", $data);
	}
	public function userkonversi(Request $request)
  {
		if(!auth()->user()->can('konversi.transaksi')){
			abort(403, 'User does not have the right roles.');
		}
		$id = $request->id;
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$dtTransaksi = Array();
		if ($id != ""){
			$dtTransaksi = Transaksi::getKonversi($id);
		}
		$datarate = Transaksi::getRateDPP();
		$breadcrumb[] = Array("link" => "/transaksi/userbarangkonversi/" .$dtTransaksi["header"]->ID_HEADER, "text" => "Konversi Barang");
		$breadcrumb[] = Array("text" => "Transaksi Konversi");
		$dtProduk = Produk::get();

		$data = [
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb,
				"idtransaksi" => $id,"dataproduk" => $dtProduk,  "datarate" => $datarate,
				"konversi" => isset($dtTransaksi["konversi"]) ? json_encode($dtTransaksi["konversi"]) : "{}",
			];
		return view("transaksi.transaksikonversi", $data);
	}
	public function perekamanbarang(Request $request)
  {
		if(!auth()->user()->can('barang.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		$canViewCustomer = auth()->user()->can('customer.view');
		if ($filter && $filter == "1"){
			$postKantor = $request->input("kantor");
			$postCustomer = $request->input("customer");
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$postKategori2 = $request->input("kategori2");
			$dari2 = $request->input("dari2");
			$sampai2 = $request->input("sampai2");
			$export = $request->input("export");
			$includeDetail = $export == 1;
			$data = Transaksi::browseBarang($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2, $includeDetail);
			if ($data){
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', Transaksi::getKantor($postKantor)->URAIAN);
					}
					else {
					    $sheet->setCellValue('C1', "Semua");
					}
					$row = 2;
					if ($canViewCustomer){
							$sheet->setCellValue('A' .strval($row), 'CUSTOMER');
							if ($postCustomer && trim($postCustomer) != ""){
								$sheet->setCellValue('C' .strval($row), Transaksi::getCustomer($postCustomer)->nama_customer);
							}
							else {
								$sheet->setCellValue('C' .strval($row), "Semua");
							}
							$row +=1;
					}
					$sheet->setCellValue('A' .strval($row), 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C' .strval($row), Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C' .strval($row), "Semua");
					}
					$lastrow = $row;
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					if ($postKategori2 && trim($postKategori2) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori2);
						$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
					}
					$lastrow += 2;
					$columns = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P',
													 'Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE',
													 'AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO', 'AP');
					$colNo = 0;
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Importir');
					if ($canViewCustomer){
							$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Customer');
					}
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Tiba');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No Aju');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Aju');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Nopen');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No BL');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl BL');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No Inv');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Inv');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jml Kmsn');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl SPPB');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Keluar');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Terima');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jalur');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Faktur');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No. Form');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Form');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No LS');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl LS');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jns Dokumen');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Valuta');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'NDPBM');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Nilai');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'BM');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'BMT');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'PPn');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'PPh');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Total');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'PPh Bebas');
          $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Kode Barang');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Uraian');
			    $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jml Kemasan');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, '');
			    $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jml Sat Harga');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, '');
			    $sheet->setCellValue($columns[$colNo++] .$lastrow, 'CIF');
			    $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Harga');
			    $sheet->setCellValue($columns[$colNo++] .$lastrow, 'No. SPTNP');
			    $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl SPTNP');

					$rowIndex = 0;
					$rowId = 0;
					foreach ($data as $dt){
						if ($dt->ID != $rowId){
						    $rowId = $dt->ID;
						    $rowIndex += 1;
						    $lastrow += 1;
						}
						$lastrow += 1;
						$colNo = 0;
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $rowIndex);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->IMPORTIR);
						if ($canViewCustomer){
								$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CUSTOMER);
						}
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLTIBA);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOAJU);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLAJU);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOPEN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_BL);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLBL);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_INV);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLINV);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JUMLAH_KEMASAN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLSPPB);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLKELUAR);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLTERIMA);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JALURDOK);
  					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->PENGIRIM);
  					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_FORM);
  					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLFORM);
  					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_LS);
  					$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLLS);
            $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JENISDOKUMEN);
            $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->MATAUANG);
            $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NDPBM);
            $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NILAI);
            $sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->BM);
            $sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->BMT);
            $sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->PPN);
            $sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->PPH);
            $sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TOTAL);
						$sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->PPH_BEBAS);
						$sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->KODEBARANG);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->URAIAN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JMLKEMASAN);
						$sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->SATUANKEMASAN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JMLSATHARGA);
						$sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->satuan);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CIF);
						$sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0.000');
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->HARGA);
						$sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0.000');
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOSPTNP);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLSPTNP);

					}
					return $this->exportXls($spreadsheet, "browse_barang");
				}
				else {
					return response()->json($data);
				}
			}
			else {

				return response()->json([]);
			}
		}
		else {

			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Perekaman Barang");
			$kantor = Transaksi::getKantor();
			$customer = Transaksi::getCustomer();
			$importir = Transaksi::getImportir();

			return view("transaksi.perekamanbarang",["breads" => $breadcrumb,
						"datakantor" => $kantor, "datacustomer" => $customer,
						"dataimportir" => $importir,
						"datakategori1" => Array("No BL","No Kontainer","Nopen","No Inv","Hasil Periksa","No Aju"),
						"datakategori2" => Array("Tanggal Nopen","Tanggal SPPB","Tanggal Keluar","Tanggal Tiba")
						]);
		}
	}
	public function konversibarang(Request $request)
  {
		if(!auth()->user()->can('konversi.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		$canViewCustomer = auth()->user()->can('customer.view');
		if ($filter && $filter == "1"){
			$postKantor = $request->input("kantor");
			$postCustomer = $request->input("customer");
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$postKategori2 = $request->input("kategori2");
			$dari2 = $request->input("dari2");
			$sampai2 = $request->input("sampai2");
			$postKategori3 = $request->input("kategori3");
			$dari3 = $request->input("dari3");
			$sampai3 = $request->input("sampai3");

			$data = Transaksi::browseKonversi($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2, $postKategori3, $dari3, $sampai3);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', Transaksi::getKantor($postKantor)->URAIAN);
					}
					else {
					    $sheet->setCellValue('C1', "Semua");
					}
					$row = 2;
					if ($canViewCustomer){
							$sheet->setCellValue('A2', 'CUSTOMER');
							if ($postCustomer && trim($postCustomer) != ""){
								$sheet->setCellValue('C2', Transaksi::getCustomer($postCustomer)->nama_customer);
							}
							else {
								$sheet->setCellValue('C2', "Semua");
							}
							$row += 1;
					}
					$sheet->setCellValue('A' .strval($row), 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C' .strval($row), Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C' .strval($row), "Semua");
					}
					$lastrow = $row;
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					if ($postKategori2 && trim($postKategori2) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori2);
						$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
					}
					if ($postKategori3 && trim($postKategori3) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori3);
						$sheet->setCellValue('C' .$lastrow, $dari3 == "" ? "-" : Date("d M Y", strtotime($dari3)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai3 == "" ? "-" : Date("d M Y", strtotime($sampai3)));
					}

					$lastrow += 2;
					$colNo = 0;
					$columns = "ABCDEFGHIJKL";
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Importir');
					if ($canViewCustomer){
							$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Customer');
					}
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Tiba');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Nopen');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No BL');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No Inv');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jml Kmsn');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl SPPB');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Keluar');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Konversi');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Jalur');

					foreach ($data as $dt){
						$lastrow += 1;
						$colNo = 0;
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->IMPORTIR);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->CUSTOMER);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLTIBA);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOPEN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_BL);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NO_INV);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JUMLAH_KEMASAN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLSPPB);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLKELUAR);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLKONVERSI);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->JALURDOK);
					}
					return $this->exportXls($spreadsheet, "konversi");
				}
				else {
					return response()->json($data);
				}
			}
			else {

				return response()->json([]);
			}
		}
		else {

			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Konversi Barang");
			$kantor = Transaksi::getKantor();
			$customer = Transaksi::getCustomer();
			$importir = Transaksi::getImportir();

			return view("transaksi.konversibarang",["breads" => $breadcrumb,
										"datakantor" => $kantor, "datacustomer" => $customer,
										"dataimportir" => $importir,
										"datakategori1" => Array("No BL","No Kontainer","Nopen","Hasil Periksa","No Aju"),
										"datakategori2" => Array("Tanggal Nopen","Tanggal Konversi","Tanggal Keluar")
										]);
		}
	}
	private function getImportir()
	{
		$user = auth()->user()->id;
		$usr = User::find($user)->first();
		$company = $usr->hasCompany();
		if ($company){
			$company = $company->id;
			$importir = Transaksi::getImportir($company, true);
		}
		else {
			$importir = Transaksi::getImportir();
		}
		return $importir;
	}
	/*
	public function browseStokProduk(Request $request)
  {
		if(!auth()->user()->can('stokperproduk')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		if ($filter && $filter == "1"){
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$dari = $request->input("dari2");
			$sampai = $request->input("sampai2");
			$data = Transaksi::stokProduk($postImportir, $dari, $sampai, $postKategori1,
									$isikategori1);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C1', Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C1', "Semua");
					}
					$lastrow = 1;
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					$lastrow += 1;
					$sheet->setCellValue('A' .$lastrow, 'Tanggal');
					$sheet->setCellValue('C' .$lastrow, $dari);
					$sheet->setCellValue('D' .$lastrow, $sampai);
					$lastrow += 2;

					$sheet->setCellValue('A' .$lastrow, 'No');
					$sheet->setCellValue('B' .$lastrow, 'Kode Produk');
					$sheet->setCellValue('C' .$lastrow, 'Saldo Awal');
					$sheet->setCellValue('F' .$lastrow, 'Masuk');
					$sheet->setCellValue('I' .$lastrow, 'Keluar');
					$sheet->setCellValue('L' .$lastrow, 'Stok Akhir');
					$sheet->setCellValue('O' .$lastrow, "KODE BARANG");
					$sheet->setCellValue('P' .$lastrow, "IMPORTIR");
					$sheet->setCellValue('Q' .$lastrow, "TANGGAL");
					$sheet->setCellValue('R' .$lastrow, "SALDO AWAL");
					$sheet->setCellValue('U' .$lastrow, "MASUK");
					$sheet->setCellValue('X' .$lastrow, "KELUAR");

                    $no = 0;
					foreach ($data as $dt){
						$detail = Transaksi::detailStokProduk($postImportir, $dari, $sampai, $dt->id);
						if (count($detail) > 0){
							$no += 1;
							foreach ($detail as $det){
    							$lastrow += 1;
								$sheet->setCellValue('A' .$lastrow, $no);
								$sheet->setCellValue('B' .$lastrow, $dt->kode);
        						$sheet->setCellValue('C' .$lastrow, $dt->kemasansawal);
        						$sheet->setCellValue('D' .$lastrow, $dt->satuansawal);
        						$sheet->getStyle('D' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
        						$sheet->setCellValue('E' .$lastrow, $dt->satuan);
        						$sheet->setCellValue('F' .$lastrow, $dt->kemasanmasuk);
        						$sheet->setCellValue('G' .$lastrow, $dt->satuanmasuk);
        						$sheet->getStyle('G' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
        						$sheet->setCellValue('H' .$lastrow, $dt->satuan);
        						$sheet->setCellValue('I' .$lastrow, $dt->kemasankeluar);
        						$sheet->setCellValue('J' .$lastrow, $dt->satuankeluar);
        						$sheet->getStyle('J' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
        						$sheet->setCellValue('K' .$lastrow, $dt->satuan);
        						$sheet->setCellValue('L' .$lastrow, $dt->kemasansakhir);
        						$sheet->setCellValue('M' .$lastrow, $dt->satuansakhir);
        						$sheet->getStyle('M' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
        						$sheet->setCellValue('N' .$lastrow, $dt->satuan);

								$sheet->setCellValue('O' .$lastrow, $det->KODEBARANG);
								$sheet->setCellValue('P' .$lastrow, $det->NAMA);
								$sheet->setCellValue('Q' .$lastrow, $det->TANGGAL);
								$sheet->setCellValue('R' .$lastrow, $det->kemasansawal);
								$sheet->setCellValue('S' .$lastrow, $det->satuansawal);
								$sheet->getStyle('S' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
								$sheet->setCellValue('T' .$lastrow, $det->satuan);
								$sheet->setCellValue('U' .$lastrow, $det->kemasanmasuk);
								$sheet->setCellValue('V' .$lastrow, $det->satuanmasuk);
								$sheet->getStyle('V' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
								$sheet->setCellValue('W' .$lastrow, $det->satuan);
								$sheet->setCellValue('X' .$lastrow, $det->kemasankeluar);
								$sheet->setCellValue('Y' .$lastrow, $det->satuankeluar);
								$sheet->getStyle('Y' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
								$sheet->setCellValue('Z' .$lastrow, $det->satuan);
							}
						}
						else {
								$no += 1;
								$lastrow += 1;
								$sheet->setCellValue('A' .$lastrow, $no);
								$sheet->setCellValue('B' .$lastrow, $dt->kode);
        						$sheet->setCellValue('C' .$lastrow, $dt->kemasansawal);
        						$sheet->setCellValue('D' .$lastrow, $dt->satuansawal);
        						$sheet->getStyle('D' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
        						$sheet->setCellValue('E' .$lastrow, $dt->satuan);
        						$sheet->setCellValue('F' .$lastrow, $dt->kemasanmasuk);
        						$sheet->setCellValue('G' .$lastrow, $dt->satuanmasuk);
        						$sheet->getStyle('G' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
        						$sheet->setCellValue('H' .$lastrow, $dt->satuan);
        						$sheet->setCellValue('I' .$lastrow, $dt->kemasankeluar);
        						$sheet->setCellValue('J' .$lastrow, $dt->satuankeluar);
        						$sheet->getStyle('J' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
        						$sheet->setCellValue('K' .$lastrow, $dt->satuan);
        						$sheet->setCellValue('L' .$lastrow, $dt->kemasansakhir);
        						$sheet->setCellValue('M' .$lastrow, $dt->satuansakhir);
        						$sheet->getStyle('M' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
        						$sheet->setCellValue('N' .$lastrow, $dt->satuan);
						}
						$lastrow += 1;
					}
					$writer = new Xlsx($spreadsheet);
					return response()->streamDownload(function() use ($writer){
						$writer->save('php://output');
					}, 'stok_produk' .Date("YmdHis") .'.xlsx',
					['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
				}
				else {
					return response()->json($data);
				}
			}
			else {
				return response()->json([]);
			}
		}
		else {
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Browse Stok per Produk");
			$importir = Transaksi::getImportir();
			$produk = DB::table("produk")->get();

			return view("transaksi.stokproduk",["breads" => $breadcrumb,
										"dataimportir" => $importir, "dataproduk" => $produk,
										"datakategori1" => Array("Kode Produk"),"datakategori2" => Array("Tanggal")
										]);
		}
	}
	public function browseStokBarang(Request $request)
  {
		if(!auth()->user()->can('stokperbarang')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		if ($filter && $filter == "1"){
			//$postKantor = $request->input("kantor");
			$postCustomer = $request->input("customer");
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$postKategori2 = $request->input("kategori2");
			$dari2 = $request->input("dari2");
			$sampai2 = $request->input("sampai2");

			$data = Transaksi::stokBarang($postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2);
			if ($data){
				$export = $request->input("export");
                if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'CUSTOMER');
					if ($postCustomer && trim($postCustomer) != ""){
						$sheet->setCellValue('C1', Transaksi::getCustomer($postCustomer)->nama_customer);
					}
					else {
						$sheet->setCellValue('C1', "Semua");
					}
					$sheet->setCellValue('A2', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C2', Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C2', "Semua");
					}
					$lastrow = 2;
		            if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					if ($postKategori2 && trim($postKategori2) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori2);
						$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
					}
					$lastrow += 2;
					$sheet->setCellValue('A' .$lastrow, 'No');
					$sheet->setCellValue('B' .$lastrow, 'Kode Barang');
					$sheet->setCellValue('C' .$lastrow, 'Kode Produk');
					$sheet->setCellValue('D' .$lastrow, 'Customer');
					$sheet->setCellValue('E' .$lastrow, 'Faktur');
					$sheet->setCellValue('F' .$lastrow, 'No.Aju');
					$sheet->setCellValue('G' .$lastrow, 'Kurs');
					$sheet->setCellValue('H' .$lastrow, 'Harga Satuan');
					$sheet->setCellValue('I' .$lastrow, 'DPP');
					$sheet->setCellValue('J' .$lastrow, 'Tgl Terima');
					$sheet->setCellValue('K' .$lastrow, 'Saldo Awal');
					$sheet->setCellValue('N' .$lastrow, 'Masuk');
					$sheet->setCellValue('Q' .$lastrow, 'Keluar');
					$sheet->setCellValue('T' .$lastrow, 'Stok Akhir');
					$sheet->setCellValue('W' .$lastrow, "No.DO");
					$sheet->setCellValue('X' .$lastrow, "Tgl DO");
					$sheet->setCellValue('Y' .$lastrow, "No Inv Jual");
					$sheet->setCellValue('Z' .$lastrow, "Tanggal");
					$sheet->setCellValue('AA' .$lastrow, "Keluar");

          $no = 0;
          $lastrow += 1;
					foreach ($data as $dt){
						$detail = Transaksi::getDetailStokBarang($dt->ID, $postKategori2, $dari2, $sampai2);
						if (count($detail) > 0){
							$no += 1;
							foreach ($detail as $det){
								$sheet->setCellValue('A' .$lastrow, $no);
								$sheet->setCellValue('B' .$lastrow, $dt->KODEBARANG);
								$sheet->setCellValue('C' .$lastrow, $dt->kode);
								$sheet->setCellValue('D' .$lastrow, $dt->CUSTOMER);
								$sheet->setCellValue('E' .$lastrow, $dt->FAKTUR);
								$sheet->setCellValue('F' .$lastrow, $dt->NOAJU);
								$sheet->setCellValue('G' .$lastrow, $dt->NDPBM);
								$sheet->setCellValue('H' .$lastrow, $dt->HARGA);
								$sheet->setCellValue('I' .$lastrow, $dt->DPP);
								$sheet->setCellValue('J' .$lastrow, $dt->TGL_TERIMA);
    						$sheet->setCellValue('K' .$lastrow, $dt->kemasansawal);
    						$sheet->setCellValue('L' .$lastrow, $dt->satuansawal);
    						$sheet->setCellValue('M' .$lastrow, $dt->satuan);
    						$sheet->setCellValue('N' .$lastrow, $dt->kemasanmasuk);
    						$sheet->setCellValue('O' .$lastrow, $dt->satuanmasuk);
    						$sheet->setCellValue('P' .$lastrow, $dt->satuan);
    						$sheet->setCellValue('Q' .$lastrow, $dt->kemasankeluar);
    						$sheet->setCellValue('R' .$lastrow, $dt->satuankeluar);
    						$sheet->setCellValue('S' .$lastrow, $dt->satuan);
    						$sheet->setCellValue('T' .$lastrow, $dt->kemasansakhir);
    						$sheet->setCellValue('U' .$lastrow, $dt->satuansakhir);
    						$sheet->setCellValue('V' .$lastrow, $dt->satuan);

								$sheet->setCellValue('W' .$lastrow, $det->NO_DO);
								$sheet->setCellValue('X' .$lastrow, $det->TGL_DO);
								$sheet->setCellValue('Y' .$lastrow, $det->NO_INV_JUAL);
								$sheet->setCellValue('Z' .$lastrow, $det->TGL_KELUAR);
								$sheet->setCellValue('AA' .$lastrow, $det->kemasankeluar);
								$sheet->setCellValue('AB' .$lastrow, $det->satuankeluar);
								$sheet->setCellValue('AC' .$lastrow, $det->satuan);
						    $lastrow += 1;

							}
							$lastrow += 1;
						}
						else {
								$no += 1;
								$sheet->setCellValue('A' .$lastrow, $no);
								$sheet->setCellValue('B' .$lastrow, $dt->KODEBARANG);
								$sheet->setCellValue('C' .$lastrow, $dt->kode);
								$sheet->setCellValue('D' .$lastrow, $dt->CUSTOMER);
								$sheet->setCellValue('E' .$lastrow, $dt->FAKTUR);
								$sheet->setCellValue('F' .$lastrow, $dt->NOAJU);
								$sheet->setCellValue('G' .$lastrow, $dt->NDPBM);
								$sheet->setCellValue('H' .$lastrow, $dt->HARGA);
								$sheet->setCellValue('I' .$lastrow, $dt->DPP);
								$sheet->setCellValue('J' .$lastrow, $dt->TGL_TERIMA);
    						$sheet->setCellValue('K' .$lastrow, $dt->kemasansawal);
    						$sheet->setCellValue('L' .$lastrow, $dt->satuansawal);
    						$sheet->setCellValue('M' .$lastrow, $dt->satuan);
    						$sheet->setCellValue('N' .$lastrow, $dt->kemasanmasuk);
    						$sheet->setCellValue('O' .$lastrow, $dt->satuanmasuk);
    						$sheet->setCellValue('P' .$lastrow, $dt->satuan);
    						$sheet->setCellValue('Q' .$lastrow, $dt->kemasankeluar);
    						$sheet->setCellValue('R' .$lastrow, $dt->satuankeluar);
    						$sheet->setCellValue('S' .$lastrow, $dt->satuan);
    						$sheet->setCellValue('T' .$lastrow, $dt->kemasansakhir);
    						$sheet->setCellValue('U' .$lastrow, $dt->satuansakhir);
    						$sheet->setCellValue('V' .$lastrow, $dt->satuan);
    						$lastrow += 1;
    					}
    				}

						$writer = new Xlsx($spreadsheet);
						return response()->streamDownload(function() use ($writer){
							$writer->save('php://output');
						}, 'stok_barang' .Date("YmdHis") .'.xlsx',
						['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    			}
        		else {

    				return response()->json($data);
    			}
    		}
    		else {

				return response()->json([]);
			}
		}
		else {
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Browse Stok per Kode Barang");
			$importir = Transaksi::getImportir();
			$customer = Transaksi::getCustomer();

			return view("transaksi.stokbarang",["breads" => $breadcrumb,
									    "datacustomer" => $customer,
										"dataimportir" => $importir,
										"datakategori1" => Array("Saldo Akhir", "Kode Barang"),
										"datakategori2" => Array("Tanggal Terima", "Tanggal DO")
										]);
    	}
	}
	public function detailstokproduk(Request $request)
	{
		if(!auth()->user()->can('stokperproduk')){
			abort(403, 'User does not have the right roles.');
		}
		$id = $request->input("id");
		parse_str($request->input("form"), $form);
		$data = Transaksi::detailStokProduk($form["importir"],$form['dari2'], $form['sampai2'], $id);
		return response()->json(["data" => $data]);
	}
	public function detailstokbarang(Request $request)
	{
		if(!auth()->user()->can('stokperbarang')){
			abort(403, 'User does not have the right roles.');
		}
		$id = $request->input("id");
		parse_str($request->input("form"), $form);
		$data = Transaksi::getDetailStokBarang($id, $form["kategori2"], $form["dari2"], $form["sampai2"]);
		return response()->json(["data" => $data]);
	}
	*/
	public function perekamanpengeluaran($id = "")
	{
		if(!auth()->user()->can('master.produk.browse')){
			abort(403, 'User does not have the right roles.');
		}
    $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman Pengeluaran");

		if ($id != ""){
    		$data = Transaksi::getTransaksiDOrder($id, false);
	    	$no_do = $data["header"]->NO_DO;
		}
		else {
		    $no_do = "";
		}
		return view("transaksi.pengeluaran",["breads" => $breadcrumb,
									"no_do" => $no_do]);
	}
	/*
	public function deliveryorder(Request $request, $id = "")
  {
		if(!auth()->user()->can('deliveryorder')){
			abort(403, 'User does not have the right roles.');
		}

  	$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman Delivery Order");
		$pembeli = Transaksi::getPembeli();
		if ($id != ""){
			$dtTransaksi = Transaksi::getTransaksiDOrder($id);
			$pengeluaran = Transaksi::getDataPengeluaran($id);
		}
		else {
			$notransaksi = " (Baru)";
		}
		$data = [ "datapembeli" => $pembeli,
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : new DeliveryOrder, "breads" => $breadcrumb,
				"idtransaksi" => $id, "detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
				"pengeluaran" => isset($pengeluaran) ? json_encode($pengeluaran) : "{}"
			];

		return view("transaksi.transaksidorder", $data);
	}
	*/
	public function searchkodebarang(Request $request)
	{
		$kode = $request->input("kode");
		$data = Transaksi::getKodeBarang($kode);
		if (!$data){
			$response["error"] = "Kode Barang tidak ada";
		}
		else {
			$response = $data;
		}

		return response()->json($response);
	}
	public function searchdo(Request $request)
	{
		$nodo = $request->input("no_do");
		$data = Transaksi::getTransaksiDOrder($nodo, false, "NO_DO");

		if ($data){
		    $pengeluaran = Transaksi::getDataPengeluaran($data["header"]->ID);
			return response()->json(["header" => $data, "detail" => $pengeluaran]);
		}
		else {
			return response()->json(["error" => "Data Tidak Ditemukan"]);
		}
	}
	public function userquota(Request $request, $id = "")
  {
		$user = auth()->user();
		if ($id == ""){
			if(!$user->can('quota.transaksi')){
				abort(403, 'User does not have the right roles.');
			}
		}
		else {
			if (!$user->can('quota.edit')){
				abort(403, 'User does not have the right roles.');
			}
		}
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/browsesaldoquota", "text" => "Browse Saldo Quota");
		$breadcrumb[] = Array("text" => "Perekaman Quota");

		$dtImportir = Transaksi::getImportir();
		$dtSatuan = Transaksi::getSatuan();
		if ($id != ""){
    		$dtTransaksi = Transaksi::getTransaksiQuota($id);
    		$dtFiles = Transaksi::getFiles($id, 2);
		}
		else {
				$dtTransaksi["header"] = new Quota;
		}
		$data = [
				"header" => isset($dtTransaksi) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb,
				"idtransaksi" => $id, "importir" => $dtImportir,"files" => isset($dtFiles) ? $dtFiles : [],
				"datasatuan" => $dtSatuan, "detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}"
			];
		return view("transaksi.transaksiquota", $data);
	}
	public function getPI(Request $request)
	{
	    $id = $request->input("id");
	    $data = Transaksi::getPI($id);

	    if ($data){
	        return response()->json(["ID" => $data->ID, "NO_PI" => $data->NO_PI, "TGL_PI" => $data->TGLPI]);
	    }
	    else {
	        return response()->json(["ID" => "", "NO_PI" => "", "TGL_PI" => ""]);
	    }
	}
	public function browseSaldoQuota(Request $request)
  {
		if(!auth()->user()->can('quota.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		$canViewCustomer = auth()->user()->can('customer.view');
		$canViewDetailQuota = auth()->user()->can('quota.edit');
		if ($filter && $filter == "1"){
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$data = Transaksi::saldoQuota($postImportir, $postKategori1, $isikategori1);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C1', Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C1', "Semua");
					}
					$lastrow = 1;
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					$lastrow += 2;

					$sheet->setCellValue('A' .$lastrow, 'No');
					$sheet->setCellValue('B' .$lastrow, 'Importir');
					$sheet->setCellValue('C' .$lastrow, 'Kode HS');
					$sheet->setCellValue('D' .$lastrow, 'Saldo Awal');
					$sheet->setCellValue('E' .$lastrow, 'Terpakai');
					$sheet->setCellValue('F' .$lastrow, 'Stok Akhir');
					$sheet->setCellValue('G' .$lastrow, "Satuan");
					if ($canViewDetailQuota){
							$sheet->setCellValue('H' .$lastrow, "Consignee");
							$colNo = 8;
							$columns = "ABCDEFGHIJKLMNO";
							if ($canViewCustomer){
									$sheet->setCellValue($columns[$colNo++] .$lastrow, "Customer");
							}
							$sheet->setCellValue($columns[$colNo++] .$lastrow, "No. VO");
							$sheet->setCellValue($columns[$colNo++] .$lastrow, "No Inv");
							$sheet->setCellValue($columns[$colNo++] .$lastrow, "No BL");
		          $sheet->setCellValue($columns[$colNo++] .$lastrow, "Booking");
		          $sheet->setCellValue($columns[$colNo++] .$lastrow, "Realisasi");
		          $sheet->setCellValue($columns[$colNo++] .$lastrow, "Satuan");
					}
          $no = 0;
					foreach ($data as $dt){
						if ($canViewDetailQuota){
								$detail = Transaksi::detailSaldoQuota($dt->ID, $dt->KODE_HS);
						}
						else {
								$detail = Array();
						}
						if (count($detail) > 0){
							$no += 1;
							foreach ($detail as $det){
  							$lastrow += 1;
								$sheet->setCellValue('A' .$lastrow, $no);
								$sheet->setCellValue('B' .$lastrow, $dt->NAMAIMPORTIR);
    						$sheet->setCellValue('C' .$lastrow, $dt->KODE_HS);
    						$sheet->setCellValue('D' .$lastrow, $dt->AWAL);
    						$sheet->getStyle('D' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
    						$sheet->setCellValue('E' .$lastrow, $dt->TERPAKAI);
    						$sheet->getStyle('E' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
    						$sheet->setCellValue('F' .$lastrow, $dt->AKHIR);
    						$sheet->getStyle('F' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
    						$sheet->setCellValue('G' .$lastrow, $dt->SATUAN);
    						$sheet->setCellValue('H' .$lastrow, $det->NAMACONSIGNEE);
								$colNo = 8;
								if ($canViewCustomer){
    								$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->NAMACUSTOMER);
								}
    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->NO_VO);
    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->NO_INV);
    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->NO_BL);
    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->BOOKING);
    						$sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->REALISASI);
    						$sheet->getStyle($columns[$colNo] .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
    						$sheet->setCellValue($columns[$colNo++] .$lastrow, $det->NAMASATUAN);
  						}
						}
						else {
								$no += 1;
								$lastrow += 1;
								$sheet->setCellValue('A' .$lastrow, $no);
								$sheet->setCellValue('B' .$lastrow, $dt->NAMAIMPORTIR);
    						$sheet->setCellValue('C' .$lastrow, $dt->KODE_HS);
    						$sheet->setCellValue('D' .$lastrow, $dt->AWAL);
    						$sheet->getStyle('D' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
    						$sheet->setCellValue('E' .$lastrow, $dt->TERPAKAI);
    						$sheet->getStyle('E' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
    						$sheet->setCellValue('F' .$lastrow, $dt->AKHIR);
    						$sheet->getStyle('F' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
    						$sheet->setCellValue('G' .$lastrow, $dt->SATUAN);
						}
						$lastrow += 1;
					}
					return $this->exportXls($spreadsheet, "saldo_quota");
				}
				else {
					return response()->json($data);
				}
			}
			else {
				return response()->json([]);
			}
		}
		else {
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Browse Saldo Quota");
			$importir = Transaksi::getImportir();

			return view("transaksi.saldoquota",["breads" => $breadcrumb,
										"dataimportir" => $importir,
										"datakategori1" => Array("Kode HS", "No VO", "No Inv")
										]);
		}
	}
	public function detailsaldoquota(Request $request)
	{
		if(!auth()->user()->can('quota.browse')){
			abort(403, 'User does not have the right roles.');
		}
		$id = $request->input("id");
		$kodehs = $request->input("kodehs");
		$data = Transaksi::detailSaldoQuota($id, $kodehs);
		return response()->json(["data" => $data]);
	}
	public function voucher($id = "")
	{
    $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman Voucher");


		if ($id != ""){
    		$data = Transaksi::getTransaksiVoucher($id, false);
	    	$no_voucher = sprintf('%08d', $data["header"]["ID"]);
	    	$tanggal = $data["header"]["TANGGAL"];
		}
		else {
		    $no_do = "";
		    $no_voucher = "Baru";
		    $tanggal = Date("d-n-Y");
		}
		return view("transaksi.transaksivoucher",["breads" => $breadcrumb,
									"stylesheets" => $assets["stylesheets"],"no_voucher" => $no_voucher, "tgl_voucher" => $tanggal,
									"scripts" => $assets["scripts"]]);
	}
	public function getBL($id)
	{
	    $data = Transaksi::getBL($id);
        $response = [];
	    if ($data){
	    	$response["data"] = $data;
	    }
	    else {
	        $response["data"] = [];
	    }
	    return response()->json($response);
	}
	public function banding(Request $request)
  {
		if(!auth()->user()->can('sptnp.banding')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		if ($filter && $filter == "1"){
			$postKantor = $request->input("kantor");
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$postKategori2 = $request->input("kategori2");
			$dari2 = $request->input("dari2");
			$sampai2 = $request->input("sampai2");
			$postKategori3 = $request->input("kategori3");
			$dari3 = $request->input("dari3");
			$sampai3 = $request->input("sampai3");

			$data = Sptnp::browseBanding($postKantor, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2, $postKategori3, $dari3, $sampai3);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', Transaksi::getKantor($postKantor)->URAIAN);
					}
					else {
					    $sheet->setCellValue('C1', "Semua");
					}
					$sheet->setCellValue('A2', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C2', Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C2', "Semua");
					}
					$lastrow = 2;
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					if ($postKategori2 && trim($postKategori2) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori2);
						$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
					}
					if ($postKategori3 && trim($postKategori3) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori3);
						$sheet->setCellValue('C' .$lastrow, $dari3 == "" ? "-" : Date("d M Y", strtotime($dari3)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai3 == "" ? "-" : Date("d M Y", strtotime($sampai3)));
					}
					$lastrow += 1;
					$sheet->setCellValue('A' .$lastrow, 'Kantor');
					$sheet->setCellValue('B' .$lastrow, 'Importir');
					$sheet->setCellValue('C' .$lastrow, 'Nopen');
					$sheet->setCellValue('D' .$lastrow, 'Tgl Nopen');
				  $sheet->setCellValue('E' .$lastrow, 'No Kep BRT');
					$sheet->setCellValue('F' .$lastrow, 'Tgl Kep BRT');
					$sheet->setCellValue('G' .$lastrow, 'No Sengk');
					$sheet->setCellValue('H' .$lastrow, 'Tgl Sengk');
					$sheet->setCellValue('I' .$lastrow, 'Mjls');
					$sheet->setCellValue('J' .$lastrow, 'SD01');
					$sheet->setCellValue('K' .$lastrow, 'SD02');
					$sheet->setCellValue('L' .$lastrow, 'SD03');
					$sheet->setCellValue('M' .$lastrow, 'SD04');
					$sheet->setCellValue('N' .$lastrow, 'SD05');
					$sheet->setCellValue('O' .$lastrow, 'SD06');
					$sheet->setCellValue('P' .$lastrow, 'SD07');
					$sheet->setCellValue('Q' .$lastrow, 'HASIL_BDG');
					$sheet->setCellValue('R' .$lastrow, 'NO_KEP_BDG');
					$sheet->setCellValue('S' .$lastrow, 'TGLKEPBDG');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->KODEKANTOR);
						$sheet->setCellValue('B' .$lastrow, $dt->NAMAIMPORTIR);
						$sheet->setCellValue('C' .$lastrow, $dt->NOPEN);
						$sheet->setCellValue('D' .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue('E' .$lastrow, $dt->NO_KEPBRT);
						$sheet->setCellValue('F' .$lastrow, $dt->TGLKEPBRT);
						$sheet->setCellValue('G' .$lastrow, $dt->NO_BDG);
						$sheet->setCellValue('H' .$lastrow, $dt->TGLBDG);
						$sheet->setCellValue('I' .$lastrow, $dt->MAJELIS);
						$sheet->setCellValue('J' .$lastrow, $dt->SDG01);
						$sheet->setCellValue('K' .$lastrow, $dt->SDG02);
						$sheet->setCellValue('L' .$lastrow, $dt->SDG03);
						$sheet->setCellValue('M' .$lastrow, $dt->SDG04);
						$sheet->setCellValue('N' .$lastrow, $dt->SDG05);
						$sheet->setCellValue('O' .$lastrow, $dt->SDG06);
						$sheet->setCellValue('P' .$lastrow, $dt->SDG07);
						$sheet->setCellValue('Q' .$lastrow, $dt->HASIL_BDG);
						$sheet->setCellValue('R' .$lastrow, $dt->NO_KEP_BDG);
						$sheet->setCellValue('S' .$lastrow, $dt->TGLKEPBDG);
					}

					$writer = new Xlsx($spreadsheet);
					return response()->streamDownload(function() use ($writer){
							$writer->save('php://output');
						}, 'banding_' .Date("YmdHis") .'.xlsx',
						['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

				}
				else {
					return response()->json($data);
				}
			}
			else {
				return response()->json([]);
			}
		}
		else {

			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Browse Banding");
			$kantor = Transaksi::getKantor();
			$importir = Transaksi::getImportir();
			return view("transaksi.banding",["breads" => $breadcrumb,
										"datakantor" => $kantor, "dataimportir" => $importir,
										"datakategori1" => Array("Nopen","No Kep Brt", "No Sengk", "Mjls"),
										"datakategori2" => Array("Tanggal Nopen","Tanggal Sengk", "Tanggal Kep Brt", "Tanggal Sidang")
										]);
		}
	}
	public function keberatan(Request $request)
  {
		if(!auth()->user()->can('sptnp.keberatan')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		if ($filter && $filter == "1"){
			$postKantor = $request->input("kantor");
			$postImportir = $request->input("importir");
			$postKategori1 = $request->input("kategori1");
			$isikategori1 = $request->input("isikategori1");
			$postKategori2 = $request->input("kategori2");
			$dari2 = $request->input("dari2");
			$sampai2 = $request->input("sampai2");

			$data = Sptnp::browseKeberatan($postKantor, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', Transaksi::getKantor($postKantor)->URAIAN);
					}
					else {
					    $sheet->setCellValue('C1', "Semua");
					}
					$sheet->setCellValue('A2', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C2', Transaksi::getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C2', "Semua");
					}
					$lastrow = 2;
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						$sheet->setCellValue('C' .$lastrow, $isikategori1);
					}
					if ($postKategori2 && trim($postKategori2) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori2);
						$sheet->setCellValue('C' .$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
					}
					$lastrow += 1;
					$sheet->setCellValue('A' .$lastrow, 'Kantor');
					$sheet->setCellValue('B' .$lastrow, 'Importir');
					$sheet->setCellValue('C' .$lastrow, 'No SPTNP');
					$sheet->setCellValue('D' .$lastrow, 'Tgl SPTNP');
				  $sheet->setCellValue('E' .$lastrow, 'Nopen');
				  $sheet->setCellValue('F' .$lastrow, 'Total');
					$sheet->setCellValue('G' .$lastrow, 'Tgl Lunas');
					$sheet->setCellValue('H' .$lastrow, 'Tgl BRT');
					$sheet->setCellValue('I' .$lastrow, 'Hsl BRT');
					$sheet->setCellValue('J' .$lastrow, 'No Kep BRT');
					$sheet->setCellValue('K' .$lastrow, 'Tgl Kep BRT');
					$sheet->setCellValue('L' .$lastrow, 'Tgl Jth Tempo Bdg');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->KODEKANTOR);
						$sheet->setCellValue('B' .$lastrow, $dt->NAMAIMPORTIR);
						$sheet->setCellValue('C' .$lastrow, $dt->NO_SPTNP);
						$sheet->setCellValue('D' .$lastrow, $dt->TGLSPTNP);
						$sheet->setCellValue('E' .$lastrow, $dt->NOPEN);
						$sheet->setCellValue('F' .$lastrow, $dt->TOTAL_TB);
						$sheet->setCellValue('G' .$lastrow, $dt->TGLLUNAS);
						$sheet->setCellValue('H' .$lastrow, $dt->TGLBRT);
						$sheet->setCellValue('I' .$lastrow, $dt->HSL_BRT);
						$sheet->setCellValue('J' .$lastrow, $dt->NO_KEPBRT);
						$sheet->setCellValue('K' .$lastrow, $dt->TGLKEPBRT);
						$sheet->setCellValue('L' .$lastrow, $dt->TGLJTHTMPBDG);
					}

					$writer = new Xlsx($spreadsheet);
					return response()->streamDownload(function() use ($writer){
							$writer->save('php://output');
						}, 'keberatan_' .Date("YmdHis") .'.xlsx',
						['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

				}
				else {
					return response()->json($data);
				}
			}
			else {
				return response()->json([]);
			}
		}
		else {

			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Browse Keberatan");
			$kantor = Transaksi::getKantor();
			$importir = Transaksi::getImportir();
			return view("transaksi.keberatan",["breads" => $breadcrumb,
										"datakantor" => $kantor, "dataimportir" => $importir,
										"datakategori1" => Array("Nopen","No SPTNP"),
										"datakategori2" => Array("Tanggal Jatuh Tempo","Tanggal SPTNP", "Tanggal BRT", "Tanggal Nopen","Tanggal Lunas", "Tgl Jatuh Tempo Bdg")
										]);
		}
	}
	public function profilharga(Request $request)
  {
		if(!auth()->user()->can('profil_harga')){
			abort(403, 'User does not have the right roles.');
		}
		$filter = $request->input("filter");
		$canViewCustomer = auth()->user()->can('customer.view');
		if ($filter && $filter == "1"){
			$postSupplier = $request->input("supplier");
			$postImportir = $request->input("importir");
			$postUraian = $request->input("uraian");
			$postKodeBarang = $request->input("kodebarang");
			$postKategori1 = $request->input("kategori1");
			$dari1 = $request->input("dari1");
			$sampai1 = $request->input("sampai1");

			$data = Transaksi::profilHarga($postSupplier, $postImportir, $postKodeBarang, $postUraian,
									$postKategori1, $dari1, $sampai1);
			if ($data){
				$export = $request->input("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$lastrow = 0;
					if ($postSupplier && trim($postSupplier) != ""){
							$lastrow += 1;
							$sheet->setCellValue('A' .$lastrow, 'SUPPLIER');
    					$sheet->setCellValue('C' .$lastrow, $postSupplier);
					}
					if ($postImportir && trim($postImportir) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, 'IMPORTIR');
						$sheet->setCellValue('C' .$lastrow, Transaksi::getImportir($postImportir)->NAMA);
					}
					if ($postKodeBarang && trim($postKodeBarang) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, 'KODE BARANG');
						$sheet->setCellValue('C' .$lastrow, $postKodeBarang);
					}
					if ($postUraian && trim($postUraian) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, 'URAIAN');
						$sheet->setCellValue('C' .$lastrow, $postUraian);
					}
					if ($postKategori1 && trim($postKategori1) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori1);
						$sheet->setCellValue('C' .$lastrow, $dari1 == "" ? "-" : Date("d M Y", strtotime($dari1)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai1 == "" ? "-" : Date("d M Y", strtotime($sampai1)));
					}
					$lastrow += 1;
					$columns = "ABCDEFGHIJK";
					$colNo = 0;
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Kantor');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Supplier');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Importir');
					if ($canViewCustomer){
							$sheet->setCellValue('D' .$lastrow, 'Customer');
					}
				  $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl BL');
				  $sheet->setCellValue($columns[$colNo++] .$lastrow, 'Nopen');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Kode Brg');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Uraian');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'Harga');
					$sheet->setCellValue($columns[$colNo++] .$lastrow, 'No SPTNP');

					foreach ($data as $dt){
						$lastrow += 1;
						$colNo = 0;
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->KODEKANTOR);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NAMASUPPLIER);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NAMAIMPORTIR);
						if ($canViewCustomer){
								$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NAMACUSTOMER);
						}
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLBL);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOPEN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->KODEBARANG);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->URAIAN);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->HARGA);
						$sheet->setCellValue($columns[$colNo++] .$lastrow, $dt->NOSPTNP);
					}

					$writer = new Xlsx($spreadsheet);
					return response()->streamDownload(function() use ($writer){
							$writer->save('php://output');
						}, 'profilharga_' .Date("YmdHis") .'.xlsx',
						['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

				}
				else {
					return response()->json($data);
				}
			}
			else {
				return response()->json([]);
			}
		}
		else {

			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Browse Profil Harga");
			$importir = Transaksi::getImportir();
			return view("transaksi.profilharga",["breads" => $breadcrumb,
										"dataimportir" => $importir,
										"datakategori1" => Array("Tanggal Nopen","Tanggal BL")
										]);
		}
	}
}
