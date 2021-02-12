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
<h5>LAPORAN REALISASI (Periode Laporan)</h5><br>
<span>Kantor : {{ namakantor }}</span><br>
<span>Periode Laporan: {{ dari }} s/d {{ sampai }}</span><br>
<span>Kategori Biaya: {{ kategoribiaya }}</span><br>
<table class="table-report" width="100%" cellspacing="0" cellpadding="2">
    <thead>
        <tr>        
            <td rowspan="2" class="border-left border-bottom" width="10%">No</td>
            <td class="border-inner" width="20%">NO.BL</td>
            <td class="border-inner" width="15%">JML KMS</td>
            <td class="border-inner" width="10%">JML KONT</td>
            <td class="border-inner" width="20%">TGL LAPORAN</td>
            <td class="border-inner" width="10%">KLOTER</td>
            <td class="border-inner border-right" width="15%">TOTAL BIAYA</td>
        </tr>
        <tr>        
            <td class="border-inner border-bottom" width="20%">NO.KONT</td>
            <td class="border-inner border-bottom" width="15%">UK.KONT</td>
            <td class="border-inner border-bottom" width="10%"></td>
            <td class="border-inner border-bottom" colspan="2" width="30%">JNS.BIAYA</td>
            <td class="border-inner border-bottom border-right" width="15%">NOMINAL</td>
        </tr>
    </thead>
    <tbody>
        {% set totalBiaya = 0 %}
        {% for data in transaksi %}
        <tr>        
            <td class="pt-2" valign="top" style="padding-bottom: 0.3cm;border-bottom: 1px solid #000" rowspan="2" width="10%">{{ loop.index }}</td>
            <td class="pt-2" width="20%">{{ data.header.NO_BL }}</td>
            <td class="pt-2 number text-center" width="15%">{{ data.header.JUMLAH_KEMASAN|number_format(0,".",",") }}</td>
            <td class="pt-2 number text-center" width="10%">{{ data.header.JUMLAH_KONTAINER }}</td>
            <td class="pt-2 number" width="20%">{{ data.header.TGL_LAPORAN ? data.header.TGL_LAPORAN|date("d-m-Y") : "" }}</td>
            <td class="pt-2 number text-center" width="10%">{{ data.header.KLOTER }}</td>
            <td class="pt-2 number" width="15%">{{ data.totalbiaya|number_format(0,".",",") }}</td>
        </tr>
        <tr>                 
            <td valign="top" width="45%" colspan="3" style="padding-bottom: 0.5cm;border-bottom: 1px solid #000">
                <table width="100%">
                    {% for kont in data.kontainer %}
                    <tr>   
                        <td width="45%">{{ kont.NOMOR_KONTAINER }}</td>
                        <td width="55%">{{ kont.URAIAN }}</td>
                    </tr>
                    {% endfor %}
                </table>
            </td>
            <td valign="top" width="45%" colspan="3" style="padding-bottom: 0.5cm;border-bottom: 1px solid #000">
                <table width="100%">
                    {% for by in data.biaya %}
                    <tr>   
                        <td width="70%">{{ by.URAIAN }}</td>
                        <td class="number" width="30%">{{ by.NOMINAL|number_format(0,".",",") }}</td>
                    </tr>
                    {% set totalBiaya = totalBiaya + by.NOMINAL %}
                    {% endfor %}
                </table>
            </td>
        </tr>
        {% endfor %}
        <tr>
            <td class="number" colspan="6">
                <strong>Grand Total Biaya</strong>
            </td>
            <td class="number">
                {{ totalBiaya|number_format(0,".",".")}}
            </td>
        </tr>
    </tbody>
</table>