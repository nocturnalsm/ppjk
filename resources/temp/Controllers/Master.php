<?php

namespace App\Controllers;

use Now\System\Packages\PageController as PageController;

class Master extends PageController {
	
	private $data;
	private $breadcrumb = Array();
	private $permission;

	private function prepareAssets()
	{
		return ["stylesheets" => ["../web/assets/datatables/css/jquery.dataTables.min.css",
								  "../web/assets/datatables/css/jquery.dataTables_themeroller.css",
								  "../web/assets/datatables/Buttons-1.5.4/css/buttons.dataTables.min.css",
								  "../web/assets/datatables/Select-1.2.6/css/select.dataTables.min.css",
								  "../web/assets/datatables/Responsive-2.2.2/css/responsive.dataTables.min.css"],
				"scripts" => ["../web/assets/datatables/js/jquery.dataTables.min.js",
							  "../web/assets/datatables/Buttons-1.5.4/js/dataTables.buttons.min.js",
							  "../web/assets/datatables/Select-1.2.6/js/dataTables.select.min.js",
							  "../web/assets/datatables/Responsive-2.2.2/js/dataTables.responsive.min.js"]];
	}	
	public function jenisbarang()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Jenis Barang");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "../web/assets/app/jenisbarang.js";
		$this->loadModel("JenisBarang");		
		$this->render("jenisbarang.php", ["stylesheets" => $assets["stylesheets"], 
										 "scripts" => $assets["scripts"], 
										 "breads" => $breadcrumb,
										 "columns" => Array("Kode","Uraian")]);
	}
	public function getdata_jenisbarang()
	{		
		$search = $this->get("search");
		$draw = $this->get("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('JenisBarang');
		$fetch = $this->jenisbarang->getData($search, $start, $length, $order);
		$num_rows = $this->jenisbarang->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function satuan()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Satuan");
		$assets = $this->prepareAssets();
		$this->loadModel("Satuan");		
		$this->render("satuan.php", ["stylesheets" => $assets["stylesheets"],
										 "breads" => $breadcrumb,
										 "scripts" => $assets["scripts"], 
										 "columns" => Array("Kode","Satuan")]);
	}
	public function getdata_satuan()
	{		
		$search = $this->get("search");
		$draw = $this->get("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Satuan');
		$fetch = $this->satuan->getData($search, $start, $length, $order);
		$num_rows = $this->satuan->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function produk()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Produk");
		$assets = $this->prepareAssets();
		$assets["stylesheets"][] = "../web/assets/jquery-ui/jquery-ui.min.css";
		$assets["scripts"][] = "../web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "../web/assets/jquery-ui/jquery-ui.min.js";
		$this->loadModel("Produk");		
		$this->loadModel("Satuan");
		$satuan = $this->satuan->getSatuan();
		$this->render("produk.php", ["stylesheets" => $assets["stylesheets"],
										 "breads" => $breadcrumb,
										 "satuan" => $satuan,
										 "scripts" => $assets["scripts"], 
										 "columns" => Array("Kode","Nama","HS Code","Satuan","Harga")]);
	}
	public function getdata_produk()
	{		
		$search = $this->get("search");
		$draw = $this->get("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Produk');
		$fetch = $this->produk->getData($search, $start, $length, $order);
		$num_rows = $this->produk->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}	
	public function penerima()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Penerima");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "../web/assets/app/penerima.js";
		$this->loadModel("Penerima");		
		$this->render("penerima.php", ["stylesheets" => $assets["stylesheets"], 
										 "scripts" => $assets["scripts"], 
										 "breads" => $breadcrumb,
										 "columns" => Array("Kode","Penerima")]);
	}
	public function getdata_penerima()
	{		
		$search = $this->get("search");
		$draw = $this->get("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Penerima');
		$fetch = $this->penerima->getData($search, $start, $length, $order);
		$num_rows = $this->penerima->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function jeniskemasan()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Jenis Kemasan");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/app/jeniskemasan.js";
		$this->render("jeniskemasan.php", ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], "breads" => $breadcrumb,
									 "columns" => Array("Kode","Uraian")]);
	}
	public function getdata_gudang()
	{			
		$search = $this->get("search");
		$draw = $this->post("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Gudang');
		$fetch = $this->gudang->getData($search, $start, $length, $order);
		$num_rows = $this->gudang->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}	
	public function gudang()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Gudang");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/app/gudang.js";
		$this->render("gudang.php", ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], "breads" => $breadcrumb,
									 "columns" => Array("Kode","Uraian")]);
	}
	public function getdata_bank()
	{			
		$search = $this->get("search");
		$draw = $this->post("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Bank');
		$fetch = $this->bank->getData($search, $start, $length, $order);
		$num_rows = $this->bank->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}	
	public function bank()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Bank");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/app/bank.js";
		$this->render("bank.php", ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], "breads" => $breadcrumb,
									 "columns" => Array("Bank")]);
	}
	public function getdata_rekening()
	{			
		$search = $this->get("search");
		$draw = $this->post("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Rekening');
		$fetch = $this->rekening->getData($search, $start, $length, $order);
		$num_rows = $this->rekening->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}	
	public function rekening()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Rekening");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/app/rekening.js";		
		$this->loadModel("Bank");
		$dtBank = $this->bank->getData([]);
		$this->render("rekening.php", ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], "breads" => $breadcrumb,
									 "columns" => Array("Bank","No Rekening","Nama"),
									 "databank" => $dtBank]);
	}
	public function getdata_jeniskemasan()
	{			
		$search = $this->get("search");
		$draw = $this->post("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('JenisKemasan');
		$fetch = $this->jeniskemasan->getData($search, $start, $length, $order);
		$num_rows = $this->jeniskemasan->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}	
	public function pelmuat()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Pelabuhan Muat");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/app/pelmuat.js";
		$this->render("pelmuat.php", ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], "breads" => $breadcrumb,
									 "columns" => Array("Kode","Uraian")]);
	}
	public function getdata_pelmuat()
	{			
		$search = $this->get("search");
		$draw = $this->post("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Pelmuat');
		$fetch = $this->pelmuat->getData($search, $start, $length, $order);
		$num_rows = $this->pelmuat->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function importir()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Importir");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/js/jquery.inputmask.bundle.js";
		$assets["scripts"][] = "../web/assets/app/importir.js";
		$this->render("importir.php", ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], 
									 "breads" => $breadcrumb,
									 "columns" => Array("NPWP","Nama","Alamat","Telepon","Email")]);
	}
	public function getdata_importir()
	{	
		$search = $this->get("search");
		$draw = $this->get("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Importir');
		$fetch = $this->importir->getData($search, $start, $length, $order);
		$num_rows = $this->importir->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_filtered,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function jenisdokumen()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Jenis Dokumen");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/app/jenisdokumen.js";
		$this->render("jenisdokumen.php", ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], "breads" => $breadcrumb,
									 "columns" => Array("Kode","Jenis Dokumen")]);
	}
	public function getdata_jenisdokumen()
	{			
		$search = $this->get("search");
		$draw = $this->post("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('JenisDokumen');
		$fetch = $this->jenisdokumen->getData($search, $start, $length, $order);
		$num_rows = $this->jenisdokumen->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function kantor()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Kode Kantor");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/app/kantor.js";
		$this->render("kantor.php", ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], "breads" => $breadcrumb,
									 "columns" => Array("Kode","Nama Kantor")]);
	}
	public function getdata_kantor()
	{			
		$search = $this->get("search");
		$draw = $this->post("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Kantor');
		$fetch = $this->kantor->getData($search, $start, $length, $order);
		$num_rows = $this->kantor->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function ratedpp()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Rate DPP");
		$assets = $this->prepareAssets();
		$assets["scripts"][] = "../web/assets/js/jquery.inputmask.bundle.js";
		$this->render("ratedpp.php", ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], "breads" => $breadcrumb,
									 "columns" => Array("Rate")]);
	}
	public function getdata_ratedpp()
	{			
		$search = $this->get("search");
		$draw = $this->post("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Rate');
		$fetch = $this->rate->getData($search, $start, $length, $order);
		$num_rows = $this->rate->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function pembeli()
	{							
		$breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Pembeli");
		$assets = $this->prepareAssets();
		$this->loadModel("Pembeli");
		$customer = $this->pembeli->query("SELECT id_customer, nama_customer FROM plbbandu_app15.tb_customer ORDER BY nama_customer")->get();
		$this->render("pembeli.php", ["stylesheets" => $assets["stylesheets"], "scripts" => $assets["scripts"], "breads" => $breadcrumb,
									 "customer" => $customer, "columns" => Array("Kode","Nama Pembeli","Customer","Alamat")]);
	}
	public function getdata_pembeli()
	{			
		$search = $this->get("search");
		$draw = $this->post("draw");
		$start = $this->get("start");
		$length = $this->get("length");
		$order = $this->get("order");
		$this->loadModel('Pembeli');
		$fetch = $this->pembeli->getData($search, $start, $length, $order);
		$num_rows = $this->pembeli->getData($search)->num_rows();
		$num_filtered = $fetch->num_rows();
		$data = Array("draw" => $draw,
					  "recordsTotal" => $num_filtered,
					  "recordsFiltered" => $num_rows,
					  "data" => $fetch->get());
		header('Content-Type: application/json');
        print json_encode($data);
	}
	public function crud()
	{
		$action = $this->post("action");
		$message = Array();
		if ($action){
			$fields = $this->post("input"); 
			parse_str($fields, $input);	
			try {
				switch ($action){
					case "satuan":
						$this->loadModel("Satuan");
						if ($input["input-action"] == "add"){						
							$result = $this->satuan->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->satuan->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->satuan->drop($input["id"]);
						}
						break;
					case "produk":
						$this->loadModel("Produk");
						if ($input["input-action"] == "add"){						
							$result = $this->produk->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->produk->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->produk->drop($input["id"]);
						}
						break;
					case "jenisbarang":
						$this->loadModel("JenisBarang");
						if ($input["input-action"] == "add"){						
							$result = $this->jenisbarang->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->jenisbarang->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->jenisbarang->drop($input["id"]);
						}
						break;	
					case "jeniskemasan":
						$this->loadModel("JenisKemasan");
						if ($input["input-action"] == "add"){						
							$result = $this->jeniskemasan->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->jeniskemasan->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->jeniskemasan->drop($input["id"]);
						}
						break;
					case "gudang":
						$this->loadModel("Gudang");
						if ($input["input-action"] == "add"){						
							$result = $this->gudang->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->gudang->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->gudang->drop($input["id"]);
						}
						break;
					case "importir":
						$this->loadModel("Importir");
						if ($input["input-action"] == "add"){						
							$result = $this->importir->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->importir->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->importir->drop($input["id"]);
						}
						break;
					case "jenisdokumen":
						$this->loadModel("JenisDokumen");
						if ($input["input-action"] == "add"){						
							$result = $this->jenisdokumen->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->jenisdokumen->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->jenisdokumen->drop($input["id"]);
						}
						break;	
					case "kantor":
						$this->loadModel("Kantor");
						if ($input["input-action"] == "add"){						
							$result = $this->kantor->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->kantor->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->kantor->drop($input["id"]);
						}
						break;	
					case "pelmuat":
						$this->loadModel("Pelmuat");
						if ($input["input-action"] == "add"){						
							$result = $this->pelmuat->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->pelmuat->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->pelmuat->drop($input["id"]);
						}
						break;
					case "bank":
						$this->loadModel("Bank");
						if ($input["input-action"] == "add"){						
							$result = $this->bank->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->bank->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->bank->drop($input["id"]);
						}
						break;	
					case "rekening":
						$this->loadModel("Rekening");
						if ($input["input-action"] == "add"){						
							$result = $this->rekening->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->rekening->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->rekening->drop($input["id"]);
						}
						break;	
					case "penerima":
						$this->loadModel("Penerima");
						if ($input["input-action"] == "add"){						
							$result = $this->penerima->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->penerima->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->penerima->drop($input["id"]);
						}
						break;	
					case "pembeli":
						$this->loadModel("Pembeli");
						if ($input["input-action"] == "add"){						
							$result = $this->pembeli->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->pembeli->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->pembeli->drop($input["id"]);
						}
						break;	
					case "ratedpp":
						$this->loadModel("Rate");
						if ($input["input-action"] == "add"){						
							$result = $this->rate->add($input);
						}
						else if ($input["input-action"] == "edit"){
							$result = $this->rate->edit($input);
						}
						else if ($input["input-action"] == "delete"){
							$result = $this->rate->drop($input["id"]);
						}
						break;
				}
				$message["result"] = $result;
			}	
			catch (\Exception $e){
				$message["error"] = $e->getMessage();
			}
		}
		header('Content-Type: application/json');
        print json_encode($message);
	}
}

?>
