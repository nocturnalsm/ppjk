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
		$this->loadModel("Transaksi");		
		$this->loadModel("Satuan");
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
		$dtSatuan = $this->satuan->getSatuan();
	
		$dtTransaksi = Array();
		$pi = [];
		if ($id != ""){
			$dtTransaksi = $this->transaksi->getTransaksi($id);
			$notransaksi = " No. " .sprintf('%08d', $dtTransaksi["header"]->ID);
			$pi = $this->transaksi->getPI($dtTransaksi["header"]->CONSIGNEE);
			$detailQuota = json_encode($this->transaksi->getRealisasiQuota($id));
		}
		else {
			$notransaksi = " (Baru)";
		}
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb, "importir" => $dtImportir, 
				"kontainer" => isset($dtTransaksi["kontainer"]) ? json_encode($dtTransaksi["kontainer"]) : "{}", 				
				"jeniskemasan" => $dtJenisKemasan, "customer" => $dtCustomer,
				"jenisdokumen" => $dtJenisDokumen, "datasatuan" => $dtSatuan,  
				"kodekantor" => $dtKantor, "pelmuat" => $dtPelmuat, "shipper" => $dtShipper,
				"jumlahkontainer" => $dtJumlahKontainer, "quota" => isset($detailQuota) ? $detailQuota : "{}", "pi" => $pi,
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
		$this->loadModel("Transaksi");		
		$this->loadModel("Satuan");
		$dtTransaksi = $this->transaksi->getTransaksiVo($id);
		$dtImportir = $this->transaksi->getImportir();
		$dtSatuan = $this->satuan->getSatuan();
		$detailQuota = json_encode($this->transaksi->getRealisasiQuota($id));
		$notransaksi = " No. " .sprintf('%08d', $dtTransaksi->ID);
		if ($dtTransaksi->NO_PI == ''){
		    $dataPI = $this->transaksi->getPI($dtTransaksi->CONSIGNEE);
		    if ($dataPI){
		        $dtTransaksi->ID_PI = $dataPI->ID;
		        $dtTransaksi->NO_PI = $dataPI->NO_PI;
		    }
		}
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => $dtTransaksi, "breads" => $breadcrumb,  
				"quota" => $detailQuota, "datasatuan" => $dtSatuan,
				"idtransaksi" => $id, "importir" => $dtImportir, 
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
			$postKategori3 = $this->post("kategori3");
			$dari3 = $this->post("dari3");
			$sampai3 = $this->post("sampai3");

			$this->setSessionValue("browsevo", [
				"kantor" => $postKantor,
				"kategori" => $postKategori,
				"customer" => $postCustomer,
				"importir" => $postImportir,
				"kategori1" => $postKategori1,
				"isikategori1" => $isikategori1,
				"kategori2" => $postKategori2,
				"dari2" => $dari2, "sampai2" => $sampai2,
				"kategori3" => $postKategori3,
				"dari3" => $dari3, "sampai3" => $sampai3

			]);		
			$this->loadModel("Transaksi");		
			$data = $this->transaksi->browseVo($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2,
									$postKategori3, $dari3, $sampai3);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
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
					if ($postKategori3 && trim($postKategori3) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori3);
						$sheet->setCellValue('C' .$lastrow, $dari3 == "" ? "-" : Date("d M Y", strtotime($dari3)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai3 == "" ? "-" : Date("d M Y", strtotime($sampai3)));					
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
										"datakategori2" => Array("Tanggal Periksa", "Tanggal LS", "Tanggal VO","Tanggal Nopen","Tanggal Tiba"),
										"datakategori3" => Array("Tanggal Periksa", "Tanggal LS", "Tanggal VO","Tanggal Nopen","Tanggal Tiba")															
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
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
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
			$postKategori3 = $this->post("kategori3");
			$dari3 = $this->post("dari3");
			$sampai3 = $this->post("sampai3");		

			$this->loadModel("Transaksi");		
			$data = $this->transaksi->browseDo($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2, $postKategori3, $dari3, $sampai3);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
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
					if ($postKategori3 && trim($postKategori3) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori3);
						$sheet->setCellValue('C' .$lastrow, $dari3 == "" ? "-" : Date("d M Y", strtotime($dari3)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai3 == "" ? "-" : Date("d M Y", strtotime($sampai3)));					
					}

					$lastrow += 2;
					$sheet->setCellValue('A' .$lastrow, 'Importir');
					$sheet->setCellValue('B' .$lastrow, 'Customer');
					$sheet->setCellValue('C' .$lastrow, 'No Inv');
					$sheet->setCellValue('D' .$lastrow, 'No PO');
					$sheet->setCellValue('E' .$lastrow, 'No SC');
					$sheet->setCellValue('F' .$lastrow, 'No BL');
					$sheet->setCellValue('G' .$lastrow, 'Tgl BL');
				    $sheet->setCellValue('H' .$lastrow, 'No Aju');
				    $sheet->setCellValue('I' .$lastrow, 'Nopen');
				    $sheet->setCellValue('J' .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue('K' .$lastrow, 'Tgl Tiba');
					$sheet->setCellValue('L' .$lastrow, 'No. Form');
					$sheet->setCellValue('M' .$lastrow, 'Tgl LS');
					$sheet->setCellValue('N' .$lastrow, 'Tgl Dok Trm');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->IMPORTIR);
						$sheet->setCellValue('B' .$lastrow, $dt->CUSTOMER);
						$sheet->setCellValue('C' .$lastrow, $dt->NO_INV);
						$sheet->setCellValue('D' .$lastrow, $dt->NO_PO);
						$sheet->setCellValue('E' .$lastrow, $dt->NO_SC);
						$sheet->setCellValue('F' .$lastrow, $dt->NO_BL);
						$sheet->setCellValue('G' .$lastrow, $dt->TGLBL);
						$sheet->setCellValue('H' .$lastrow, $dt->NOAJU);
						$sheet->setCellValue('I' .$lastrow, $dt->NOPEN);
						$sheet->setCellValue('J' .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue('K' .$lastrow, $dt->TGLTIBA);
						$sheet->setCellValue('L' .$lastrow, $dt->NO_FORM);
						$sheet->setCellValue('M' .$lastrow, $dt->TGLLS);
						$sheet->setCellValue('N' .$lastrow, $dt->TGLDOKTRM);
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
										"datakategori1" => Array("No Inv","No BL","No VO", "Nopen"),
										"datakategori2" => Array("Tanggal BL","Tanggal Tiba", "Tanggal Nopen", "Tgl Dok Terima")															
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
				if ($postKantor && trim($postKantor) != ""){
    				$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
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
				$sheet->setCellValue('A' .$lastrow, 'Kd Kantor');
				$sheet->setCellValue('B' .$lastrow, 'No. Inv');
				$sheet->setCellValue('C' .$lastrow, 'No. BL');
				$sheet->setCellValue('D' .$lastrow, 'Jml Kmsn');
				$sheet->setCellValue('E' .$lastrow, 'Customer');
				$sheet->setCellValue('F' .$lastrow, 'Importir');
				$sheet->setCellValue('G' .$lastrow, 'Tgl Tiba');
				$sheet->setCellValue('H' .$lastrow, 'Tgl SPPB');
				$sheet->setCellValue('I' .$lastrow, 'Tgl Keluar');
				$sheet->setCellValue('J' .$lastrow, 'Tgl Terima');
				$sheet->setCellValue('K' .$lastrow, 'No.Aju');
				$sheet->setCellValue('L' .$lastrow, 'Nopen');
				$sheet->setCellValue('M' .$lastrow, 'Tgl Nopen');
				/*
				$sheet->setCellValue('K' .$lastrow, 'No. PO');
				$sheet->setCellValue('L' .$lastrow, 'No. SC');
				*/
				$sheet->setCellValue('N' .$lastrow, 'Jml Kontainer');

				foreach ($data as $dt){
					$lastrow += 1;
					$sheet->setCellValue('A' .$lastrow, $dt->KODEKANTOR);
					$sheet->setCellValue('B' .$lastrow, $dt->NO_INV);
					$sheet->setCellValue('C' .$lastrow, $dt->NO_BL);
					$sheet->setCellValue('D' .$lastrow, $dt->JUMLAH_KEMASAN);
					$sheet->setCellValue('E' .$lastrow, $dt->NAMA);
					$sheet->setCellValue('F' .$lastrow, $dt->IMPORTIR);
					$sheet->setCellValue('G' .$lastrow, $dt->TGLTIBA);
					$sheet->setCellValue('H' .$lastrow, $dt->TGLSPPB);
					$sheet->setCellValue('I' .$lastrow, $dt->TGLKELUAR);
					$sheet->setCellValue('J' .$lastrow, $dt->TGLTERIMA);
					$sheet->setCellValue('K' .$lastrow, $dt->NOAJU);
					$sheet->setCellValue('L' .$lastrow, $dt->NOPEN);
					$sheet->setCellValue('M' .$lastrow, $dt->TGLNOPEN);
					/*
					$sheet->setCellValue('K' .$lastrow, $dt->NO_PO);
					$sheet->setCellValue('L' .$lastrow, $dt->NO_SC);
					*/
					$sheet->setCellValue('N' .$lastrow, $dt->JUMLAH_KONTAINER);
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
	/*
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
	*/
	public function browsesptnp()
    {
		$filter = $this->get("filter");
		if ($filter && $filter == "1"){
			$postKantor = $this->post("kantor");
			$postImportir = $this->post("importir");			
			$postKategori1 = $this->post("kategori1");
			$isikategori1 = $this->post("isikategori1");
			$postKategori2 = $this->post("kategori2");
			$dari2 = $this->post("dari2");
			$sampai2 = $this->post("sampai2");		
			$this->loadModel("Transaksi");		
			$data = $this->transaksi->browseSPTNP($postKantor, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
					}
					else {
					    $sheet->setCellValue('C1', "Semua");
					}
					$sheet->setCellValue('A2', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C2', $this->transaksi->getImportir($postImportir)->NAMA);
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
					$sheet->setCellValue('A' .$lastrow, 'Importir');
					$sheet->setCellValue('B' .$lastrow, 'Customer');
				    $sheet->setCellValue('C' .$lastrow, 'No Aju');
				    $sheet->setCellValue('D' .$lastrow, 'Nopen');
				    $sheet->setCellValue('E' .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue('F' .$lastrow, 'No SPTNP');
					$sheet->setCellValue('G' .$lastrow, 'Tgl SPTNP');
					$sheet->setCellValue('H' .$lastrow, 'Tgl Jth Tempo');
					$sheet->setCellValue('I' .$lastrow, 'Tgl Lunas');
					$sheet->setCellValue('J' .$lastrow, 'Tgl BRT');
					$sheet->setCellValue('K' .$lastrow, 'Hsl BRT');
					$sheet->setCellValue('L' .$lastrow, 'Denda TB');
					$sheet->setCellValue('M' .$lastrow, 'Total TB');
					$sheet->setCellValue('N' .$lastrow, 'Jenis SPTNP');

					foreach ($data as $dt){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $dt->IMPORTIR);
						$sheet->setCellValue('B' .$lastrow, $dt->CUSTOMER);
						$sheet->setCellValue('C' .$lastrow, $dt->NOAJU);
						$sheet->setCellValue('D' .$lastrow, $dt->NOPEN);
						$sheet->setCellValue('E' .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue('F' .$lastrow, $dt->NO_SPTNP);
						$sheet->setCellValue('G' .$lastrow, $dt->TGLSPTNP);
						$sheet->setCellValue('H' .$lastrow, $dt->TGLJTHTEMPOSPTNP);
						$sheet->setCellValue('I' .$lastrow, $dt->TGLLUNAS);
						$sheet->setCellValue('J' .$lastrow, $dt->TGLBRT);
						$sheet->setCellValue('K' .$lastrow, $dt->HSL_BRT);
						$sheet->setCellValue('L' .$lastrow, $dt->DENDA_TB);
						$sheet->setCellValue('M' .$lastrow, $dt->TOTAL_TB);
						$sheet->setCellValue('N' .$lastrow, $dt->JENIS_SPTNP);
					}
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="browsesptnp.xlsx"');
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
			$breadcrumb[] = Array("text" => "Perekaman SPTNP");
			$this->loadModel("Transaksi");
			$kantor = $this->transaksi->getKantor();
			$importir = $this->getImportir();
			$this->render("perekamansptnp.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
										"scripts" => $assets["scripts"],
										"datakantor" => $kantor, "dataimportir" => $importir,								
										"datakategori1" => Array("Nopen","No SPTNP","Jenis SPTNP"),
										"datakategori2" => Array("Tanggal Jatuh Tempo","Tanggal SPTNP", "Tanggal BRT")															
										]);
		}        
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
					$detail = $this->post("detail");
					parse_str($postheader, $header);
					if ($header["idtransaksi"] == ""){
						$action = "insert";
					}
					else {
						$action = "update";
					}
					$id = $this->transaksi->saveTransaksi($action, $header, $kontainer, $detail);
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
					$pengeluaran = $this->post("pengeluaran");
					parse_str($postheader, $header);
					if ($header["idtransaksi"] == ""){
						$action = "insert";
					}
					else {
						$action = "update";
					}
					$id = $this->transaksi->saveTransaksiDOrder($action, $header, $detail, $pengeluaran);
				}		
				else {
					$id = $this->post("delete");
					if ($id && $id != ""){
						$this->transaksi->deleteTransaksiDOrder($id);
					}
				}						
			}
			else if ($type == "userquota"){
				if ($postheader){
					$detail = $this->post("detail");
					$files = $this->post('files');					
					parse_str($postheader, $header);
					if ($header["idtransaksi"] == ""){
						$action = "insert";
					}
					else {
						$action = "update";
					}
					$id = $this->transaksi->saveTransaksiQuota($action, $header, $detail, $files);
				}		
				else {
					$id = $this->post("delete");
					if ($id && $id != ""){
						$this->transaksi->deleteTransaksiQuota($id);
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
					$postDetail = $this->post("detail");
					$id = $this->transaksi->saveTransaksiVo($header, $postDetail);
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
		$type = $this->post("filetype");
		if (!$type){
		    $type = 0;
		}
		if ($file->isValid()){
			$realname = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$this->loadModel("Transaksi");							
			$this->db->startTrans();			
			try {
				$id = $this->transaksi->saveFile($realname, $extension, $type);
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
                    $sheet->setCellValue('A' .$lastrow, 'No');
                    $sheet->setCellValue('B' .$lastrow, 'Kantor');
					$sheet->setCellValue('C' .$lastrow, 'Importir');
                    $sheet->setCellValue('D' .$lastrow, 'Customer');
                    $sheet->setCellValue('E' .$lastrow, 'Shipper');
                    $sheet->setCellValue('F' .$lastrow, 'Jns Dok');
                    $sheet->setCellValue('G' .$lastrow, 'Nopen');
                    $sheet->setCellValue('H' .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue('I' .$lastrow, 'No Inv');
					$sheet->setCellValue('J' .$lastrow, 'Tgl Inv');
					$sheet->setCellValue('K' .$lastrow, 'Jth Tempo');
					$sheet->setCellValue('L' .$lastrow, 'Curr');
					$sheet->setCellValue('M' .$lastrow, 'CIF');
					$sheet->setCellValue('N' .$lastrow, 'Payment');
					$sheet->setCellValue('O' .$lastrow, 'Saldo');
					$sheet->setCellValue('P' .$lastrow, "NO PPU");
					$sheet->setCellValue('Q' .$lastrow, "MATA UANG");							
					$sheet->setCellValue('R' .$lastrow, "KURS");
					$sheet->setCellValue('S' .$lastrow, "NOMINAL");
					$sheet->setCellValue('T' .$lastrow, "RUPIAH");						
					$sheet->setCellValue('U' .$lastrow, "TGL BAYAR");									

                    $lastrow += 1;
                    $no = 0;
					foreach ($data as $dt){
						$detail = $this->transaksi->getDetailBayar($dt->ID);
						$no += 1;
						if (count($detail) > 0){
                            $lastrow += 1;
							foreach ($detail as $det){
							    $sheet->setCellValue('A' .$lastrow, $no);
                                $sheet->setCellValue('B' .$lastrow, $dt->KANTOR);
        						$sheet->setCellValue('C' .$lastrow, $dt->IMPORTIR);
                                $sheet->setCellValue('D' .$lastrow, $dt->CUSTOMER);
                                $sheet->setCellValue('E' .$lastrow, $dt->SHIPPER);
                                $sheet->setCellValue('F' .$lastrow, $dt->JENISDOKUMEN);
                                $sheet->setCellValue('G' .$lastrow, $dt->NOPEN);
                                $sheet->setCellValue('H' .$lastrow, $dt->TGLNOPEN);
                                $sheet->setCellValue('I' .$lastrow, $dt->NO_INV);
                                $sheet->setCellValue('J' .$lastrow, $dt->TGLINV);
        						$sheet->setCellValue('K' .$lastrow, $dt->TGLJTHTEMPO);
        						$sheet->setCellValue('L' .$lastrow, $dt->MATAUANG);
        						$sheet->setCellValue('M' .$lastrow, $dt->CIF);
        						$sheet->setCellValue('N' .$lastrow, $dt->TOT_PAYMENT);
        						$sheet->setCellValue('O' .$lastrow, $dt->SALDO);
								$sheet->setCellValue('P' .$lastrow, $det->NO_PPU);
								$sheet->setCellValue('Q' .$lastrow, $det->MATAUANG);
								$sheet->setCellValue('R' .$lastrow, $det->KURS);
								$sheet->setCellValue('S' .$lastrow, $det->NOMINAL);
								$sheet->setCellValue('T' .$lastrow, $det->RUPIAH);						
								$sheet->setCellValue('U' .$lastrow, $det->TGLBAYAR);
								$lastrow += 1;
							}
							$lastrow += 1;
						}					
						else {
							    $sheet->setCellValue('A' .$lastrow, $no);
                                $sheet->setCellValue('B' .$lastrow, $dt->KANTOR);
        						$sheet->setCellValue('C' .$lastrow, $dt->IMPORTIR);
                                $sheet->setCellValue('D' .$lastrow, $dt->CUSTOMER);
                                $sheet->setCellValue('E' .$lastrow, $dt->SHIPPER);
                                $sheet->setCellValue('F' .$lastrow, $dt->JENISDOKUMEN);
                                $sheet->setCellValue('G' .$lastrow, $dt->NOPEN);
                                $sheet->setCellValue('H' .$lastrow, $dt->TGLNOPEN);
                                $sheet->setCellValue('I' .$lastrow, $dt->NO_INV);
                                $sheet->setCellValue('J' .$lastrow, $dt->TGLINV);
        						$sheet->setCellValue('K' .$lastrow, $dt->TGLJTHTEMPO);
        						$sheet->setCellValue('L' .$lastrow, $dt->MATAUANG);
        						$sheet->setCellValue('M' .$lastrow, $dt->CIF);
        						$sheet->setCellValue('N' .$lastrow, $dt->TOT_PAYMENT);
        						$sheet->setCellValue('O' .$lastrow, $dt->SALDO);					        
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
		$this->transaksi->execute("UPDATE tbl_penarikan_header SET TOTAL = BM + BMT + PPN + PPH WHERE ID = " .$id);
		$dtTransaksi = Array();
		if ($id != ""){
			$dtTransaksi = $this->transaksi->getTransaksiBarang($id);
			$dtFiles = $this->transaksi->getFiles($dtTransaksi["header"]->ID, 1);
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
			$dtFiles = $this->transaksi->getFiles($dtTransaksi["header"]->ID, 1);
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
		$datarate = $this->transaksi->getRateDPP();
		$breadcrumb[] = Array("link" => "/transaksi/userbarangkonversi/" .$dtTransaksi["header"]->ID_HEADER, "text" => "Konversi Barang");
		$breadcrumb[] = Array("text" => "Transaksi Konversi");
		$dtProduk = $this->produk->getProduk();
						
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb,  
				"idtransaksi" => $id,"dataproduk" => $dtProduk,  "datarate" => $datarate,
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
		$dtFiles = $this->transaksi->getFiles($id, 1);				
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
			$export = $this->get("export");
			$includeDetail = $export == 1;
			$data = $this->transaksi->browseBarang($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2, $includeDetail);
			if ($data){
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
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
					$sheet->setCellValue('A' .$lastrow, 'No');
					$sheet->setCellValue('B' .$lastrow, 'Importir');
					$sheet->setCellValue('C' .$lastrow, 'Customer');
					$sheet->setCellValue('D' .$lastrow, 'Tgl Tiba');
					$sheet->setCellValue('E' .$lastrow, 'No Aju');
					$sheet->setCellValue('F' .$lastrow, 'Tgl Aju');
					$sheet->setCellValue('G' .$lastrow, 'Nopen');
					$sheet->setCellValue('H' .$lastrow, 'Tgl Nopen');
					$sheet->setCellValue('I' .$lastrow, 'No BL');
					$sheet->setCellValue('J' .$lastrow, 'Tgl BL');
					$sheet->setCellValue('K' .$lastrow, 'No Inv');
					$sheet->setCellValue('L' .$lastrow, 'Tgl Inv');
					$sheet->setCellValue('M' .$lastrow, 'Jml Kmsn');
					$sheet->setCellValue('N' .$lastrow, 'Tgl SPPB');
					$sheet->setCellValue('O' .$lastrow, 'Tgl Keluar');
					$sheet->setCellValue('P' .$lastrow, 'Tgl Terima');
					$sheet->setCellValue('Q' .$lastrow, 'Jalur');
					$sheet->setCellValue('R' .$lastrow, 'Faktur');
					$sheet->setCellValue('S' .$lastrow, 'No. Form');
					$sheet->setCellValue('T' .$lastrow, 'Tgl Form');
					$sheet->setCellValue('U' .$lastrow, 'No LS');
					$sheet->setCellValue('V' .$lastrow, 'Tgl LS');
                    $sheet->setCellValue('W' .$lastrow, 'Jns Dokumen');
                    $sheet->setCellValue('X' .$lastrow, 'Valuta');
                    $sheet->setCellValue('Y' .$lastrow, 'NDPBM');
                    $sheet->setCellValue('Z' .$lastrow, 'Nilai');
                    $sheet->setCellValue('AA' .$lastrow, 'BM');
                    $sheet->setCellValue('AB' .$lastrow, 'BMT');
                    $sheet->setCellValue('AC' .$lastrow, 'PPn');
                    $sheet->setCellValue('AD' .$lastrow, 'PPh');
                    $sheet->setCellValue('AE' .$lastrow, 'Total');
                    $sheet->setCellValue('AF' .$lastrow, 'PPh Bebas');
                    $sheet->setCellValue('AG' .$lastrow, 'Kode Barang');
					$sheet->setCellValue('AH' .$lastrow, 'Uraian');
				    $sheet->setCellValue('AI' .$lastrow, 'Jml Kemasan');
				    $sheet->setCellValue('AK' .$lastrow, 'Jml Sat Harga');
				    $sheet->setCellValue('AM' .$lastrow, 'CIF');
				    $sheet->setCellValue('AN' .$lastrow, 'Harga');
				    $sheet->setCellValue('AO' .$lastrow, 'No. SPTNP');
				    $sheet->setCellValue('AP' .$lastrow, 'Tgl SPTNP');

					$rowIndex = 0;
					$rowId = 0;
					foreach ($data as $dt){
						if ($dt->ID != $rowId){
						    $rowId = $dt->ID;
						    $rowIndex += 1;
						    $lastrow += 1;
						}
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $rowIndex);
						$sheet->setCellValue('B' .$lastrow, $dt->IMPORTIR);
						$sheet->setCellValue('C' .$lastrow, $dt->CUSTOMER);
						$sheet->setCellValue('D' .$lastrow, $dt->TGLTIBA);
						$sheet->setCellValue('E' .$lastrow, $dt->NOAJU);
						$sheet->setCellValue('F' .$lastrow, $dt->TGLAJU);
						$sheet->setCellValue('G' .$lastrow, $dt->NOPEN);
						$sheet->setCellValue('H' .$lastrow, $dt->TGLNOPEN);
						$sheet->setCellValue('I' .$lastrow, $dt->NO_BL);
						$sheet->setCellValue('J' .$lastrow, $dt->TGLBL);
						$sheet->setCellValue('K' .$lastrow, $dt->NO_INV);
						$sheet->setCellValue('L' .$lastrow, $dt->TGLINV);
						$sheet->setCellValue('M' .$lastrow, $dt->JUMLAH_KEMASAN);
						$sheet->setCellValue('N' .$lastrow, $dt->TGLSPPB);
						$sheet->setCellValue('O' .$lastrow, $dt->TGLKELUAR);
						$sheet->setCellValue('P' .$lastrow, $dt->TGLTERIMA);
						$sheet->setCellValue('Q' .$lastrow, $dt->JALURDOK);
    					$sheet->setCellValue('R' .$lastrow, $dt->PENGIRIM);
    					$sheet->setCellValue('S' .$lastrow, $dt->NO_FORM);
    					$sheet->setCellValue('T' .$lastrow, $dt->TGLFORM);
    					$sheet->setCellValue('U' .$lastrow, $dt->NO_LS);
    					$sheet->setCellValue('V' .$lastrow, $dt->TGLLS);
                        $sheet->setCellValue('W' .$lastrow, $dt->JENISDOKUMEN);
                        $sheet->setCellValue('X' .$lastrow, $dt->MATAUANG);
                        $sheet->setCellValue('Y' .$lastrow, $dt->NDPBM);
                        $sheet->setCellValue('Z' .$lastrow, $dt->NILAI);
                        $sheet->getStyle('Z' .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
                        $sheet->setCellValue('AA' .$lastrow, $dt->BM);
                        $sheet->getStyle('AA' .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
                        $sheet->setCellValue('AB' .$lastrow, $dt->BMT);
                        $sheet->getStyle('AB' .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
                        $sheet->setCellValue('AC' .$lastrow, $dt->PPN);
                        $sheet->getStyle('AC' .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
                        $sheet->setCellValue('AD' .$lastrow, $dt->PPH);
                        $sheet->getStyle('AD' .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
                        $sheet->setCellValue('AE' .$lastrow, $dt->TOTAL);
						$sheet->getStyle('AE' .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValue('AF' .$lastrow, $dt->PPH_BEBAS);
						$sheet->getStyle('AF' .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValue('AG' .$lastrow, $dt->KODEBARANG);
						$sheet->setCellValue('AH' .$lastrow, $dt->URAIAN);
						$sheet->setCellValue('AI' .$lastrow, $dt->JMLKEMASAN);
						$sheet->getStyle('AH' .$lastrow)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValue('AJ' .$lastrow, $dt->SATUANKEMASAN);
						$sheet->setCellValue('AK' .$lastrow, $dt->JMLSATHARGA);
						$sheet->getStyle('AJ' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
						$sheet->setCellValue('AL' .$lastrow, $dt->satuan);
						$sheet->setCellValue('AM' .$lastrow, $dt->CIF);
						$sheet->getStyle('AL' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.000');
						$sheet->setCellValue('AN' .$lastrow, $dt->HARGA);
						$sheet->getStyle('AM' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.000');
						$sheet->setCellValue('AO' .$lastrow, $dt->NOSPTNP);
						$sheet->setCellValue('AP' .$lastrow, $dt->TGLSPTNP);
						
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
			$postKategori3 = $this->post("kategori3");
			$dari3 = $this->post("dari3");
			$sampai3 = $this->post("sampai3");		

			$this->loadModel("Transaksi");		
			$data = $this->transaksi->browseKonversi($postKantor, $postCustomer, $postImportir, $postKategori1,
									$isikategori1, $postKategori2, $dari2, $sampai2, $postKategori3, $dari3, $sampai3);
			if ($data){
				$export = $this->get("export");
				if ($export == "1"){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A1', 'KANTOR');
					if ($postKantor && trim($postKantor) != ""){
    					$sheet->setCellValue('C1', $this->transaksi->getKantor($postKantor)->URAIAN);
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
					if ($postKategori3 && trim($postKategori3) != ""){
						$lastrow += 1;
						$sheet->setCellValue('A' .$lastrow, $postKategori3);
						$sheet->setCellValue('C' .$lastrow, $dari3 == "" ? "-" : Date("d M Y", strtotime($dari3)));
						$sheet->setCellValue('D' .$lastrow, "sampai");
						$sheet->setCellValue('E' .$lastrow, $sampai3 == "" ? "-" : Date("d M Y", strtotime($sampai3)));					
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
			$dari = $this->post("dari2");
			$sampai = $this->post("sampai2");
			$this->loadModel("Transaksi");		
			$data = $this->transaksi->stokProduk($postImportir, $dari, $sampai, $postKategori1,
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
						$detail = $this->transaksi->detailStokProduk($postImportir, $dari, $sampai, $dt->id);						
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
										"datakategori1" => Array("Kode Produk"),"datakategori2" => Array("Tanggal")
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
					$sheet->setCellValue('A1', 'CUSTOMER');
					if ($postCustomer && trim($postCustomer) != ""){
						$sheet->setCellValue('C1', $this->transaksi->getCustomer($postCustomer)->nama_customer);
					}
					else {
						$sheet->setCellValue('C1', "Semua");
					}
					$sheet->setCellValue('A2', 'IMPORTIR');
					if ($postImportir && trim($postImportir) != ""){
						$sheet->setCellValue('C2', $this->transaksi->getImportir($postImportir)->NAMA);
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
						$detail = $this->transaksi->getDetailStokBarang($dt->ID, $postKategori2, $dari2, $sampai2);						
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
    					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    					header('Content-Disposition: attachment;filename="stokbarang.xlsx"');
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
										"datakategori2" => Array("Tanggal Terima", "Tanggal DO")
										]);
    	}
	}
	public function detailstokproduk()
	{
		$id = $this->post("id");
		parse_str($this->post("form"), $form);
		$this->loadModel("Transaksi");
		$data = $this->transaksi->detailStokProduk($form["importir"],$form['dari2'], $form['sampai2'], $id);
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
		parse_str($this->post("form"), $form);
		$this->loadModel("Transaksi");
		$data = $this->transaksi->getDetailStokBarang($id, $form["kategori2"], $form["dari2"], $form["sampai2"]);
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
		$this->loadModel("Transaksi");
		if ($id != ""){
    		$data = $this->transaksi->getTransaksiDOrder($id, false);
	    	$no_do = $data["header"]->NO_DO;
		}
		else {
		    $no_do = "";
		}
		$this->render("pengeluaran.php",["breads" => $breadcrumb, 
									"stylesheets" => $assets["stylesheets"],"no_do" => $no_do, 
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
		$pembeli = $this->transaksi->getPembeli();
		if ($id != ""){
			$dtTransaksi = $this->transaksi->getTransaksiDOrder($id);
			$pengeluaran = $this->transaksi->getDataPengeluaran($id);
		}
		else {
			$notransaksi = " (Baru)";
		}
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], "datapembeli" => $pembeli, 
				"header" => isset($dtTransaksi["header"]) ? $dtTransaksi["header"] : [], "breads" => $breadcrumb,  
				"idtransaksi" => $id, "detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}",
				"pengeluaran" => isset($pengeluaran) ? json_encode($pengeluaran) : "{}"
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
		$this->loadModel("Transaksi");
		$data = $this->transaksi->getTransaksiDOrder($nodo, false, "NO_DO");
		header('Content-Type: application/json');
		if ($data){					
		    $pengeluaran = $this->transaksi->getDataPengeluaran($data["header"]->ID);
			print json_encode(["header" => $data, "detail" => $pengeluaran]);
		}
		else {
			print json_encode(["error" => "Data Tidak Ditemukan"]);
		}
	}
	public function userquota($id = "")
    {		
		$breadcrumb[] = Array("link" => "/", "text" => "Home");
		$breadcrumb[] = Array("link" => "/transaksi/browsesaldoquota", "text" => "Browse Saldo Quota");
		$breadcrumb[] = Array("text" => "Perekaman Quota");
        $assets = $this->prepareAssets();
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "/web/assets/js/dropzone.min.js";
		$assets["stylesheets"][] = "/web/assets/css/dropzone.min.css";
		$this->loadModel("Transaksi");		
		$this->loadModel("Satuan");
		$dtImportir = $this->transaksi->getImportir();
		$dtSatuan = $this->satuan->getSatuan();
		if ($id != ""){
    		$dtTransaksi = $this->transaksi->getTransaksiQuota($id);
    		$dtFiles = $this->transaksi->getFiles($id, 2);
		}
		$data = ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
				"header" => isset($dtTransaksi) ? $dtTransaksi["header"] : "{}" , "breads" => $breadcrumb,  
				"idtransaksi" => $id, "importir" => $dtImportir,"files" => isset($dtFiles) ? $dtFiles : "{}",
				"datasatuan" => $dtSatuan, "detail" => isset($dtTransaksi["detail"]) ? json_encode($dtTransaksi["detail"]) : "{}"
			];		
		$this->render("transaksiquota.php", $data);
	}
	public function getPI()
	{
	    $id = $this->post("id");
	    $this->loadModel("Transaksi");
	    $data = $this->transaksi->getPI($id);
	    header('Content-Type: application/json');
	    if ($data){
	        print json_encode(["ID" => $data->ID, "NO_PI" => $data->NO_PI, "TGL_PI" => $data->TGLPI]);
	    }
	    else {
	        print json_encode(["ID" => "", "NO_PI" => "", "TGL_PI" => ""]);
	    }
	}
	public function browseSaldoQuota()
    {
		$filter = $this->get("filter");
		if ($filter && $filter == "1"){
			//$postKantor = $this->post("kantor");
			//$postCustomer = $this->post("customer");
			$postImportir = $this->post("importir");			
			$postKategori1 = $this->post("kategori1");
			$isikategori1 = $this->post("isikategori1");
			$this->loadModel("Transaksi");		
			$data = $this->transaksi->saldoQuota($postImportir, $postKategori1, $isikategori1);
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
					
					$sheet->setCellValue('A' .$lastrow, 'No');
					$sheet->setCellValue('B' .$lastrow, 'Importir');
					$sheet->setCellValue('C' .$lastrow, 'Kode HS');
					$sheet->setCellValue('D' .$lastrow, 'Saldo Awal');
					$sheet->setCellValue('E' .$lastrow, 'Terpakai');
					$sheet->setCellValue('F' .$lastrow, 'Stok Akhir');
					$sheet->setCellValue('G' .$lastrow, "Satuan");
					$sheet->setCellValue('H' .$lastrow, "Consignee");							
					$sheet->setCellValue('I' .$lastrow, "Customer");
					$sheet->setCellValue('J' .$lastrow, "No. VO");
					$sheet->setCellValue('K' .$lastrow, "No Inv");						
					$sheet->setCellValue('L' .$lastrow, "No BL");									
                    $sheet->setCellValue('M' .$lastrow, "Booking");
                    $sheet->setCellValue('N' .$lastrow, "Realisasi");
                    $sheet->setCellValue('O' .$lastrow, "Satuan");
                    $no = 0;
					foreach ($data as $dt){
						$detail = $this->transaksi->detailSaldoQuota($dt->ID, $dt->KODE_HS);						
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
        						$sheet->setCellValue('I' .$lastrow, $det->NAMACUSTOMER);
        						$sheet->setCellValue('J' .$lastrow, $det->NO_VO);
        						$sheet->setCellValue('K' .$lastrow, $det->NO_INV);
        						$sheet->setCellValue('L' .$lastrow, $det->NO_BL);
        						$sheet->setCellValue('M' .$lastrow, $det->BOOKING);
        						$sheet->getStyle('M' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
        						$sheet->setCellValue('N' .$lastrow, $det->REALISASI);
        						$sheet->getStyle('N' .$lastrow)->getNumberFormat()->setFormatCode('#,##0.00');
        						$sheet->setCellValue('O' .$lastrow, $det->NAMASATUAN);
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
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="browsesaldoquota.xlsx"');
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
			$breadcrumb[] = Array("text" => "Browse Saldo Quota");
			$this->loadModel("Transaksi");
			$importir = $this->transaksi->getImportir();
			
					
			$this->render("saldoquota.php",["breads" => $breadcrumb, "stylesheets" => $assets["stylesheets"], 
										"scripts" => $assets["scripts"],
										"dataimportir" => $importir, 								
										"datakategori1" => Array("Kode HS", "No VO", "No Inv")
										]);
		}
	}
	public function detailsaldoquota()
	{
		$id = $this->post("id");
		$kodehs = $this->post("kodehs");
		$this->loadModel("Transaksi");
		$data = $this->transaksi->detailSaldoQuota($id, $kodehs);
		if (!$data){
			$response["data"] = [];
		}
		else {
			$response["data"] = $data;
		}
		header('Content-Type: application/json');
		print json_encode($response);
	}
	public function voucher($id = "")
	{
		$assets = $this->prepareAssets();
        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Perekaman Voucher");
		$assets["scripts"][] = "/web/assets/js/jquery.inputmask.bundle.js";
		$this->loadModel("Transaksi");
		if ($id != ""){
    		$data = $this->transaksi->getTransaksiVoucher($id, false);
	    	$no_voucher = sprintf('%08d', $data["header"]["ID"]);
	    	$tanggal = $data["header"]["TANGGAL"];
		}
		else {
		    $no_do = "";
		    $no_voucher = "Baru";
		    $tanggal = Date("d-n-Y");
		}
		$this->render("transaksivoucher.php",["breads" => $breadcrumb, 
									"stylesheets" => $assets["stylesheets"],"no_voucher" => $no_voucher, "tgl_voucher" => $tanggal, 
									"scripts" => $assets["scripts"]]);		
	}
	public function getBL($id)
	{
	    $this->loadModel("Transaksi");
	    $data = $this->transaksi->getBL($id);
	    header('Content-Type: application/json');
        $response = [];
	    if ($data){
	    	$response["data"] = $data;
	    }
	    else {
	        $response["data"] = [];   
	    }
	    print json_encode($response);
	}

}
