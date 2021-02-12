<?php

namespace App\Controllers;

use Now\System\Packages\PageController as PageController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Transaksi extends PageController {
	
	public function __construct($app)
	{
		parent::__construct($app);
		$this->beforeRender(function($data){
			$data['username'] = ucfirst($this->getSessionValue("logged_in"));
			$data['userlevel'] = $this->getSessionValue("userlevel");
			return $data;
		});
	}
    private function prepareAssets()
	{
		return ["stylesheets" => ["/web/assets/datatables/css/jquery.dataTables.min.css",
								  "/web/assets/datatables/css/jquery.dataTables_themeroller.css",	
								  "/web/assets/datatables/Select-1.2.6/css/select.dataTables.min.css",
								  "/web/assets/datatables/Responsive-2.2.2/css/responsive.dataTables.min.css",
								  "/web/assets/jquery-ui/jquery-ui.min.css"
								  ],
				"scripts" => ["/web/assets/datatables/js/jquery.dataTables.min.js",
							  "/web/assets/datatables/Select-1.2.6/js/dataTables.select.min.js",
							  "/web/assets/datatables/Responsive-2.2.2/js/dataTables.responsive.min.js",
							  "/web/assets/jquery-ui/jquery-ui.min.js"
								]
				];
	}

    public function index($id = "")
    {		
        $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Transaksi");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/app/transaksi.js";
		$this->loadModel("Transaksi");		
		$dtImportir = $this->transaksi->getImportir();
		$dtCustomer = $this->transaksi->getCustomer();
		$dtJenisBarang = $this->transaksi->getJenisBarang();
		$dtJenisKemasan = $this->transaksi->getJenisKemasan();
		$dtJumlahKontainer = $this->transaksi->getJumlahKontainer();
		$dtUkuranKontainer = $this->transaksi->getUkuranKontainer();
		$dtJenisDokumen = $this->transaksi->getJenisDokumen();
		$dtShipper = $this->transaksi->getShipper();
		$dtPelmuat = $this->transaksi->getPelmuat();
		$dtKantor = $this->transaksi->getKantor();
		$dtTransaksi = Array();
		if ($id != ""){
			$dtTransaksi = $this->transaksi->getTransaksi($id);
			$notransaksi = " No. " .sprintf('%08d', $dtTransaksi["header"]->ID);
		}
		else {
			$notransaksi = " (Baru)";
		}
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb, "importir" => $dtImportir, 
				"kontainer" => isset($dtTransaksi["kontainer"]) ? json_encode($dtTransaksi["kontainer"]) : "{}", 				
				"jeniskemasan" => $dtJenisKemasan, "customer" => $dtCustomer,
				"jenisdokumen" => $dtJenisDokumen,  
				"kodekantor" => $dtKantor, "pelmuat" => $dtPelmuat, "shipper" => $dtShipper,
				"jumlahkontainer" => $dtJumlahKontainer, 
				"jenisbarang" => $dtJenisBarang, "idtransaksi" => $id,
				"ukurankontainer" => $dtUkuranKontainer, "notransaksi" => $notransaksi,
				"canDelete" => $id != "",
				"userlevel" => $this->getSessionValue("userlevel")
			];		
		$this->render("transaksi.php", $data);
	}
	public function transaksibayar($id = "")
    {		
        $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Transaksi Pembayaran");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$this->loadModel("Transaksi");		
		$dtRekening = $this->transaksi->getRekening();
		$dtMataUang = $this->transaksi->getMataUang();
		$dtKantor = $this->transaksi->getKantor();
		$dtTransaksi = Array();
		if ($id != ""){
			$this->transaksi->calculateBayar($id);
			$dtTransaksi = $this->transaksi->getTransaksiBayar($id);
		}
		else {
			$notransaksi = " (Baru)";
		}
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb, 
				"rekening" => $dtRekening, 
				"detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
				"matauang" => $dtMataUang
			];		
		$this->render("transaksibayar.php", $data);
	}
	public function userdo($id = "")
    {		
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/perekamando", "text" => "Browse Do");
		$breadcrumb[] = Array("text" => "Perekaman Do");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/js/dropzone.min.js";
		$assets["stylesheets"][] = "/web/assets/css/dropzone.min.css";
		$this->loadModel("Transaksi");		
		$dtPelmuat = $this->transaksi->getPelmuat();
		$dtTransaksi = $this->transaksi->getTransaksiDo($id);
		$dtMataUang = $this->transaksi->getMataUang();
		$dtTOP = $this->transaksi->getTOP();
		$dtFiles = $this->transaksi->getFiles($id);
		$dtJenisFile = $this->transaksi->getJenisFile();
		$notransaksi = " No. " .sprintf('%08d', $dtTransaksi->ID);		
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi) ? $dtTransaksi : "{}" , "breads" => $breadcrumb,  
				"idtransaksi" => $id,"notransaksi" => $notransaksi, "pelmuat" => $dtPelmuat,
				"files" => $dtFiles,"top" => $dtTOP, "matauang" => $dtMataUang,
				"jenisfile" => $dtJenisFile
			];		
		$this->render("transaksido.php", $data);
	}
	public function userbc($id = "")
    {		
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/perekamanbc", "text" => "Browse BC 2.0");
		$breadcrumb[] = Array("text" => "Perekaman BC 2.0");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/app/transaksibc.js";
		$this->loadModel("Transaksi");				
		$dtJenisDokumen = $this->transaksi->getJenisDokumen();
		$dtTransaksi = $this->transaksi->getTransaksiBc($id);
		$notransaksi = " No. " .sprintf('%08d', $dtTransaksi->ID);
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi) ? $dtTransaksi : "{}" , "breads" => $breadcrumb,  
				"idtransaksi" => $id,"notransaksi" => $notransaksi,"jenisdokumen" => $dtJenisDokumen];		
		$this->render("transaksibc.php", $data);
	}
	public function uservo($id = "")
    {		
        $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/perekamanvo", "text" => "Browse VO");
		$breadcrumb[] = Array("text" => "Perekaman VO");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/app/transaksivo.js";
		$this->loadModel("Transaksi");		
		$dtTransaksi = $this->transaksi->getTransaksiVo($id);
		$notransaksi = " No. " .sprintf('%08d', $dtTransaksi->ID);
		
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => $dtTransaksi, "breads" => $breadcrumb,  
				"idtransaksi" => $id,
				"notransaksi" => $notransaksi
			];		
		$this->render("transaksivo.php", $data);
	}
	public function usersptnp($id = "")
    {		
        $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman SPTNP");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/app/sptnp.js";
		$this->loadModel("Transaksi");		
		$dtTransaksi = $this->transaksi->getTransaksiSPTNP($id);
		$notransaksi = " No. " .sprintf('%08d', $dtTransaksi->ID);
		
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => $dtTransaksi, "breads" => $breadcrumb,  
				"idtransaksi" => $id,
				"notransaksi" => $notransaksi
			];		
		$this->render("transaksisptnp.php", $data);
	}
	public function userbayar($id = "")
    {		
        $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman Pembayaran");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$this->loadModel("Transaksi");
		$dtCustomer = $this->transaksi->getCustomer();
		$dtBank = $this->transaksi->getBank();
		$dtMataUang = $this->transaksi->getMataUang();
		$dtRekening = $this->transaksi->getRekening();
		$dtTOP = $this->transaksi->getTOP();
		$dtShipper = $this->transaksi->getShipper();
		$dtImportir = $this->transaksi->getImportir();
		$dtPenerima = $this->transaksi->getPenerima();
		$this->transaksi->calculateBayar($id);
		$dtTransaksi = Array();
		$dtTransaksi = $this->transaksi->getTransaksiBayar($id);
		$notransaksi = " No. " .sprintf('%08d', $dtTransaksi["header"]->ID);
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => $dtTransaksi["header"], "breads" => $breadcrumb,  
				"idtransaksi" => $id,"datamatauang" => $dtMataUang,
				"notransaksi" => $notransaksi, "datatop" => $dtTOP, "dataimportir" => $dtImportir,
				"datashipper" => $dtShipper, "datacustomer" => $dtCustomer,
				"databank" => $dtBank, "datarekening" => $dtRekening,
				"datapenerima" => $dtPenerima,
				"detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
			];		
			
		$this->render("transaksibayar.php", $data);
	}
	public function edithasilbongkar($id = "")
    {		
        $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Edit Hasil Bongkar");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/app/edithasilbongkar.js";
		$this->loadModel("Transaksi");		
		$dtJenisKemasan = $this->transaksi->getJenisKemasan();
		$dtJenisDokumen = $this->transaksi->getJenisDokumen();
		$dtStatusRevisi = $this->transaksi->getStatusRevisi();
		$dtCustomer = $this->transaksi->getCustomer();
		$dtTransaksi = Array();
		if ($id != ""){
			$dtTransaksi = $this->transaksi->getTransaksi($id);
			$notransaksi = " No. " .sprintf('%08d', $dtTransaksi["header"]->ID);
		}		
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb, 				 				
				"jeniskemasan" => $dtJenisKemasan, 
				"jenisdokumen" => $dtJenisDokumen, 
				"statusrevisi" => $dtStatusRevisi,
				"customer" => $dtCustomer,
				"idtransaksi" => $id, "userlevel" => $this->getSessionValue("userlevel"),
				"notransaksi" => $notransaksi,
			];		
		$this->render("edithasilbongkar.php", $data);
	}
	public function search()
    {
        $assets = $this->prepareAssets();
        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Cari Transaksi");
		$this->loadModel("Transaksi");
		$kantor = $this->transaksi->getKantor();
		$customer = $this->transaksi->getCustomer();

		$assets["scripts"][] = "/web/assets/app/daftartransaksi.js";
		$this->render("search.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
									"scripts" => $assets["scripts"],
									"kodekantor" => $kantor, "customer" => $customer]);
	}
	public function searchproduk()
    {
        $assets = $this->prepareAssets();
        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Cari Produk");
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		
		$this->render("searchbarang.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"]]);
	}
	public function browse()
    {
        $assets = $this->prepareAssets();
        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Browse Schedule");
		$this->loadModel("Transaksi");
		$kantor = $this->transaksi->getKantor();
		$customer = $this->transaksi->getCustomer();
		$importir = $this->getImportir();
				
		$this->render("browse.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
									"scripts" => $assets["scripts"],
									"datakantor" => $kantor, "datacustomer" => $customer,
									"dataimportir" => $importir, 
									"datakategori" => Array("Tanggal Tiba","Tanggal Keluar",
															"Tanggal Nopen")]);
	}
	public function hasilbongkar()
    {
        $assets = $this->prepareAssets();
        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman Hasil Bongkar");
		$this->loadModel("Transaksi");
		$kantor = $this->transaksi->getKantor();
		$customer = $this->transaksi->getCustomer();
		$gudang = $this->transaksi->getGudang();
				
		$assets["scripts"][] = "/web/assets/app/hasilbongkar.js";
		$this->render("hasilbongkar.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
									"scripts" => $assets["scripts"],
									"datakantor" => $kantor, "datacustomer" => $customer,
									"datagudang" => $gudang,
									"userlevel" => $this->getSessionValue("userlevel"),
									"datakategori1" => Array("Aju Dok In","Nopen Dok In",
															"Hasil Bongkar"),
									"datakategori2" => Array("Tanggal Bongkar","Tanggal Tiba",
															"Tanggal Nopen Dok In")															
									]);
	}
	public function perekamanvo()
    {
		$filter = $this->get("filter");
		if ($filter && $filter == "1"){
			$postKantor = $this->post("kantor");
			$postKategori = $this->post("kategori");
			$postCustomer = $this->post("customer");
			$postImportir = $this->post("importir");			
			$postKategori1 = $this->post("kategori1");
			$isikategori1 = $this->post("isikategori1");
			$postKategori2 = $this->post("kategori2");
			$dari2 = $this->post("dari2");
			$sampai2 = $this->post("sampai2");
			$this->setSessionValue("browsevo", [
				"kantor" => $postKantor,
				"kategori" => $postKategori,
				"customer" => $postCustomer,
				"importir" => $postImportir,
				"kategori1" => $postKategori1,
				"isikategori1" => $isikategori1,
				"kategori2" => $postKategori2,
				"dari2" => $dari2, "sampai2" => $sampai2
			]);		
			$this->loadModel("Transaksi");		
			$data = $this->transaksi->browseVo($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
					$sheet->setCellValue('A2', 'CUSTOMER');
					if ($postCustomer && trim($postCustomer) != ""){
						$sheet->setCellValue('C2', $this->transaksi->getCustomer($postCustomer)->nama_customer);
					}
					else {
						$sheet->setCellValue('C2', "Semua");
					}
					$sheet->setCellValue('A3', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C3', $this->transaksi->getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C3', "Semua");
					}
					$lastrow = 3;
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
					$lastrow += 2;
					$sheet->setCellValue('A' .$lastrow, 'Importir');
					$sheet->setCellValue('B' .$lastrow, 'Customer');
					$sheet->setCellValue('C' .$lastrow, 'No Inv');
					$sheet->setCellValue('D' .$lastrow, 'No. VO');
					$sheet->setCellValue('E' .$lastrow, 'Tgl VO');
					$sheet->setCellValue('F' .$lastrow, 'Tgl Tiba');
					$sheet->setCellValue('G' .$lastrow, 'Nopen');
					$sheet->setCellValue('H' .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue('I' .$lastrow, 'Kode HS');
					$sheet->setCellValue('J' .$lastrow, 'Tgl Periksa');
					$sheet->setCellValue('K' .$lastrow, 'Tgl LS');
					$sheet->setCellValue('L' .$lastrow, 'Status VO');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->IMPORTIR);
						$sheet->setCellValue('B' .$lastrow, $dt->CUSTOMER);
						$sheet->setCellValue('C' .$lastrow, $dt->NO_INV);
						$sheet->setCellValue('D' .$lastrow, $dt->NO_VO);
						$sheet->setCellValue('E' .$lastrow, $dt->TGLVO);
						$sheet->setCellValue('F' .$lastrow, $dt->TGLTIBA);
						$sheet->setCellValue('G' .$lastrow, $dt->NOPEN);
						$sheet->setCellValue('H' .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue('I' .$lastrow, $dt->KODE_HS_VO);
						$sheet->setCellValue('J' .$lastrow, $dt->TGLPERIKSAVO);
						$sheet->setCellValue('K' .$lastrow, $dt->TGLLS);
						$sheet->setCellValue('L' .$lastrow, $dt->STATUSVO);
					}
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="browsevo.xlsx"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$writer = new Xlsx($spreadsheet);
					$writer->save('php://output');
				}
				else {
					header('Content-Type: application/json');
					print json_encode($data);
				}
			}
			else {
				header('Content-Type: application/json');
				print json_encode([]);
			}
		}
		else {
			$filters = $this->getSessionValue("browsevo");
			$assets = $this->prepareAssets();
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Perekaman VO");
			$this->loadModel("Transaksi");
			$kantor = $this->transaksi->getKantor();
			$customer = $this->transaksi->getCustomer();			

			$importir = $this->getImportir();
			$this->render("perekamanvo.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
										"scripts" => $assets["scripts"],
										"datakantor" => $kantor, "datacustomer" => $customer,
										"dataimportir" => $importir, "filters" => $filters,								
										"datakategori1" => Array("No Inv","No VO",
																"Status VO"),
										"datakategori2" => Array("Tanggal Periksa", "Tanggal LS", "Tanggal VO","Tanggal Nopen","Tanggal Tiba")															
										]);
		}        
	}
	public function perekamanbayar()
    {
		$filter = $this->get("filter");
		if ($filter && $filter == "1"){
			$postKantor = $this->post("kantor");
			$postKategori = $this->post("kategori");
			$postCustomer = $this->post("customer");
			$postImportir = $this->post("importir");			
			$postKategori1 = $this->post("kategori1");
			$isikategori1 = $this->post("isikategori1");
			$postKategori2 = $this->post("kategori2");
			$dari2 = $this->post("dari2");
			$sampai2 = $this->post("sampai2");		
			$this->loadModel("Transaksi");		
			$data = $this->transaksi->browseBayar($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2,$dari2, $sampai2);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){

					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
					$sheet->setCellValue('A2', 'CUSTOMER');
					if ($postCustomer && trim($postCustomer) != ""){
						$sheet->setCellValue('C2', $this->transaksi->getCustomer($postCustomer)->nama_customer);
					}
					else {
						$sheet->setCellValue('C2', "Semua");
					}
					$sheet->setCellValue('A3', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C3', $this->transaksi->getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C3', "Semua");
					}
					$lastrow = 3;
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
					$sheet->setCellValue('A' .$lastrow, 'Importir');
					$sheet->setCellValue('B' .$lastrow, 'Customer');
					$sheet->setCellValue('C' .$lastrow, 'No Inv');
					$sheet->setCellValue('D' .$lastrow, 'TT/Non TT');
					$sheet->setCellValue('E' .$lastrow, 'TOP');
					$sheet->setCellValue('F' .$lastrow, 'Jth Tempo');
					$sheet->setCellValue('G' .$lastrow, 'Curr');
					$sheet->setCellValue('H' .$lastrow, 'CIF');
					$sheet->setCellValue('I' .$lastrow, 'Nominal');
					$sheet->setCellValue('J' .$lastrow, 'Saldo');	
					$sheet->setCellValue('K' .$lastrow, 'Faktur');					

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->IMPORTIR);
						$sheet->setCellValue('B' .$lastrow, $dt->CUSTOMER);
						$sheet->setCellValue('C' .$lastrow, $dt->NO_INV);
						$sheet->setCellValue('D' .$lastrow, $dt->PEMBAYARAN != '' ? ($dt->PEMBAYARAN == 'Y' ? 'TT' : 'Non TT') : '');
						$sheet->setCellValue('E' .$lastrow, $dt->TERM);
						$sheet->setCellValue('F' .$lastrow, $dt->TGLJTHTEMPO);
						$sheet->setCellValue('G' .$lastrow, $dt->MATAUANG);
						$sheet->setCellValue('H' .$lastrow, $dt->CIF);
						$sheet->setCellValue('I' .$lastrow, $dt->BAYAR);
						$sheet->setCellValue('J' .$lastrow, $dt->CIF - $dt->BAYAR);	
						$sheet->setCellValue('K' .$lastrow, $dt->FAKTUR);
						$detail = $this->transaksi->getDetailBayar($dt->ID);						
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
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="browsebayar.xlsx"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$writer = new Xlsx($spreadsheet);
					$writer->save('php://output');
				}
				else {
					header('Content-Type: application/json');
					print json_encode($data);
				}
			}
			else {
				header('Content-Type: application/json');
				print json_encode([]);
			}
		}
		else {
			$assets = $this->prepareAssets();
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Perekaman Pembayaran");
			$this->loadModel("Transaksi");
			$kantor = $this->transaksi->getKantor();
			$customer = $this->transaksi->getCustomer();
			$importir = $this->getImportir();
			$top = $this->transaksi->getTOP();
					
			$this->render("perekamanbayar.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
										"scripts" => $assets["scripts"],
										"datakantor" => $kantor, "datacustomer" => $customer,
										"dataimportir" => $importir,"top" => $top,								
										"datakategori1" => Array("No Inv","TOP","TT/Non TT"),
										"datakategori2" => Array("Tanggal Jatuh Tempo")															
										]);
		}        
	}
	public function perekamando()
    {
		$filter = $this->get("filter");
		if ($filter && $filter == "1"){
			$postKantor = $this->post("kantor");
			$postCustomer = $this->post("customer");
			$postImportir = $this->post("importir");			
			$postKategori1 = $this->post("kategori1");
			$isikategori1 = $this->post("isikategori1");
			$postKategori2 = $this->post("kategori2");
			$dari2 = $this->post("dari2");
			$sampai2 = $this->post("sampai2");		
			$this->loadModel("Transaksi");		
			$data = $this->transaksi->browseDo($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
					$sheet->setCellValue('A2', 'CUSTOMER');
					if ($postCustomer && trim($postCustomer) != ""){
						$sheet->setCellValue('C2', $this->transaksi->getCustomer($postCustomer)->nama_customer);
					}
					else {
						$sheet->setCellValue('C2', "Semua");
					}
					$sheet->setCellValue('A3', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C3', $this->transaksi->getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C3', "Semua");
					}
					$lastrow = 3;
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
					$sheet->setCellValue('A' .$lastrow, 'Importir');
					$sheet->setCellValue('B' .$lastrow, 'Customer');
					$sheet->setCellValue('C' .$lastrow, 'No Inv');
					$sheet->setCellValue('D' .$lastrow, 'No PO');
					$sheet->setCellValue('E' .$lastrow, 'No SC');
					$sheet->setCellValue('F' .$lastrow, 'No BL');
					$sheet->setCellValue('G' .$lastrow, 'Tgl BL');
					$sheet->setCellValue('H' .$lastrow, 'Tgl Tiba');
					$sheet->setCellValue('I' .$lastrow, 'No. Form');
					$sheet->setCellValue('J' .$lastrow, 'Tgl Dok Trm');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->IMPORTIR);
						$sheet->setCellValue('B' .$lastrow, $dt->CUSTOMER);
						$sheet->setCellValue('C' .$lastrow, $dt->NO_INV);
						$sheet->setCellValue('D' .$lastrow, $dt->NO_PO);
						$sheet->setCellValue('E' .$lastrow, $dt->NO_SC);
						$sheet->setCellValue('F' .$lastrow, $dt->NO_BL);
						$sheet->setCellValue('G' .$lastrow, $dt->TGLBL);
						$sheet->setCellValue('H' .$lastrow, $dt->TGLTIBA);
						$sheet->setCellValue('I' .$lastrow, $dt->NO_FORM);
						$sheet->setCellValue('J' .$lastrow, $dt->TGLDOKTRM);
					}
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="browsedokumen.xlsx"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$writer = new Xlsx($spreadsheet);
					$writer->save('php://output');
				}
				else {
					header('Content-Type: application/json');
					print json_encode($data);
				}
			}
			else {
				header('Content-Type: application/json');
				print json_encode([]);
			}
		}
		else {
			$assets = $this->prepareAssets();
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Perekaman Dokumen");
			$this->loadModel("Transaksi");
			$kantor = $this->transaksi->getKantor();
			$customer = $this->transaksi->getCustomer();
			$importir = $this->getImportir();
			$this->render("perekamando.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
										"scripts" => $assets["scripts"],
										"datakantor" => $kantor, "datacustomer" => $customer,
										"dataimportir" => $importir,								
										"datakategori1" => Array("No Inv","No BL","No VO"),
										"datakategori2" => Array("Tanggal BL","Tanggal Tiba")															
										]);
		}        
	}
	public function perekamanbc()
    {
		        
	}
	public function filter()
	{
		$postKantor = $this->post("kantor");
		$postCustomer =$this->post("customer");
		$postImportir = $this->post("importir");
		$postKategori1 = $this->post("kategori1");
		$dari1 = $this->post("dari1");
		$sampai1 = $this->post("sampai1");
		$postKategori2 = $this->post("kategori2");
		$dari2 = $this->post("dari2");
		$sampai2 = $this->post("sampai2");
		$export = $this->get("export");
		
		$this->loadModel("Transaksi");		
		$data = $this->transaksi->browse($postKantor, $postCustomer, $postImportir, $postKategori1,
								$dari1, $sampai1, $postKategori2, $dari2, $sampai2);
		if ($data){
			if ($export == '1'){
				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setCellValue('A1', 'KANTOR');
				$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
				$sheet->setCellValue('A2', 'CUSTOMER');
				if ($postCustomer && trim($postCustomer) != ""){
					$sheet->setCellValue('C2', $this->transaksi->getCustomer($postCustomer)->nama_customer);
				}
				else {
					$sheet->setCellValue('C2', "Semua");
				}
				$sheet->setCellValue('A3', 'IMPORTIR');
				if ($postImportir && trim($postImportir) != ""){
					$sheet->setCellValue('C3', $this->transaksi->getImportir($postImportir)->NAMA);
				}
				else {
					$sheet->setCellValue('C3', "Semua");
				}
				$lastrow = 4;
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
				$sheet->setCellValue('A' .$lastrow, 'No. Inv');
				$sheet->setCellValue('B' .$lastrow, 'No. BL');
				$sheet->setCellValue('C' .$lastrow, 'Jml Kmsn');
				$sheet->setCellValue('D' .$lastrow, 'Customer');
				$sheet->setCellValue('E' .$lastrow, 'Importir');
				$sheet->setCellValue('F' .$lastrow, 'Tgl Tiba');				
				$sheet->setCellValue('G' .$lastrow, 'Tgl Keluar');
				$sheet->setCellValue('H' .$lastrow, 'No.Aju');
				$sheet->setCellValue('I' .$lastrow, 'Nopen');
				$sheet->setCellValue('J' .$lastrow, 'Tgl Nopen');
				$sheet->setCellValue('K' .$lastrow, 'No. PO');
				$sheet->setCellValue('L' .$lastrow, 'No. SC');
				$sheet->setCellValue('M' .$lastrow, 'Jml Kont');

				foreach ($data as $dt){
					$lastrow += 1;
					$sheet->setCellValue('A' .$lastrow, $dt->NO_INV);
					$sheet->setCellValue('B' .$lastrow, $dt->NO_BL);
					$sheet->setCellValue('C' .$lastrow, $dt->JUMLAH_KEMASAN);
					$sheet->setCellValue('D' .$lastrow, $dt->NAMA);
					$sheet->setCellValue('E' .$lastrow, $dt->IMPORTIR);
					$sheet->setCellValue('F' .$lastrow, $dt->TGLTIBA);
					$sheet->setCellValue('G' .$lastrow, $dt->TGLKELUAR);
					$sheet->setCellValue('H' .$lastrow, $dt->NOAJU);
					$sheet->setCellValue('I' .$lastrow, $dt->NOPEN);
					$sheet->setCellValue('J' .$lastrow, $dt->TGLNOPEN);
					$sheet->setCellValue('K' .$lastrow, $dt->NO_PO);
					$sheet->setCellValue('L' .$lastrow, $dt->NO_SC);
					$sheet->setCellValue('M' .$lastrow, $dt->JUMLAH_KONTAINER);
				}
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="browse.xlsx"');
				header('Cache-Control: max-age=0');
				// If you're serving to IE 9, then the following may be needed
				header('Cache-Control: max-age=1');

				// If you're serving to IE over SSL, then the following may be needed
				header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
				header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
				header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
				header ('Pragma: public'); // HTTP/1.0

				$writer = new Xlsx($spreadsheet);
				$writer->save('php://output');
			}
			else {
				header('Content-Type: application/json');
				print json_encode($data);
			}
		}
		else {
			header('Content-Type: application/json');
			if ($export == '1'){
				print json_encode(["message" => "Tidak ada data yang dieksport"]);
			}
			else {
				print json_encode([]);
			}
		}
	}
	public function filterbongkar()
	{
		$postKantor = $this->get("kantor");
		$postCustomer =$this->get("customer");
		$postGudang = $this->get("gudang");
		$postKategori1 = $this->get("kategori1");
		$isikategori1 = $this->get("isikategori1");
		$postKategori2 = $this->get("kategori2");
		$dari2 = $this->get("dari2");
		$sampai2 = $this->get("sampai2");		
		$this->loadModel("Transaksi");		
		$data = $this->transaksi->hasilbongkar($postKantor, $postGudang, $postCustomer, $postKategori1,
								$isikategori1, $postKategori2, $dari2, $sampai2);
		header('Content-Type: application/json');
		print json_encode($data);					
	}	
	public function find()
	{
		$term = $this->get("term");
		$searchtype = $this->get("searchtype");
		$kantor = $this->get("kantor");
		
		$filter = null;
		if (isset($kantor)){
			$filter = Array("tgltiba1" => $this->get("tgltiba1"),
							"tgltiba2" => $this->get("tgltiba2"),
							"tglbongkar1" => $this->get("tglbongkar1"),
							"tglbongkar2" => $this->get("tglbongkar2"),
							"customer" => $this->get("customer"),
							"kantor" => $kantor);
		}			
		else {			
			$kantor = null;
		}
		$this->loadModel("Transaksi");
		$data = $this->transaksi->search($term,$searchtype, $filter);		
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function findproduk()
	{
		$hscode = $this->get("hscode");
		$rangefrom = $this->get("rangefrom");
		$rangeto = $this->get("rangeto");
		
		$this->loadModel("Produk");
		$data = $this->produk->search($hscode,$rangefrom, $rangeto);		
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function searchsptnp()
	{
		$nopen = $this->get("nopen");
		$tglnopen = $this->get("tglnopen");				
		$this->loadModel("Transaksi");
		$data = $this->transaksi->searchsptnp($nopen,$tglnopen);		
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function searchkontainer()
	{
		$assets = $this->prepareAssets();
        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Cari Transaksi Berdasarkan Nomor Kontainer");
		$assets["scripts"][] = "/web/assets/app/daftartransaksi.js";
		$this->render("searchkontainer.php",["breads" => $breadcrumb, 
									"stylesheets" => $assets["stylesheets"], 
									"scripts" => $assets["scripts"]]);		
	}
	public function perekamansptnp()
	{
		$assets = $this->prepareAssets();
        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman SPTNP");
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/app/sptnp.js";
		$this->render("sptnp.php",["breads" => $breadcrumb, 
									"stylesheets" => $assets["stylesheets"], 
									"scripts" => $assets["scripts"]]);		
	}
	public function get_daftar()
	{		
		$search = $this->post("search");
		$draw = $this->post("draw");
		$start = $this->post("start");
		$length = $this->post("length");
		$order = $this->post("order");
		$this->loadModel("Transaksi");
		$fetch = $this->transaksi->getData($search, $start, $length, $order);
		$num_rows = $this->transaksi->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
								"recordsFiltered" => $num_rows,
								"recordsTotal" => $num_filtered,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function daftarproduk()
	{		
		$search = $this->post("search");
		$draw = $this->post("draw");
		$start = $this->post("start");
		$length = $this->post("length");
		$order = $this->post("order");
		$this->loadModel("Produk");
		$fetch = $this->produk->getDataProduk($search, $start, $length, $order);
		$num_rows = $fetch['count'];
		$num_filtered = $fetch['data']->num_rows();
		$data = Array("draw" => $draw,
								"recordsFiltered" => $num_rows,
								"recordsTotal" => $num_filtered,
					  "data" => $fetch['data']->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function crud()
	{
		$this->loadModel("Transaksi");
		$postheader = $this->post("header");	
		$type = $this->post("type");		
		$message = Array();
		$this->db->startTrans();
		try {
			if (!$type){
				if ($postheader){
					$kontainer = $this->post("kontainer");
					parse_str($postheader, $header);
					if ($header["idtransaksi"] == ""){
						$action = "insert";
					}
					else {
						$action = "update";
					}
					$id = $this->transaksi->saveTransaksi($action, $header, $kontainer);
				}
				else {
					$id = $this->post("delete");
					if ($id && $id != ""){
						$this->transaksi->deleteTransaksi($id);
					}
				}
			}
			else if ($type == "bayar"){
				if ($postheader){
					$detail = $this->post("detail");
					parse_str($postheader, $header);
					if ($header["idtransaksi"] == ""){
						$action = "insert";
					}
					else {
						$action = "update";
					}
					$id = $this->transaksi->saveTransaksiBayar($action, $header, $detail);
				}		
				else {
					$id = $this->post("delete");
					if ($id && $id != ""){
						$this->transaksi->deleteTransaksiBayar($id);
					}
				}						
			}
			else if ($type == "deliveryorder"){
				if ($postheader){
					$detail = $this->post("detail");
					parse_str($postheader, $header);
					if ($header["idtransaksi"] == ""){
						$action = "insert";
					}
					else {
						$action = "update";
					}
					$id = $this->transaksi->saveTransaksiDOrder($action, $header, $detail);
				}		
				else {
					$id = $this->post("delete");
					if ($id && $id != ""){
						$this->transaksi->deleteTransaksiDOrder($id);
					}
				}						
			}
			else if ($type == "pengeluaran"){
				if ($postheader){
					$id = $this->post("do_id");
					$this->transaksi->savePengeluaran($id, $postheader);
				}		
			}
			else if ($type == "userdo"){				
				if ($postheader){
					parse_str($postheader, $header);
					$postfiles = $this->post('files');					
					$id = $this->transaksi->saveTransaksiDo($header, $postfiles);
				}				  		
			}
			else if ($type == "barang"){				
				if ($postheader){
					$detail = $this->post("detail");
					parse_str($postheader, $header);					
					//$postfiles = $this->post('files');					
					$id = $this->transaksi->saveTransaksiBarang($header, $detail /*, $postfiles*/);
				}				  		
			}
			else if ($type == "konversi"){				
				if ($postheader){
					$detail = $this->post("detail");
					parse_str($postheader, $header);						
					$id = $this->transaksi->saveTransaksiKonversi($header, $detail);
				}				  		
			}
			else if ($type == "mutasi"){				
				if ($postheader){
					$detail = $this->post("detail");
					parse_str($postheader, $header);					
					$postfiles = $this->post('files');					
					$id = $this->transaksi->saveMutasiBarang($header, $detail, $postfiles);
				}				  		
			}
			else if ($type == "uservo"){
				if ($postheader){
					parse_str($postheader, $header);
					$id = $this->transaksi->saveTransaksiVo($header);
				}	
			}
			else if ($type == "usersptnp"){
				if ($postheader){
					parse_str($postheader, $header);
					$id = $this->transaksi->saveTransaksiSPTNP($header);
				}	
			}
			else if ($type == "userbc"){
				if ($postheader){
					parse_str($postheader, $header);
					$id = $this->transaksi->saveTransaksiBC($header);
				}	
			}
			$this->db->commitTrans();
			$message["result"] = $id;
		}
		catch (\Exception $e){
			$this->db->rollbackTrans();
			$message["error"] = $e->getMessage();
			$this->logger->log("error", $e->getMessage());
		}		
		header('Content-Type: application/json');
		print json_encode($message);
	}	
	public function savehasilbongkar()
	{
		$this->loadModel("Transaksi");
		$postheader = $this->post("header");		
		$message = Array();
		$this->db->startTrans();
		try {
			if ($postheader){
				$kontainer = $this->post("kontainer");
				parse_str($postheader, $header);
				$id = $this->transaksi->updateHasilBongkar($header);
			}
			$this->db->commitTrans();
			$message["result"] = $id;  
		}
		catch (\Exception $e){
			$this->db->rollbackTrans();
			$message["error"] = $e->getMessage();
		}		
		header('Content-Type: application/json');
        print json_encode($message);
	}
	public function delete()
	{
		$id = $this->post("iddelete");
		$message = Array();
		if ($id && $id != ""){
			$this->loadModel("Transaksi"); 
			$this->db->startTrans();
			try {				
				$this->transaksi->deleteTransaksi($id);
				$message["result"] = $id;
				$this->db->commitTrans();
			}
			catch (Exception $e){
				$message["error"] = $e->getMessage();
				$this->db->rollbackTrans();
			}
		}
		header('Content-Type: application/json');
        print json_encode($message);
	}
	public function approve()
	{
		$id = $this->post("id");
		$message = Array();
		if ($id){
			$this->loadModel("Transaksi");
			try {
				$this->transaksi->approve($this->getSessionValue("logged_in"), $id);
				$message["result"] = "0";
			}
			catch (Exception $e){
				$message["error"] = $e->getMessage();
			}
		}
		else {
			$message["error"] = "Id tidak ditemukan";
		}
		header('Content-Type: application/json');
        print json_encode($message);
	}
	public function upload()
	{
		$file = $this->files('file'); 		
		if ($file->isValid()){
			$realname = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$this->loadModel("Transaksi");							
			$this->db->startTrans();			
			try {
				$id = $this->transaksi->saveFile($realname, $extension);
				$file->move(ROOT_DIR ."uploads", $id."." .$extension);
				$this->db->commitTrans();
				print json_encode(["id" => $id]);
			}
			catch (Exception $e){
				$this->db->rollbackTrans();
			}
		}
	}
	public function removeFile()
	{
		$id = $this->post("id");
		$this->loadModel("Transaksi");
		$this->db->startTrans();
		try {			
			$this->transaksi->deleteFile($id);					
			$this->db->commitTrans();
		}
		catch (Extension $e){
			$this->db->rollbackTrans();
		}
	}
	public function getFile()
	{
		$file = $this->get("file");
		$this->loadModel("Transaksi");
		$dtFile = $this->transaksi->query("SELECT FILENAME from tbl_files WHERE ID = '$file'");
		if ($dtFile->num_rows() > 0){
			$content = file_get_contents(ROOT_DIR .'uploads/' .$dtFile->current()->FILENAME);
			//$response = new BinaryFileResponse(ROOT_DIR .'uploads/' .$dtFile->current()->FILENAME);
			header('Content-Disposition: attachment;filename="' .$dtFile->current()->FILENAME .'"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			echo $content;
		}
	}
	public function getPerekamanFiles()
	{
		$perekaman_id = $this->get("id");
		$this->loadModel("Transaksi");
		$dtFile = $this->transaksi->query("SELECT * from tbl_files WHERE ID_HEADER = '$perekaman_id'")->get();
		echo json_encode($dtFile);
	}
	public function cron()
	{
		$action = $this->get('action');
		if ($action == 'deletefiles'){
			$this->loadModel("Transaksi");
			$data = $this->transaksi->query("SELECT FILENAME FROM tbl_files WHERE ID_HEADER IS NULL")->get();			
			foreach ($data as $dt){
				unlink(ROOT_DIR ."uploads/" .$dt->FILENAME);
			}
			$this->transaksi->delete("ID_HEADER IS NULL");
		}
	}
	public function searchinv()
	{
		$inv = $this->get("inv");
		$this->loadModel("Transaksi");
		$data = $this->transaksi->getInv($inv);		
		if (!$data){
			$response["error"] = "No Inv tidak ada";
		}
		else {
			$response = $data;
		}
		header('Content-Type: application/json');
		print json_encode($response);
	}
	public function detailbayar()
	{
		$id = $this->post("id");
		$this->loadModel("Transaksi");
		$data = $this->transaksi->getDetailBayar($id);
		if (!$data){
			$response["data"] = [];
		}
		else {
			$response["data"] = $data;
		}
		header('Content-Type: application/json');
		print json_encode($response);
	}
	public function konversi()
	{
		$id = $this->post("id");
		$this->loadModel("Transaksi");
		$data = $this->transaksi->getKonversi($id);
		if (!$data){
			$response["data"] = [];
		}
		else {
			$response["data"] = $data;
		}
		header('Content-Type: application/json');
		print json_encode($response);
	}
	public function kartuHutang()
    {
        $filter = $this->get("filter");
		if ($filter && $filter == "1"){
			$postKantor = $this->post("kantor");
			$postKategori = $this->post("kategori");
			$postCustomer = $this->post("customer");
            $postImportir = $this->post("importir");
            $postShipper = $this->post("shipper");			
			//$postKategori1 = $this->post("kategori1");
			//$isikategori1 = $this->post("isikategori1");
			$postKategori2 = $this->post("kategori2");
			$dari2 = $this->post("dari2");
			$sampai2 = $this->post("sampai2");	
            $this->loadModel("Transaksi");		
			$data = $this->transaksi->kartuHutang($postKantor, $postCustomer, $postImportir, $postShipper,
									$postKategori2,$dari2, $sampai2);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->setCellValue('A1', 'KANTOR');
                    if ($postKantor && trim($postKantor) != ""){
                        $sheet->setCellValue('A1', $this->transaksi->getKantor($postKantor)->URAIAN);
                    }
                    else {
                        $sheet->setCellValue('C1', "Semua");
                    }					
					$sheet->setCellValue('A2', 'CUSTOMER');
					if ($postCustomer && trim($postCustomer) != ""){
						$sheet->setCellValue('C2', $this->transaksi->getCustomer($postCustomer)->nama_customer);
					}
					else {
						$sheet->setCellValue('C2', "Semua");
					}
					$sheet->setCellValue('A3', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C3', $this->transaksi->getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C3', "Semua");
                    }
                    $sheet->setCellValue('A4', 'SHIPPER');
					if ($postShipper && trim($postShipper) != ""){
						$sheet->setCellValue('C4', $this->transaksi->getShipper($postShipper)->nama_pemasok);
					}
					else {
						$sheet->setCellValue('C4', "Semua");
					}
                    $lastrow = 4;
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
                    $sheet->setCellValue('A' .$lastrow, 'Kantor');
					$sheet->setCellValue('B' .$lastrow, 'Importir');
                    $sheet->setCellValue('C' .$lastrow, 'Customer');
                    $sheet->setCellValue('D' .$lastrow, 'Shipper');
                    $sheet->setCellValue('E' .$lastrow, 'Jns Dok');
                    $sheet->setCellValue('F' .$lastrow, 'Nopen');
                    $sheet->setCellValue('G' .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue('H' .$lastrow, 'No Inv');
					$sheet->setCellValue('I' .$lastrow, 'Tgl Inv');
					$sheet->setCellValue('J' .$lastrow, 'Jth Tempo');
					$sheet->setCellValue('K' .$lastrow, 'Curr');
					$sheet->setCellValue('L' .$lastrow, 'CIF');
					$sheet->setCellValue('M' .$lastrow, 'Payment');
					$sheet->setCellValue('N' .$lastrow, 'Saldo');

					foreach ($data as $dt){
                        $lastrow += 1;
                        $sheet->setCellValue('A' .$lastrow, $dt->KANTOR);
						$sheet->setCellValue('B' .$lastrow, $dt->IMPORTIR);
                        $sheet->setCellValue('C' .$lastrow, $dt->CUSTOMER);
                        $sheet->setCellValue('D' .$lastrow, $dt->SHIPPER);
                        $sheet->setCellValue('E' .$lastrow, $dt->JENISDOKUMEN);
                        $sheet->setCellValue('F' .$lastrow, $dt->NOPEN);
                        $sheet->setCellValue('G' .$lastrow, $dt->TGLNOPEN);
                        $sheet->setCellValue('H' .$lastrow, $dt->NO_INV);
                        $sheet->setCellValue('I' .$lastrow, $dt->TGLINV);
						$sheet->setCellValue('J' .$lastrow, $dt->TGLJTHTEMPO);
						$sheet->setCellValue('K' .$lastrow, $dt->MATAUANG);
						$sheet->setCellValue('L' .$lastrow, $dt->CIF);
						$sheet->setCellValue('M' .$lastrow, $dt->TOT_PAYMENT);
						$sheet->setCellValue('N' .$lastrow, $dt->SALDO);	
						$detail = $this->transaksi->getDetailBayar($dt->ID);						
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
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="kartuhutang.xlsx"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$writer = new Xlsx($spreadsheet);
					$writer->save('php://output');
				}
				else {
					header('Content-Type: application/json');
					print json_encode($data);
				}
			}
			else {
				header('Content-Type: application/json');
				print json_encode([]);
			}
		}
		else {
			$assets = $this->prepareAssets();
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Kartu Hutang");
            $this->loadModel("Transaksi");
			$kantor = $this->transaksi->getKantor();
			$customer = $this->transaksi->getCustomer();
            $importir = $this->transaksi->getImportir();            
            $shipper = $this->transaksi->getShipper();
					
			$assets["scripts"][] = "/web/assets/app/kartuhutang.js";
			$this->render("kartuhutang.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
										"scripts" => $assets["scripts"],
                                        "datakantor" => $kantor, "datacustomer" => $customer,
                                        "datashipper" => $shipper,
										"dataimportir" => $importir,								
										//"datakategori1" => Array("No Inv","TOP","TT/Non TT"),
										"datakategori2" => Array("Tgl Jatuh Tempo","Tgl Inv","Tgl Nopen")															
										]);
		}
	}
	public function userbarang($id = "")
    {		
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/perekamanbarang", "text" => "Perekaman Barang");
		$breadcrumb[] = Array("text" => "Transaksi Perekaman Barang");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/js/dropzone.min.js";		
		$assets["stylesheets"][] = "/web/assets/css/dropzone.min.css";
		$this->loadModel("Produk");
		$this->loadModel("Satuan");
		$this->loadModel("Transaksi");
		$dtCustomer = $this->transaksi->getCustomer();
		$dtSatuan = $this->satuan->getSatuan();
		$dtJenisKemasan = $this->transaksi->getJenisKemasan();
		$dtJenisDokumen = $this->transaksi->getJenisDokumen();
		$dtImportir = $this->transaksi->getImportir();
		$dtMataUang = $this->transaksi->getMataUang();
		$dtJenisFile = $this->transaksi->getJenisFile();
		$dtTransaksi = Array();
		if ($id != ""){
			$dtTransaksi = $this->transaksi->getTransaksiBarang($id);
			$dtFiles = $this->transaksi->getFiles($dtTransaksi["header"]->ID);
		}
		$dtProduk = $this->produk->getProduk();
		$dtSatuan = $this->satuan->getSatuan();
						
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb,  
				"idtransaksi" => $id,"dataproduk" => $dtProduk, "importir" => $dtImportir,
				"jeniskemasan" => $dtJenisKemasan, "customer" => $dtCustomer,
				"jenisdokumen" => $dtJenisDokumen, "matauang" => $dtMataUang,
				"files" => isset($dtFiles) ? $dtFiles : "{}", "datasatuan" => $dtSatuan,
				"jenisfile" => $dtJenisFile,
				"detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
			];		
		$this->render("transaksibarang.php", $data);
	}
	public function userbarangkonversi($id = "")
    {		
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/konversibarang", "text" => "Konversi Barang");
		$breadcrumb[] = Array("text" => "Transaksi Konversi Barang");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/js/dropzone.min.js";		
		$assets["stylesheets"][] = "/web/assets/css/dropzone.min.css";
		$this->loadModel("Produk");
		$this->loadModel("Satuan");
		$this->loadModel("Transaksi");
		$dtCustomer = $this->transaksi->getCustomer();
		$dtSatuan = $this->satuan->getSatuan();
		$dtJenisKemasan = $this->transaksi->getJenisKemasan();
		$dtJenisDokumen = $this->transaksi->getJenisDokumen();
		$dtImportir = $this->transaksi->getImportir();
		$dtMataUang = $this->transaksi->getMataUang();
		$dtJenisFile = $this->transaksi->getJenisFile();
		$dtTransaksi = Array();
		if ($id != ""){
			$dtTransaksi = $this->transaksi->getTransaksiBarang($id);
			$dtFiles = $this->transaksi->getFiles($dtTransaksi["header"]->ID);
		}
		$dtProduk = $this->produk->getProduk();
		$dtSatuan = $this->satuan->getSatuan();
						
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb,  
				"idtransaksi" => $id,"dataproduk" => $dtProduk, "importir" => $dtImportir,
				"jeniskemasan" => $dtJenisKemasan, "customer" => $dtCustomer,
				"jenisdokumen" => $dtJenisDokumen, "matauang" => $dtMataUang,
				"files" => isset($dtFiles) ? $dtFiles : "{}", "datasatuan" => $dtSatuan,
				"jenisfile" => $dtJenisFile,
				"detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
			];		
		$this->render("transaksibarangkonversi.php", $data);
	}
	public function userkonversi($id = "")
    {		
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/js/dropzone.min.js";		
		$assets["stylesheets"][] = "/web/assets/css/dropzone.min.css";
		$this->loadModel("Produk");
		$this->loadModel("Transaksi");
		$dtTransaksi = Array();
		if ($id != ""){
			$dtTransaksi = $this->transaksi->getKonversi($id);
		}
		$breadcrumb[] = Array("link" => "/transaksi/userbarangkonversi/" .$dtTransaksi["header"]->ID_HEADER, "text" => "Konversi Barang");
		$breadcrumb[] = Array("text" => "Transaksi Konversi");
		$dtProduk = $this->produk->getProduk();
						
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb,  
				"idtransaksi" => $id,"dataproduk" => $dtProduk, 
				"konversi" => isset($dtTransaksi["konversi"]) ? json_encode($dtTransaksi["konversi"]) : "{}",
			];		
		$this->render("transaksikonversi.php", $data);
	}
	public function mutasibarang($id = "")
    {		
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Transaksi Mutasi Barang");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/js/dropzone.min.js";		
		$assets["stylesheets"][] = "/web/assets/css/dropzone.min.css";
		$this->loadModel("Satuan");
		$this->loadModel("Transaksi");		
		$dtSatuan = $this->satuan->getSatuan();
		$dtBarang = $this->transaksi->getKodeBarang(["id" => $id]);
		$dtTransaksi = $this->transaksi->getMutasiBarang($id);
		$dtFiles = $this->transaksi->getFiles($id);				
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi) ? $dtTransaksi : "{}" , "breads" => $breadcrumb,  
				"idtransaksi" => $id, "barang" => $dtBarang,
				"files" => $dtFiles, "datasatuan" => $dtSatuan
			];		
		$this->render("mutasibarang.php", $data);
	}
	public function perekamanbarang()
    {
		$filter = $this->get("filter");
		if ($filter && $filter == "1"){
			$postKantor = $this->post("kantor");
			$postCustomer = $this->post("customer");
			$postImportir = $this->post("importir");			
			$postKategori1 = $this->post("kategori1");
			$isikategori1 = $this->post("isikategori1");
			$postKategori2 = $this->post("kategori2");
			$dari2 = $this->post("dari2");
			$sampai2 = $this->post("sampai2");		
			$this->loadModel("Transaksi");		
			$data = $this->transaksi->browseBarang($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
					$sheet->setCellValue('A2', 'CUSTOMER');
					if ($postCustomer && trim($postCustomer) != ""){
						$sheet->setCellValue('C2', $this->transaksi->getCustomer($postCustomer)->nama_customer);
					}
					else {
						$sheet->setCellValue('C2', "Semua");
					}
					$sheet->setCellValue('A3', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C3', $this->transaksi->getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C3', "Semua");
					}
					$lastrow = 3;
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
					$sheet->setCellValue('A' .$lastrow, 'Importir');
					$sheet->setCellValue('B' .$lastrow, 'Customer');
					$sheet->setCellValue('C' .$lastrow, 'Tgl Tiba');
					$sheet->setCellValue('D' .$lastrow, 'No Aju');
					$sheet->setCellValue('E' .$lastrow, 'Nopen');
					$sheet->setCellValue('F' .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue('G' .$lastrow, 'No BL');
					$sheet->setCellValue('H' .$lastrow, 'No Inv');
					$sheet->setCellValue('I' .$lastrow, 'Jml Kmsn');
					$sheet->setCellValue('J' .$lastrow, 'Tgl SPPB');
					$sheet->setCellValue('K' .$lastrow, 'Tgl Keluar');
					$sheet->setCellValue('L' .$lastrow, 'Jalur');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->IMPORTIR);
						$sheet->setCellValue('B' .$lastrow, $dt->CUSTOMER);
						$sheet->setCellValue('C' .$lastrow, $dt->TGLTIBA);
						$sheet->setCellValue('D' .$lastrow, $dt->NOAJU);
						$sheet->setCellValue('E' .$lastrow, $dt->NOPEN);
						$sheet->setCellValue('F' .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue('G' .$lastrow, $dt->NO_BL);
						$sheet->setCellValue('H' .$lastrow, $dt->NO_INV);
						$sheet->setCellValue('I' .$lastrow, $dt->JUMLAH_KEMASAN);
						$sheet->setCellValue('J' .$lastrow, $dt->TGLSPPB);
						$sheet->setCellValue('K' .$lastrow, $dt->TGLKELUAR);
						$sheet->setCellValue('L' .$lastrow, $dt->JALURDOK);
					}
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="browsebarang.xlsx"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$writer = new Xlsx($spreadsheet);
					$writer->save('php://output');
				}
				else {
					header('Content-Type: application/json');
					print json_encode($data);
				}
			}
			else {
				header('Content-Type: application/json');
				print json_encode([]);
			}
		}
		else {
			$assets = $this->prepareAssets();
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Perekaman Barang");
			$this->loadModel("Transaksi");
			$kantor = $this->transaksi->getKantor();
			$customer = $this->transaksi->getCustomer();
			$importir = $this->transaksi->getImportir();
					
			$this->render("perekamanbarang.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
										"scripts" => $assets["scripts"],
										"datakantor" => $kantor, "datacustomer" => $customer,
										"dataimportir" => $importir,								
										"datakategori1" => Array("No BL","No Kontainer","Nopen","No Inv","Hasil Periksa"),
										"datakategori2" => Array("Tanggal Nopen","Tanggal SPPB","Tanggal Keluar","Tanggal Tiba")															
										]);
		}
	}
	public function konversibarang()
    {
		$filter = $this->get("filter");
		if ($filter && $filter == "1"){
			$postKantor = $this->post("kantor");
			$postCustomer = $this->post("customer");
			$postImportir = $this->post("importir");			
			$postKategori1 = $this->post("kategori1");
			$isikategori1 = $this->post("isikategori1");
			$postKategori2 = $this->post("kategori2");
			$dari2 = $this->post("dari2");
			$sampai2 = $this->post("sampai2");		
			$this->loadModel("Transaksi");		
			$data = $this->transaksi->browseKonversi($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
					$sheet->setCellValue('A2', 'CUSTOMER');
					if ($postCustomer && trim($postCustomer) != ""){
						$sheet->setCellValue('C2', $this->transaksi->getCustomer($postCustomer)->nama_customer);
					}
					else {
						$sheet->setCellValue('C2', "Semua");
					}
					$sheet->setCellValue('A3', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C3', $this->transaksi->getImportir($postImportir)->NAMA);
					}
					else {
						$sheet->setCellValue('C3', "Semua");
					}
					$lastrow = 3;
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
					$sheet->setCellValue('A' .$lastrow, 'Importir');
					$sheet->setCellValue('B' .$lastrow, 'Customer');
					$sheet->setCellValue('C' .$lastrow, 'Tgl Tiba');
					$sheet->setCellValue('D' .$lastrow, 'Nopen');
					$sheet->setCellValue('E' .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue('F' .$lastrow, 'No BL');
					$sheet->setCellValue('G' .$lastrow, 'No Inv');
					$sheet->setCellValue('H' .$lastrow, 'Jml Kmsn');
					$sheet->setCellValue('I' .$lastrow, 'Tgl SPPB');
					$sheet->setCellValue('J' .$lastrow, 'Tgl Keluar');
					$sheet->setCellValue('K' .$lastrow, 'Tgl Konversi');
					$sheet->setCellValue('L' .$lastrow, 'Jalur');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->IMPORTIR);
						$sheet->setCellValue('B' .$lastrow, $dt->CUSTOMER);
						$sheet->setCellValue('C' .$lastrow, $dt->TGLTIBA);
						$sheet->setCellValue('D' .$lastrow, $dt->NOPEN);
						$sheet->setCellValue('E' .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue('F' .$lastrow, $dt->NO_BL);
						$sheet->setCellValue('G' .$lastrow, $dt->NO_INV);
						$sheet->setCellValue('H' .$lastrow, $dt->JUMLAH_KEMASAN);
						$sheet->setCellValue('I' .$lastrow, $dt->TGLSPPB);
						$sheet->setCellValue('J' .$lastrow, $dt->TGLKELUAR);
						$sheet->setCellValue('K' .$lastrow, $dt->TGLKONVERSI);
						$sheet->setCellValue('L' .$lastrow, $dt->JALURDOK);
					}
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="browsebarang.xlsx"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$writer = new Xlsx($spreadsheet);
					$writer->save('php://output');
				}
				else {
					header('Content-Type: application/json');
					print json_encode($data);
				}
			}
			else {
				header('Content-Type: application/json');
				print json_encode([]);
			}
		}
		else {
			$assets = $this->prepareAssets();
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Konversi Barang");
			$this->loadModel("Transaksi");
			$kantor = $this->transaksi->getKantor();
			$customer = $this->transaksi->getCustomer();
			$importir = $this->transaksi->getImportir();
					
			$this->render("konversibarang.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
										"scripts" => $assets["scripts"],
										"datakantor" => $kantor, "datacustomer" => $customer,
										"dataimportir" => $importir,								
										"datakategori1" => Array("No BL","No Kontainer","Nopen","Hasil Periksa"),
										"datakategori2" => Array("Tanggal Nopen","Tanggal Konversi","Tanggal Keluar")															
										]);
		}
	}
	private function getImportir()
	{
		$this->loadModel("Users");
		$user = $this->getSessionValue("logged_in");
		$usr = $this->users->getData($user);

		if ($usr->company){
			$company = $usr->company->id;
			$importir = $this->transaksi->getImportir($company, true);
		}
		else {
			$importir = $this->transaksi->getImportir();
		}
		return $importir;
	}
	public function browseStokProduk()
    {
		$filter = $this->get("filter");
		if ($filter && $filter == "1"){
			//$postKantor = $this->post("kantor");
			//$postCustomer = $this->post("customer");
			$postImportir = $this->post("importir");			
			$postKategori1 = $this->post("kategori1");
			$isikategori1 = $this->post("isikategori1");
			$this->loadModel("Transaksi");		
			$data = $this->transaksi->stokProduk($postImportir, $postKategori1,
									$isikategori1);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C1', $this->transaksi->getImportir($postImportir)->NAMA);
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
					
					$sheet->setCellValue('A' .$lastrow, 'Kode Produk');
					$sheet->setCellValue('B' .$lastrow, 'Saldo Awal');
					$sheet->setCellValue('D' .$lastrow, 'Masuk');
					$sheet->setCellValue('F' .$lastrow, 'Keluar');
					$sheet->setCellValue('H' .$lastrow, 'Stok Akhir');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->kode);
						$sheet->setCellValue('B' .$lastrow, $dt->kemasansawal);
						$sheet->setCellValue('C' .$lastrow, $dt->satuankemasan);
						$sheet->setCellValue('B' .strval($lastrow + 1), $dt->satuansawal);
						$sheet->setCellValue('C' .strval($lastrow + 1), $dt->satuan);
						$sheet->setCellValue('D' .$lastrow, $dt->kemasanmasuk);
						$sheet->setCellValue('E' .$lastrow, $dt->satuankemasan);
						$sheet->setCellValue('D' .strval($lastrow+1), $dt->satuanmasuk);
						$sheet->setCellValue('E' .strval($lastrow+1), $dt->satuan);
						$sheet->setCellValue('F' .$lastrow, $dt->kemasankeluar);
						$sheet->setCellValue('G' .$lastrow, $dt->satuankemasan);
						$sheet->setCellValue('F' .strval($lastrow+1), $dt->satuankeluar);
						$sheet->setCellValue('G' .strval($lastrow+1), $dt->satuan);
						$sheet->setCellValue('H' .$lastrow, $dt->kemasansakhir);
						$sheet->setCellValue('I' .$lastrow, $dt->satuankemasan);
						$sheet->setCellValue('H' .strval($lastrow+1), $dt->satuansakhir);
						$sheet->setCellValue('I' .strval($lastrow+1), $dt->satuan);

						$detail = $this->transaksi->stokProduk($postImportir, $postKategori1,$isikategori1, $dt->satuankemasan, $dt->satuan, $dt->id);						
						if (count($detail) > 0){
							$lastrow += 3;
							$sheet->setCellValue('A' .$lastrow, "KODE BARANG");
							$sheet->setCellValue('B' .$lastrow, "IMPORTIR");							
							$sheet->setCellValue('C' .$lastrow, "TGL TERIMA");
							$sheet->setCellValue('D' .$lastrow, "SALDO AWAL");
							$sheet->setCellValue('F' .$lastrow, "MASUK");						
							$sheet->setCellValue('H' .$lastrow, "KELUAR");
							$sheet->setCellValue('J' .$lastrow, "SALDO AKHIR");									
							foreach ($detail as $det){
								$lastrow += 1;
								$sheet->setCellValue('A' .$lastrow, $det->KODEBARANG);
								$sheet->setCellValue('B' .$lastrow, $det->NAMA);
								$sheet->setCellValue('C' .$lastrow, $det->TGL_TERIMA);
								$sheet->setCellValue('D' .$lastrow, $dt->kemasansawal);
								$sheet->setCellValue('E' .$lastrow, $dt->satuankemasan);
								$sheet->setCellValue('D' .strval($lastrow+1), $dt->satuansawal);
								$sheet->setCellValue('E' .strval($lastrow+1), $dt->satuan);
								$sheet->setCellValue('F' .$lastrow, $dt->kemasanmasuk);
								$sheet->setCellValue('G' .$lastrow, $dt->satuankemasan);
								$sheet->setCellValue('F' .strval($lastrow+1), $dt->satuanmasuk);
								$sheet->setCellValue('G' .strval($lastrow+1), $dt->satuan);
								$sheet->setCellValue('H' .$lastrow, $dt->kemasankeluar);
								$sheet->setCellValue('I' .$lastrow, $dt->satuankemasan);
								$sheet->setCellValue('H' .strval($lastrow+1), $dt->satuankeluar);
								$sheet->setCellValue('I' .strval($lastrow+1), $dt->satuan);
								$sheet->setCellValue('J' .$lastrow, $dt->kemasansakhir);
								$sheet->setCellValue('K' .$lastrow, $dt->satuankemasan);
								$sheet->setCellValue('J' .strval($lastrow+1), $dt->satuansakhir);
								$sheet->setCellValue('K' .strval($lastrow+1), $dt->satuan);
							}
							$lastrow += 2;
						}
					}
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="browsebarang.xlsx"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$writer = new Xlsx($spreadsheet);
					$writer->save('php://output');
				}
				else {
					header('Content-Type: application/json');
					print json_encode($data);
				}
			}
			else {
				header('Content-Type: application/json');
				print json_encode([]);
			}
		}
		else {
			$assets = $this->prepareAssets();
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Browse Stok per Produk");
			$this->loadModel("Transaksi");
			$this->loadModel("Produk");
			$importir = $this->transaksi->getImportir();
			$produk = $this->produk->getProduk();
					
			$this->render("stokproduk.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
										"scripts" => $assets["scripts"],
										"dataimportir" => $importir, "dataproduk" => $produk,								
										"datakategori1" => Array("Kode Produk"),
										]);
		}
	}
	public function browseStokBarang()
    {
		$filter = $this->get("filter");
		if ($filter && $filter == "1"){
			//$postKantor = $this->post("kantor");
			$postCustomer = $this->post("customer");
			$postImportir = $this->post("importir");			
			$postKategori1 = $this->post("kategori1");
			$isikategori1 = $this->post("isikategori1");
			$postKategori2 = $this->post("kategori2");
			$dari2 = $this->post("dari2");
			$sampai2 = $this->post("sampai2");

			$this->loadModel("Transaksi");		
			$data = $this->transaksi->stokBarang($postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C1', $this->transaksi->getImportir($postImportir)->NAMA);
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
					$sheet->setCellValue('A' .$lastrow, 'Kode Produk');
					$sheet->setCellValue('B' .$lastrow, 'Saldo Awal');
					$sheet->setCellValue('C' .$lastrow, 'Masuk');
					$sheet->setCellValue('D' .$lastrow, 'Keluar');
					$sheet->setCellValue('E' .$lastrow, 'Stok Akhir');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->kode);
						$sheet->setCellValue('B' .$lastrow, $dt->sawal);
						$sheet->setCellValue('C' .$lastrow, $dt->masuk);
						$sheet->setCellValue('D' .$lastrow, $dt->keluar);
						$sheet->setCellValue('E' .$lastrow, $dt->sakhir);					}
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="browsebarang.xlsx"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$writer = new Xlsx($spreadsheet);
					$writer->save('php://output');
				}
				else {
					header('Content-Type: application/json');
					print json_encode($data);
				}
			}
			else {
				header('Content-Type: application/json');
				print json_encode([]);
			}
		}
		else {
			$assets = $this->prepareAssets();
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
			$breadcrumb[] = Array("text" => "Browse Stok per Kode Barang");
			$this->loadModel("Transaksi");
			$importir = $this->transaksi->getImportir();
			$customer = $this->transaksi->getCustomer();
					
			$this->render("stokbarang.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
										"scripts" => $assets["scripts"],"datacustomer" => $customer,
										"dataimportir" => $importir, 								
										"datakategori1" => Array("Saldo Akhir", "Kode Barang"),
										"datakategori2" => Array("Tanggal Terima")
										]);
		}
	}
	public function detailstokproduk()
	{
		$id = $this->post("id");
		$kemasan = $this->post("kemasan");
		$satuan = $this->post("satuan");
		parse_str($this->post("form"), $form);
		$this->loadModel("Transaksi");
		$data = $this->transaksi->stokProduk($form['importir'], $form['kategori1'],
									$form['isikategori1'], $kemasan, $satuan, $id);
		if (!$data){
			$response["data"] = [];
		}
		else {
			$response["data"] = $data;
		}
		header('Content-Type: application/json');
		print json_encode($response);
	}
	public function detailstokbarang()
	{
		$id = $this->post("id");
		$this->loadModel("Transaksi");
		$data = $this->transaksi->getDetailStokBarang($id);
		if (!$data){
			$response["data"] = [];
		}
		else {
			$response["data"] = $data;
		}
		header('Content-Type: application/json');
		print json_encode($response);
	}
	public function perekamanpengeluaran($id = "")
	{
		$assets = $this->prepareAssets();
        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman Pengeluaran");
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$this->render("pengeluaran.php",["breads" => $breadcrumb, 
									"stylesheets" => $assets["stylesheets"],"do_id" => trim($id) != "" ? sprintf('%06d',$id) : "", 
									"scripts" => $assets["scripts"]]);		
	}
	public function pengeluaran()
	{		
		$search = $this->post("search");
		$draw = $this->post("draw");
		$start = $this->post("start");
		$length = $this->post("length");
		$order = $this->post("order") ? $this->post('order') : Array();
		$this->loadModel("Transaksi");
		$fetch = $this->transaksi->getDataPengeluaran($search, $start, $length, $order);
		$num_rows = $fetch['count'];
		$num_filtered = $fetch['data']->num_rows();
		$data = Array("draw" => $draw,
								"recordsFiltered" => $num_rows,
								"recordsTotal" => $num_filtered,
					  "data" => $fetch['data']->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function deliveryorder($id = "")
    {		
        $breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman Delivery Order");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$this->loadModel("Transaksi");
		if ($id != ""){
			$dtTransaksi = $this->transaksi->getTransaksiDOrder($id);
			$notransaksi = " No. " .sprintf('%06d', $dtTransaksi["header"]->ID);
		}
		else {
			$notransaksi = " (Baru)";
		}
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : [], "breads" => $breadcrumb,  
				"idtransaksi" => $id,"notransaksi" => $notransaksi, 
				"detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
			];		
			
		$this->render("transaksidorder.php", $data);
	}
	public function searchkodebarang()
	{
		$kode = $this->get("kode");
		$this->loadModel("Transaksi");
		$data = $this->transaksi->getKodeBarang($kode);		
		if (!$data){
			$response["error"] = "Kode Barang tidak ada";
		}
		else {
			$response = $data;
		}
		header('Content-Type: application/json');
		print json_encode($response);
	}
	public function searchdo()
	{
		$nodo = $this->post("no_do");
		$nodo = ltrim($nodo, '0');
		$this->loadModel("Transaksi");
		$data = $this->transaksi->getTransaksiDOrder($nodo, false);
		$pengeluaran = $this->transaksi->getDataPengeluaran($nodo);
		header('Content-Type: application/json');
		if ($data){					
			print json_encode(["header" => $data, "detail" => $pengeluaran]);
		}
		else {
			print json_encode(["error" => "Data Tidak Ditemukan"]);
		}
	}
}
