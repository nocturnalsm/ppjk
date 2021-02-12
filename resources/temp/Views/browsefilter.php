{% for dt in data %}
<tr>
    <td>{{ dt.NO_BL }}</td>
    <td>{{ dt.JUMLAH_KEMASAN }}</td>
    <td>{{ dt.NAMA }}</td>
    <td>{{ dt.TGLTIBA }}</td>
    <td>{{ dt.TGLBONGKAR }}</td>
    <td>{{ dt.TGLKELUAR }}</td>
    <td>{{ dt.AJU1 }}</td>
    <td>{{ dt.NOPEN1 }}</td>
    <td>{{ dt.TGLNOPEN1 }}</td>
    <td>{{ dt.AJU2 }}</td>
    <td>{{ dt.NOPEN2 }}</td>
    <td>{{ dt.TGLNOPEN2 }}</td>
    <td>{{ dt.NO_PO }}</td>
</tr>
{% endfor %}