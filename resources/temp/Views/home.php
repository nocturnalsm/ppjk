{% extends 'base.html.twig' %}
{% block body %}
<style>
    .list-group-item-action:hover {
        background-color: #c4e2ff;
    }
</style>
<div class="row">
    <div class="col-md-4 col-sm-12 mb-6">
        <div class="card card-sm">
            <div class="card-header">
                <strong>Transaksi</strong>
            </div>
            <div class="card-body">               
                <ul class="list-group list-group-flush">
                    {% if userlevel < 1 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi">Perekaman Schedule</a>
                    {% endif %}
                    {% if userlevel <= 1 or userlevel == 3 or userlevel == 6 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/perekamanvo">Perekaman VO</a>
                    {%endif%}
                    {% if userlevel <= 1 or userlevel == 2 or userlevel == 6 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/perekamando">Perekaman Dokumen</a>
                    {%endif%}
                    {% if userlevel <= 1 or userlevel == 2 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/perekamanbarang">Perekaman Barang</a>
                    {%endif%}
                    {% if userlevel < 1 or userlevel == 2 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/browsesptnp">Browse SPTNP</a>
                    {%endif%}
                    {% if userlevel <= 1 or userlevel == 2 or userlevel == 9 %}
                    {% if userlevel != 1 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/userquota">Perekaman Quota</a>
                    {% endif %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/browsesaldoquota">Browse Saldo Quota</a>
                    {%endif%}
                    {% if userlevel <= 1 or userlevel == 6 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/browse">Browse Schedule</a>
                    {% endif %}
                    {% if userlevel != 6 and userlevel != 8 and userlevel != 9 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/search">Cari Berdasarkan No. BL, Jml Kemasan, Customer, Nopen, No.Aju</a>
                    {% if userlevel != 1 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/searchkontainer">Cari Berdasarkan No Kontainer</a>
                    {% endif %}
                    {% endif %}
                    {% if userlevel < 4 or userlevel == 8 %}
                    {% if userlevel < 4 and userlevel != 1 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/searchproduk">Cari Produk</a>
                    <a class="list-group-item list-group-item-action" href="./transaksi/konversibarang">Konversi Barang</a>
                    <a class="list-group-item list-group-item-action" href="./transaksi/deliveryorder">Perekaman Delivery Order</a>
                    {% endif %}
                    {% endif %}
                    {% if userlevel == 4 or userlevel == 6 or userlevel == 1 %}                    
                    {% if userlevel == 4 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/transaksibayar">Transaksi Pembayaran</a>
                    {% endif %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/perekamanbayar">Browse Pembayaran</a>
                    {% if userlevel == 4 or userlevel == 1 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/kartuhutang">Kartu Hutang</a>
                    {% endif %}
                    {% endif %}
                    {% if userlevel <= 1 or userlevel == 7 %}
                    <a class="list-group-item list-group-item-action" href="./transaksi/browsestokproduk">Browse Stok per Produk</a>
                    <a class="list-group-item list-group-item-action" href="./transaksi/browsestokbarang">Browse Stok per Barang</a>
                    {% endif %}
                </ul>
            </div>
        </div>
    </div>
    {% if userlevel < 1 or userlevel == 4 %}
    <div class="col-md-4 col-sm-12 mb-6">
        <div class="card card-sm">
            <div class="card-header">
                <strong>Menu Master</strong>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    {% if userlevel < 1 %}
                    <a class="list-group-item list-group-item-action" href="./master/produk">Produk</a>
                    <a class="list-group-item list-group-item-action" href="./master/satuan">Satuan</a>
                    <a class="list-group-item list-group-item-action" href="./master/importir">Importir</a>                    
                    <a class="list-group-item list-group-item-action" href="./master/jenisbarang">Referensi Jenis Barang</a>
                    <a class="list-group-item list-group-item-action" href="./master/pelmuat">Referensi Pelabuhan Muat</a>
                    <a class="list-group-item list-group-item-action" href="./master/jeniskemasan">Referensi Jenis Kemasan</a>
                    <a class="list-group-item list-group-item-action" href="./master/jenisdokumen">Referensi Jenis Dokumen</a>
                    <a class="list-group-item list-group-item-action" href="./master/kantor">Referensi Kode Kantor</a>
                    <a class="list-group-item list-group-item-action" href="./master/ratedpp">Referensi Rate DPP</a>
                    {% endif %}
                    <a class="list-group-item list-group-item-action" href="./master/bank">Referensi Bank</a>
                    <a class="list-group-item list-group-item-action" href="./master/rekening">Referensi Rekening</a>
                    <a class="list-group-item list-group-item-action" href="./master/penerima">Referensi Penerima</a>
                    <a class="list-group-item list-group-item-action" href="./master/pembeli">Referensi Pembeli</a>
                </ul>
            </div>
        </div>
    </div>
    {% endif %}
</div>
{% endblock %}

