<style>        
    .table-report .border-left {
        border-left: 1px solid #000 !important;
        border-top: 1px solid #000 !important;
    }
    .table-report .border-inner {
        border-top: 1px solid #000 !important;
        border-left: 1px solid #000 !important;
    }
    .table-report .border-right {
        border-right: 1px solid #000000 !important;
    }
    .table-report .border-bottom {
        border-bottom: 1px solid #000 !important;
    }
    .table-report .number {
        text-align: right;
    }    
</style>
<h5>LAPORAN MONITORING PLB</h5><br>
<span>Kantor : {{ namakantor }}</span><br>
<span>Gudang : {{ namagudang }}</span><br>
<span>{{ kategori }} : {{ dari }} s/d {{ sampai }}</span><br>
<table class="table-report" width="100%" cellspacing="0" cellpadding="2">
    <thead>
        <tr>        
            <td rowspan="2" class="border-left border-bottom" width="5%">No</td>
            <td class="border-inner" width="10%">NO BL</td>
            <td class="border-inner" width="5%">JML KONT</td>
            <td class="border-inner" width="15%">JENIS BARANG</td>
            <td class="border-inner" width="5%">JML KEMASAN</td>
            <td rowspan="2" class="border-inner border-bottom" width="21%">CONSIGNEE</td>
            <td rowspan="2" class="border-inner border-bottom" width="5%">NO AJU</td>
            <td rowspan="2" class="border-inner border-bottom" width="5%">NOPEN</td>
            <td rowspan="2" class="border-inner border-bottom" width="8%">TGL NOPEN</td>
            <td rowspan="2" class="border-bottom border-inner border-right" width="21%">IMPORTIR</td>
        </tr>
        <tr>        
            <td class="border-inner border-bottom" colspan="2" width="15%">NO.KONT</td>
            <td class="border-inner border-bottom" colspan="2" width="20%">UK.KONT</td>
        </tr>
    </thead>
    <tbody>
        {% for data in transaksi %}
        <tr>        
            <td class="pt-2 border-left" valign="top" style="padding-bottom: 0.3cm;border-bottom: 1px solid #000" rowspan="2">{{ loop.index }}</td>
            <td valign="top" class="pt-2">{{ data.header.NO_BL }}</td>
            <td valign="top" class="pt-2 number text-center">{{ data.header.JUMLAH_KONTAINER }}</td>
            <td valign="top" class="pt-2">{{ data.header.NAMAJENISBARANG }}</td>
            <td valign="top" class="pt-2 number text-center">{{ data.header.JUMLAH_KEMASAN }}</td>
            <td valign="top" class="pt-2">{{ data.header.NAMACONSIGNEE }}</td>
            <td valign="top" class="pt-2">{{ data.header.AJU }}</td>
            <td valign="top" class="pt-2">{{ data.header.NOPEN }}</td>
            <td valign="top" class="pt-2">{{ data.header.TGL_NOPEN ? data.header.TGL_NOPEN|date("d-m-Y") : "" }}</td>
            <td valign="top" class="pt-2 border-right">{{ data.header.NAMAIMPORTIR }}</td>
        </tr>
        <tr>                 
            <td valign="top" width="35%" colspan="4" style="padding-bottom: 0.5cm;border-bottom: 1px solid #000">
                <table width="100%">
                    {% for kont in data.kontainer %}
                    <tr>   
                        <td width="70%">{{ kont.NOMOR_KONTAINER }}</td>
                        <td width="30%">{{ kont.URAIAN }}</td>
                    </tr>
                    {% endfor %}
                </table>
            </td>
            <td class="border-bottom border-right" colspan="5" width="60%"></td>
        </tr>
        {% endfor %}
    </tbody>
</table>


