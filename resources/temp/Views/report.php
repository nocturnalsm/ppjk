<div class="row mb-2" style="background-color: #dddddd;">
    <div class="col-sm-6 p-2">
        <a href="<?= site_url("laporan") ?>">
            <i class="fa fa-lg fa-chevron-circle-left"></i>&nbsp;
            <span class="small">Kembali</span>
        </a>
    </div>
    <div class="col-sm-6 text-right p-2">
        <a href="#" title="Cetak" onclick="printDiv()">
            <i class="fa fa-print"></i>
        </a>
    </div>
</div>
<div id="reportdiv" class="row">
    <style>
        @media screen, print {
            #report table {
                border-spacing: 0;
            }
            #report {
                font-size: 14px;
                width: 100%;
            }
            #report .report-content {
                width: 100%;            
            }        
            #report .report-title th, #report .report-title td {
                font-size: 12px;
            }
            #report .report-content th, #report .report-content td {
                font-size: 12px;
            }
            #report .number {
                text-align: right;
            }
            #report .report-content .footer.number {
                text-align: right;
                padding-right: 20px !important;
            }
            #report .page-footer {
                width: 100%;
            }
        }
        @media screen {
            pagebreak {
                display: none;
            }
            .page-footer {
                display: none;
            }
        }
        @media print {
            @page
            {
                size: A4;   
                margin-top: 10mm;
                margin-bottom: 5mm;
                margin-left: 10mm;
                margin-right: 10mm;
            }
            .pagebreak {page-break-after: always;page-break-before: always;} 
            .page-footer {
                display: block;
                font-size: 10px !important;
                margin-top:20px;        
            }
        }
        /*
        @media screen, print {
            .report-content {
                padding: 0;
                margin-top: 0;
            }
            .report-title {
                margin-bottom: 10px;
            }
            .report-content .borderless td {
                border: none;
            }
            .report-content .borderless th {
                background-color: #eee !important;
                padding: 8px;
            }
            .report-content .footer {
                background-color: #eee !important;
                padding: 8px !important;
            }
            .report-content .number {
                text-align: right;
                padding-right: 25px;
            }
           
            table {
                border-spacing: 0px;
            }
        }

        @media screen {
            .report-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            }
            .report-title-left {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            }
            .report-title-right {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            }
            .nodata {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: red;
            }
            .report-content {
            font-size: 12px;
            }
            .page-footer {
                display: none;
            }
            .report-footer {
            font-size: 12px;
            }
            .report-content tfoot {
                padding: 0px;
            }
            pagebreak {display:none}        
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
            @page
            {
                size: A4;   
                margin-top: 15mm;
                margin-bottom: 15mm;
                margin-left: 10mm;
                margin-right: 10mm;
            }
            .report-title {
                text-align: center;
                font-size: 13px;
                font-weight: bold;                             
                border-spacing: 0px;
            }
            .report-title-left {
                text-align: left;
                font-size: 11px;
            }
            .report-title-right {
                text-align: right;
                font-size: 11px;
            }
            .nodata {
                text-align: center;
                font-size: 11px;
                font-weight: bold;
                color: red;
            }
            table {
                font-size: 12px;
                border-spacing: 0px;
            }
            table th {
                text-align: left;
            }
            table td {
                padding-left: 8px;
            }
            table th.number {
                text-align: right;
                padding-right: 25px;
            }
            .page-footer {
                display: block;
                font-size: 10px !important;
                margin-top:20px;
            }
            .report-footer {
            font-size: 12px;
            }
            div.report-content .table tfoot td{
                padding: 3px !important;
            }
            pagebreak {page-break-after: always;page-break-before: always;}        
        }*/
    </style>
    <?= $report ?>
</div>