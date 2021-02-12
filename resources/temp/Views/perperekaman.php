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
<h5>LAPORAN REALISASI (Periode Perekaman)</h5><br>
<span>Kantor : {{ namakantor }}</span><br>
<span>Tanggal Perekaman : {{ dari }} s/d {{ sampai }}</span><br>
<span>Importir : {{ namaimportir }}</span>
<table class="table-report" width="100%" cellspacing="0" cellpadding="2">
    <thead>
        <tr>        
            <td rowspan="3" class="border-left border-bottom" width="10%">No</td>
            <td class="border-inner" width="30%">NAMA IMPORTIR</td>
            <td class="border-inner" width="15%">JML KONT</td>
            <td class="border-inner" width="15%">NOMINAL</td>
            <td class="border-inner" width="15%">TOTAL BIAYA</td>
            <td class="border-inner border-right" width="15%">BALANCE</td>
        </tr>
        <tr>        
            <td class="border-inner" width="30%">NO.BL</td>
            <td class="border-inner" width="15%">JNS.KMS</td>
            <td class="border-inner border-right" width="45%" colspan="3">
                <table width="100%" cellspacing="0">
                    <tr>
                        <td class="border-right" width="20%">JML.KMS</td>
                        <td class="border-right" width="20%">TGL.LAP</td>
                        <td class="border-right" width="20%">TGL.BC_16</td>
                        <td class="border-right" width="20%">NO.BC_16</td>
                        <td width="20%">KLOTER</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>        
            <td class="border-inner border-bottom" width="30%">NO.KONT</td>
            <td class="border-inner border-bottom" width="15%">UK.KONT</td>
            <td class="border-inner border-bottom" width="15%">JNS.BIAYA</td>
            <td class="border-inner border-bottom" width="15%">NOMINAL</td>
            <td class="border-inner border-bottom border-right" width="15%"></td>
        </tr>
    </thead>
    <tbody>
        {% set totalBiaya = 0 %}
        {% for data in transaksi %}
        <tr>        
            <td class="pt-2" valign="top" style="padding-bottom: 0.3cm;border-bottom: 1px solid #000" rowspan="3" width="10%">{{ loop.index }}</td>
            <td class="pt-2" width="30%">{{ data.header.NAMAIMPORTIR }}</td>
            <td class="pt-2 number text-center" width="15%">{{ data.header.JUMLAH_KONTAINER }}</td>
            <td class="pt-2 number" width="15%">{{ data.header.NOMINAL|number_format(0,".",",") }}</td>
            <td class="pt-2 number" width="15%">{{ data.totalbiaya|number_format(0,".",",") }}</td>
            <td class="pt-2 number" width="15%">{{ data.balance|number_format(0,".",",") }}</td>
        </tr>
        <tr>                    
            <td width="30%">{{ data.header.NO_BL }}</td>
            <td width="15%">{{ data.header.NAMAKEMASAN }}</td>
            <td width="45%" colspan="3">
                <table width="100%">
                    <tr>
                        <td class="number text-center" width="20%">{{ data.header.JUMLAH_KEMASAN }}</td>
                        <td width="20%">{{ data.header.TGL_LAPORAN ? data.header.TGL_LAPORAN|date("d-m-Y") : "" }}</td>
                        <td width="20%">{{ data.header.TGL_BC_16 ? data.header.TGL_BC_16|date("d-m-Y") : ""}}</td>
                        <td width="20%">{{ data.header.BC_16 }}</td>
                        <td width="20%">{{ data.header.KLOTER }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>                 
            <td valign="top" width="45%" colspan="2" style="padding-bottom: 0.5cm;border-bottom: 1px solid #000">
                <table width="100%">
                    {% for kont in data.kontainer %}
                    <tr>   
                        <td width="70%">{{ kont.NOMOR_KONTAINER }}</td>
                        <td width="30%">{{ kont.URAIAN }}</td>
                    </tr>
                    {% endfor %}
                </table>
            </td>
            <td valign="top" width="30%" colspan="2" style="padding-bottom: 0.5cm;border-bottom: 1px solid #000">
                <table width="100%">
                    {% for by in data.biaya %}
                    <tr>   
                        <td width="70%">{{ by.URAIAN }}</td>
                        <td class="number" width="30%">{{ by.NOMINAL|number_format(0,".",",") }}</td>
                        {% set totalBiaya = totalBiaya + by.NOMINAL %}
                    </tr>
                    {% endfor %}
                </table>
            </td>
            <td valign="top" width="15%" style="padding-bottom: 0.5cm;border-bottom: 1px solid #000"></td>
        </tr>
        {% endfor %}
        <tr>
            <td class="number" colspan="4">
                <strong>Grand Total Biaya</strong>
            </td>
            <td class="number">
                {{ totalBiaya|number_format(0,".",".")}}
            </td>
        </tr>
    </tbody>
</table>


