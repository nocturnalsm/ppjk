<style>
    h2 {
        font-size: 12pt;
    }
    table {
        font-size: 10pt;
    }
    .pagebreak {
        page-break-after: always;
    }
</style>
{% for bia in biaya %}
{% if bia.total > 0 or loop.index == 1 %}
{% if loop.index > 1 %}
<div class="pagebreak"></div>
{% endif %}
<h2>INVOICE</h2>
<hr>
<table width="100%">
    <tr>
        <td rowspan="4" valign="top" width="50%"><strong>To: {{ header.NAMAIMPORTIR }}</strong></td>
    </tr>
    <tr>        
        <td width="20%"></td>
        <td width="10%">Tanggal</td>
        <td width="2%">:</td>
        <td width="18%" style="text-align: right">{{ header.TGL_LAPORAN|date("d-m-Y") }}</td>
    </tr>
    <tr>        
        <td width="20%"></td>
        <td width="10%">No. BC 28</td>
        <td width="2%">:</td>
        <td width="18%" style="text-align: right">{{ header.NOPEN_BC_28 }}</td>
    </tr>
    <tr>        
        <td width="20%"></td>
        <td width="10%">Tgl BC 28</td>
        <td width="2%">:</td>
        <td width="18%" style="text-align: right">{{ header.TGL_BC_28|date("d-m-Y") }}</td>
    </tr>
</table>
<table style="margin-top: 0.5cm" width="100%">
    <tr style="height:0.8cm;">
        <td style="text-align: center; border: 2px solid #000" width="50%">Description</td>
        <td style="text-align: center; border: 2px solid #000" width="50%">IDR</td>
    </tr>
</table>
<table width="100%" style="margin-top: 0.5cm">
    <tr>
        <td width="20%">No. BL</td>
        <td width="80%">: {{ header.NO_BL }}</td>
    </tr>
    <tr>
        <td width="20%">PARTAI</td>
        <td width="80%">: {{ header.JUMLAH_KEMASAN }} {{ header.NAMAKEMASAN }}</td>
    </tr>
    <tr>
        <td width="20%">QTY</td>
        <td width="80%">: {{ header.JUMLAH_KONTAINER }} x {{ kontainer[0].URAIAN }}</td>
    </tr>
    <tr>
        <td width="20%" valign="top">NO. KONTAINER</td>
        <td width="80%">:
            {% for kont in kontainer %}
            {{ kont.NOMOR_KONTAINER }}<br>&nbsp;
            {% endfor %}
        </td>
    </tr>
</table>
<table style="margin-top: 0.5cm" width="100%">
    <tr>
        <td style="padding-bottom:0.5cm" width="50%">
        <strong>DETAIL BIAYA {% if bia.total > 0 %}{{ bia.value }}{% else %} : Tidak ada data{% endif %}</strong></td>
        <td colspan="2" style="padding-bottom:0.5cm" width="50%"></td>
    </tr>
    {% for item in bia.data %}
    <tr>
        <td width="50%">{{ item.URAIAN }}</td>
        <td colspan="2" style="text-align: right" width="50%">{{ item.NOMINAL|number_format(0,".",",") }}</td>
    </tr>
    {% endfor %}
    <tr>
        <td colspan="3"><hr></td>
    </tr>
    <tr>
        <td width="50%"></td>
        <td width="30%"><strong>TOTAL BIAYA</strong></td>
        <td style="text-align: right" width="20%">{{ bia.total|number_format(0,".",",") }}</td>
    </tr>
</table>
{% endif %}
{% endfor %}
<script>
    window.print();
</script>

