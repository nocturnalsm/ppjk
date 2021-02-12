<?php

namespace App\Controllers;

use Now\System\Packages\PageController as PageController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends PageController {

    public function prepareAssets()
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
    public function bongkar()
    {
        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Laporan Pembongkaran");
        $this->loadModel("Transaksi");
        $kantor = $this->transaksi->getKantor();
        $gudang = $this->transaksi->getGudang();
        $customer = $this->transaksi->getCustomer();
        $importir = $this->transaksi->getImportir();
        $assets = $this->prepAssets();
        $this->render("filterlaporan.php", ["kodekantor" => $kantor, 
                        "stylesheets" => $assets["stylesheets"],
                        "scripts" => $assets["scripts"],
                        "gudang" => $gudang, "customer" => $customer, "importir" => $importir,
                        "judul" => "Laporan Pembongkaran",
                        "breads" => $breadcrumb,
                        "kategori" => Array("Tanggal Bongkar","Tanggal Rekam","Tanggal Tiba","Tanggal Nopen Dok In","Tanggal Keluar"),
                        "dari" => Date("01-m-Y"), "sampai" => Date("d-m-Y")]);
    }    
    public function harian()
    {
        $breadcrumb[] = Array("link" => "../", "text" => "Home");
		$breadcrumb[] = Array("text" => "Laporan Harian");
        $this->loadModel("Transaksi");
        $kantor = $this->transaksi->getKantor();
        $gudang = $this->transaksi->getGudang();
        $customer = $this->transaksi->getCustomer();
        $assets = $this->prepAssets();
        $this->render("filterharian.php", ["kodekantor" => $kantor, 
                        "stylesheets" => $assets["stylesheets"],
                        "scripts" => $assets["scripts"],
                        "gudang" => $gudang, "datacustomer" => $customer,
                        "judul" => "Laporan Harian",
                        "breads" => $breadcrumb,                        
                        "datakategori" => Array("Tanggal Perekaman","Tanggal Bongkar","Tanggal Tiba","Tanggal Keluar",
                        "Tanggal Nopen Dok In","Tanggal Nopen Dok Out")]);
    }
    public function generate()
    {
        $laporan = $this->get("namalaporan");
        $kantor = $this->get("kantor");
        $gudang = $this->get("gudang");
        $this->loadModel("Transaksi");
        $namakantor = $this->transaksi->getKantor($kantor);
        if ($gudang == ""){
            $namagudang = "Semua";
        }
        else {
            $namagudang = $this->transaksi->getGudang($gudang)->URAIAN;
        }
        if ($laporan == "bongkar"){
            $dari = $this->get("dari");
            $sampai = $this->get("sampai");
            $kategori = $this->get("kategori");
            $customer = $this->get("customer");
            $importir = $this->get("importir");        
            $transaksi = $this->transaksi->getMonitoring($kantor, $gudang, $customer, $importir, $kategori, $dari, $sampai);
                        
            if (count($transaksi) > 0){
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'KANTOR');
                $sheet->setCellValue('C1', $namakantor->URAIAN);
                $sheet->setCellValue('A2', 'GUDANG');
                $sheet->setCellValue('C2', $namagudang);
                $sheet->setCellValue('A3', $kategori);
                $sheet->setCellValue('C3', $dari == "" ? "-" : Date("d M Y", strtotime($dari)));
                $sheet->setCellValue('D3', "sampai");
                $sheet->setCellValue('E3', $sampai == "" ? "-" : Date("d M Y", strtotime($sampai)));					            
                $sheet->setCellValue('A5', 'No');
                $sheet->setCellValue('B5', 'No. BL');
                $sheet->setCellValue('C5', 'Jml Kontainer');
                $sheet->setCellValue('D5', 'Jenis Barang');
                $sheet->setCellValue('E5', 'Jml Kemasan');
                $sheet->setCellValue('F5', 'Consignee');
                $sheet->setCellValue('G5', 'Customer');                
                $sheet->setCellValue('H5', 'Aju Dok In');
                $sheet->setCellValue('I5', 'Nopen Dok In');
                $sheet->setCellValue('J5', 'Tgl Dok In');
                $sheet->setCellValue('K5', 'Importir');
                $sheet->setCellValue('L5', 'Tgl Bongkar');
                $sheet->setCellValue('M5', 'Form');
                $sheet->setCellValue('B6', 'No Kontainer');
                $sheet->setCellValue('C6', 'Uk Kontainer');

                $lastrow = 6;
                $i = 0;
                foreach ($transaksi as $dt){
                    $lastrow += 1;
                    $sheet->setCellValue('A' .$lastrow, $i+1);
                    $sheet->setCellValueExplicit('B' .$lastrow, $dt["header"]->NO_BL, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('C' .$lastrow, $dt["header"]->JUMLAH_KONTAINER);
                    $sheet->setCellValue('D' .$lastrow, $dt["header"]->NAMAJENISBARANG);
                    $sheet->setCellValue('E' .$lastrow, $dt["header"]->JUMLAH_KEMASAN);
                    $sheet->setCellValue('F' .$lastrow, $dt["header"]->NAMACONSIGNEE);
                    $sheet->setCellValue('G' .$lastrow, $dt["header"]->NAMACUSTOMER);
                    $sheet->setCellValueExplicit('H' .$lastrow, $dt["header"]->AJU1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('I' .$lastrow, $dt["header"]->NOPEN1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('J' .$lastrow, isset($dt["header"]->TGLNOPEN1) ? Date("d-M-Y", strtotime($dt["header"]->TGLNOPEN1)) : "");
                    $sheet->setCellValue('K' .$lastrow, $dt["header"]->NAMAIMPORTIR);
                    $sheet->setCellValue('L' .$lastrow, $dt["header"]->TGLBONGKAR);
                    $sheet->setCellValue('M' .$lastrow, $dt["header"]->FORM);
                    foreach ($dt["kontainer"] as $kont){
                        $lastrow += 1;
                        $sheet->setCellValueExplicit('B' .$lastrow, $kont->NOMOR_KONTAINER, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue('C' .$lastrow, $kont->URAIAN);                
                    }
                    $lastrow += 1;
                    $i += 1;
                }
                $this->prepExcelHeaders("report_bongkar");
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            }
        }
        else if ($laporan == "harian"){
            $kategori1 = $this->get("kategori1");
            $dari1 = $this->get("dari1");
            $sampai1 = $this->get("sampai1");
            $kategori2 = $this->get("kategori2");
            $dari2 = $this->get("dari2");
            $sampai2 = $this->get("sampai2");
            $customer = $this->get("customer");
            $transaksi = $this->transaksi->getHarian($kantor, $gudang, $customer,
                                                    $kategori1, $dari1, $sampai1,
                                                    $kategori2, $dari2, $sampai2);
           
            if (count($transaksi) > 0){
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'KANTOR');
                $sheet->setCellValue('C1', $namakantor->URAIAN);
                $sheet->setCellValue('A2', 'GUDANG');
                $sheet->setCellValue('C2', $namagudang);
                $lastrow = 3;
                if ($kategori1 != ""){
                    $sheet->setCellValue('A'.$lastrow, "Periode " .$kategori1);
                    $sheet->setCellValue('C'.$lastrow, $dari1 == "" ? "-" : Date("d M Y", strtotime($dari1)));
                    $sheet->setCellValue('D'.$lastrow, "sampai");
                    $sheet->setCellValue('E'.$lastrow, $sampai1 == "" ? "-" : Date("d M Y", strtotime($sampai1)));
                    $lastrow += 1;
                }
                if ($kategori2 != ""){
                    $sheet->setCellValue('A'.$lastrow, "Periode " .$kategori2);
                    $sheet->setCellValue('C'.$lastrow, $dari2 == "" ? "-" : Date("d M Y", strtotime($dari2)));
                    $sheet->setCellValue('D'.$lastrow, "sampai");
                    $sheet->setCellValue('E'.$lastrow, $sampai2 == "" ? "-" : Date("d M Y", strtotime($sampai2)));
                    $lastrow += 1;
                }
                $sheet->setCellValue('A'.$lastrow, 'Tgl Perekaman');
                $sheet->setCellValue('B'.$lastrow, 'No. BL');
                $sheet->setCellValue('C'.$lastrow, 'Tgl Berangkat');
                $sheet->setCellValue('D'.$lastrow, 'Tgl Tiba');
                $sheet->setCellValue('E'.$lastrow, 'Shipper');
                $sheet->setCellValue('F'.$lastrow, 'Kapal');
                $sheet->setCellValue('G'.$lastrow, 'Qty');
                $sheet->setCellValue('H'.$lastrow, 'Jns Kemasan');
                $sheet->setCellValue('I'.$lastrow, 'Jns Barang');                
                $sheet->setCellValue('J'.$lastrow, 'Customer'); 
                $sheet->setCellValue('K'.$lastrow, 'Consignee');                                                
                $sheet->setCellValue('L'.$lastrow, 'No. INPL');
                $sheet->setCellValue('M'.$lastrow, 'No. Form');
                $sheet->setCellValue('N'.$lastrow, 'GW');
                $sheet->setCellValue('O'.$lastrow, 'CBM');
                $sheet->setCellValue('P'.$lastrow, 'Pel Muat');
                $lastrow += 1;
                $sheet->setCellValue('B'.$lastrow, 'No Kontainer');
                $sheet->setCellValue('C'.$lastrow, 'Uk Kontainer');
                $lastrow += 1;
                $i = 0;
                foreach ($transaksi as $dt){
                    $lastrow += 1;
                    $sheet->setCellValue('A' .$lastrow, $dt["header"]->TGLREKAM);
                    $sheet->setCellValueExplicit('B' .$lastrow, $dt["header"]->NO_BL, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('C' .$lastrow, $dt["header"]->TGLBERANGKAT);
                    $sheet->setCellValue('D' .$lastrow, $dt["header"]->TGLTIBA);
                    $sheet->setCellValue('E' .$lastrow, $dt["header"]->NAMASHIPPER);
                    $sheet->setCellValue('F' .$lastrow, $dt["header"]->KAPAL);
                    $sheet->setCellValue('G' .$lastrow, $dt["header"]->JUMLAH_KEMASAN);
                    $sheet->setCellValue('H' .$lastrow, $dt["header"]->JENISKEMASAN);
                    $sheet->setCellValue('I' .$lastrow, $dt["header"]->NAMAJENISBARANG);
                    $sheet->setCellValue('J' .$lastrow, $dt["header"]->NAMACUSTOMER);
                    $sheet->setCellValue('K' .$lastrow, $dt["header"]->NAMACONSIGNEE);
                    $sheet->setCellValueExplicit('L' .$lastrow, $dt["header"]->NO_INPL, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('M' .$lastrow, $dt["header"]->NO_FORM, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('N' .$lastrow, $dt["header"]->GW);
                    $sheet->setCellValue('O' .$lastrow, $dt["header"]->CBM);
                    $sheet->setCellValue('P' .$lastrow, $dt["header"]->NAMAPELMUAT);
                    foreach ($dt["kontainer"] as $kont){
                        $lastrow += 1;
                        $sheet->setCellValueExplicit('B' .$lastrow, $kont->NOMOR_KONTAINER, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue('C' .$lastrow, $kont->URAIAN);                
                    }
                    $lastrow += 1;
                    $i += 1;
                }
                $this->prepExcelHeaders("report_harian");
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            }
        }
    }
    
    public function prepExcelHeaders($filename)
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename .'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
    }
}