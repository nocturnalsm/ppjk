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
<h5>LAPORAN REALISASI (Periode Tanggal Realisasi)</h5><br>
<span>Kantor : {{ namakantor }}</span><br>
{% if namaimportir is defined %}
<span>Importir: {{ namaimportir }}</span><br>
{% endif %}
<span>Periode Laporan: Dari {{ dari }} s/d {{ sampai }}</span><br>
{% if dari2 is defined or sampai2 is defined %}
<span>Periode Realisasi: 
    {% if dari2 is defined %}
    Dari {{ dari2 }} 
    {% endif %}
    {% if sampai2 is defined %}
    s/d {{ sampai2 }}
    {% endif %}
    </span><br>
{% endif %}
<span>Kategori Biaya: {{ kategoribiaya }}</span><br>
<table class="table-report" width="100%" cellspacing="0" cellpadding="2">
    <thead>
        <tr>        
            <td rowspan="2" class="border-left border-bottom" width="5%">No</td>
            <td class="border-inner" width="10%">NO.BL</td>
            <td class="border-inner" width="10%">JML KMS</td>
            <td class="border-inner" width="10%">JML KONT</td>
            <td class="border-inner" width="15%">TGL LAPORAN</td>
            <td class="border-inner" width="10%">KLOTER</td>
            <td class="border-inner" width="10%">TOTAL BIAYA</td>
            <td class="border-inner" width="10%">NOPEN</td>
            <td class="border-inner" width="10%">TGL NOPEN</td>
            <td class="border-inner border-right" width="10%">TGL REALISASI</td>
        </tr>
        <tr>        
            <td class="border-inner border-bottom" width="10%">NO.KONT</td>
            <td class="border-inner border-bottom" width="10%">UK.KONT</td>
            <td class="border-inner border-bottom" width="10%"></td>
            <td class="border-inner border-bottom" colspan="2" width="25%">JNS.BIAYA</td>
            <td class="border-inner border-bottom" width="10%">NOMINAL</td>
            <td class="border-inner border-bottom border-right text-center" colspan="3" width="30%">IMPORTIR</td>
        </tr>
    </thead>
    <tbody>
        {% set totalBiaya = 0 %}
        {% for data in transaksi %}
        <tr>        
            <td class="pt-2" valign="top" style="padding-bottom: 0.3cm;border-bottom: 1px solid #000" rowspan="2" width="5%">{{ loop.index }}</td>
            <td class="pt-2" valign="top" width="10%">{{ data.header.NO_BL }}</td>
            <td class="pt-2 number text-center" valign="top" width="10%">{{ data.header.JUMLAH_KEMASAN|number_format(0,".",",") }}</td>
            <td class="pt-2 number text-center" valign="top" width="10%">{{ data.header.JUMLAH_KONTAINER }}</td>
            <td class="pt-2" valign="top"  width="15%">{{ data.header.TGL_LAPORAN ? data.header.TGL_LAPORAN|date("d-m-Y") : "" }}</td>
            <td class="pt-2 number text-center" valign="top"  width="10%">{{ data.header.KLOTER }}</td>
            <td class="pt-2 number" valign="top" width="10%">{{ data.totalbiaya|number_format(0,".",",") }}</td>
            <td class="pt-2 number text-center" valign="top" width="10%">{{ data.header.NOPEN_BC_28 }}</td>
            <td class="pt-2 number text-center" valign="top" width="10%">{{ data.header.TGL_BC_16 ? data.header.TGL_BC_16|date("d-m-Y") : "" }}</td>
            <td class="pt-2 number text-center" valign="top" width="10%">{{ data.header.TGL_REALISASI ? data.header.TGL_REALISASI|date("d-m-Y") : "" }}</td>
        </tr>
        <tr>                 
            <td valign="top" width="35%" colspan="3" style="padding-bottom: 0.5cm;border-bottom: 1px solid #000">
                <table width="100%">
                    {% for kont in data.kontainer %}
                    <tr>   
                        <td width="40%">{{ kont.NOMOR_KONTAINER }}</td>
                        <td width="60%">{{ kont.URAIAN }}</td>
                    </tr>
                    {% endfor %}
                </table>
            </td>
            <td valign="top" width="35%" colspan="3" style="padding-bottom: 0.5cm;border-bottom: 1px solid #000">
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
            <td valign="top" class="text-center" colspan="3" style="padding-bottom: 0.5cm;border-bottom: 1px solid #000" width="25%">{{ data.header.NAMAIMPORTIR }}</td>
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

