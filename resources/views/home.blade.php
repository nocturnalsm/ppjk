@extends('layouts.body')

@section('content')
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
                    @can('schedule.transaksi')
                    <a class="list-group-item list-group-item-action" href="./transaksi">Perekaman Schedule</a>
                    @endcan
                    @can('vo.browse')
                    <a class="list-group-item list-group-item-action" href="./transaksi/perekamanvo">Perekaman VO</a>
                    @endif
                    @can('dokumen.browse')
                    <a class="list-group-item list-group-item-action" href="./transaksi/perekamando">Perekaman Dokumen</a>
                    @endcan
                    @can('barang.browse')
                    <a class="list-group-item list-group-item-action" href="./transaksi/perekamanbarang">Perekaman Barang</a>
                    @endcan
                    @can('sptnp.transaksi')
                    <a class="list-group-item list-group-item-action" href="./transaksi/usersptnp">Perekaman SPTNP</a>
                    @endcan
                    @can('sptnp.browse')
                    <a class="list-group-item list-group-item-action" href="./transaksi/browsesptnp">Browse SPTNP</a>
                    @endcan
                    @can('sptnp.keberatan')
                    <a class="list-group-item list-group-item-action" href="./transaksi/keberatan">Browse Keberatan</a>
                    @endcan
                    @can('sptnp.banding')
                    <a class="list-group-item list-group-item-action" href="./transaksi/banding">Browse Banding</a>
                    @endcan
                    @can('quota.transaksi')
                    <a class="list-group-item list-group-item-action" href="./transaksi/userquota">Perekaman Quota</a>
                    @endcan
                    @can('quota.browse')
                    <a class="list-group-item list-group-item-action" href="./transaksi/browsesaldoquota">Browse Saldo Quota</a>
                    @endcan
                    @can('schedule.browse')
                    <a class="list-group-item list-group-item-action" href="./transaksi/browse">Browse Schedule</a>
                    @endcan
                    @can('schedule.cari')
                    <a class="list-group-item list-group-item-action" href="./transaksi/search">Cari Berdasarkan No. BL, Jml Kemasan, Customer, Nopen, No.Aju</a>
                    @endcan
                    @can('schedule.carikontainer')
                    <a class="list-group-item list-group-item-action" href="./transaksi/searchkontainer">Cari Berdasarkan No Kontainer</a>
                    @endcan
                    @can('cari_produk')
                    <a class="list-group-item list-group-item-action" href="./transaksi/searchproduk">Cari Produk</a>
                    @endcan
                    @can('profil_harga')
                    <a class="list-group-item list-group-item-action" href="./transaksi/profilharga">Profil Harga</a>
                    @endcan
                    @can('konversi.browse')
                    <a class="list-group-item list-group-item-action" href="./transaksi/konversibarang">Konversi Barang</a>
                    @endcan
                    @can('deliveryorder')
                    <a class="list-group-item list-group-item-action" href="./transaksi/deliveryorder">Perekaman Delivery Order</a>
                    @endcan
                    @can('pembayaran.transaksi')
                    <a class="list-group-item list-group-item-action" href="./transaksi/transaksibayar">Transaksi Pembayaran</a>
                    @endcan
                    @can('pembayaran.browse')
                    <a class="list-group-item list-group-item-action" href="./transaksi/perekamanbayar">Browse Pembayaran</a>
                    @endcan
                    @can('kartu_hutang')
                    <a class="list-group-item list-group-item-action" href="./transaksi/kartuhutang">Kartu Hutang</a>
                    @endcan
                    @can('stokperproduk')
                    <a class="list-group-item list-group-item-action" href="./transaksi/browsestokproduk">Browse Stok per Produk</a>
                    @endcan
                    @can('stokperbarang')
                    <a class="list-group-item list-group-item-action" href="./transaksi/browsestokbarang">Browse Stok per Barang</a>
                    @endcan
                    @can('gudang.transaksi')
                    <a class="list-group-item list-group-item-action" href="./gudang">Perekaman Data Gudang</a>
                    @endcan
                    @can('gudang.kontainermasuk')
                    <a class="list-group-item list-group-item-action" href="./gudang/kontainermasuk">Kontainer Masuk</a>
                    @endcan
                    @can('gudang.bongkar')
                    <a class="list-group-item list-group-item-action" href="./gudang/perekamanbongkar">Perekaman Bongkar</a>
                    @endcan
                </ul>
            </div>
        </div>
    </div>
    @if(auth()->user()->can('master.*'))
    <div class="col-md-4 col-sm-12 mb-6">
        <div class="card card-sm">
            <div class="card-header">
                <strong>Menu Master</strong>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @can('master.produk.browse')
                    <a class="list-group-item list-group-item-action" href="./master/produk">Produk</a>
                    @endcan
                    @can('master.satuan.browse')
                    <a class="list-group-item list-group-item-action" href="./master/satuan">Satuan</a>
                    @endcan
                    @can('master.importir.browse')
                    <a class="list-group-item list-group-item-action" href="./master/importir">Importir</a>
                    @endcan
                    @can('master.pemasok.browse')
                    <a class="list-group-item list-group-item-action" href="./master/pemasok">Pemasok</a>
                    @endcan
                    @can('master.jenisbarang.browse')
                    <a class="list-group-item list-group-item-action" href="./master/jenisbarang">Referensi Jenis Barang</a>
                    @endcan
                    @can('master.pelmuat.browse')
                    <a class="list-group-item list-group-item-action" href="./master/pelmuat">Referensi Pelabuhan Muat</a>
                    @endcan
                    @can('master.jeniskemasan.browse')
                    <a class="list-group-item list-group-item-action" href="./master/jeniskemasan">Referensi Jenis Kemasan</a>
                    @endcan
                    @can('master.jenisdokumen.browse')
                    <a class="list-group-item list-group-item-action" href="./master/jenisdokumen">Referensi Jenis Dokumen</a>
                    @endcan
                    @can('master.kantor.browse')
                    <a class="list-group-item list-group-item-action" href="./master/kantor">Referensi Kode Kantor</a>
                    @endcan
                    @can('master.dpp.browse')
                    <a class="list-group-item list-group-item-action" href="./master/ratedpp">Referensi Rate DPP</a>
                    @endcan
                    @can('master.bank.browse')
                    <a class="list-group-item list-group-item-action" href="./master/bank">Referensi Bank</a>
                    @endcan
                    @can('master.rekening.browse')
                    <a class="list-group-item list-group-item-action" href="./master/rekening">Referensi Rekening</a>
                    @endcan
                    @can('master.penerima.browse')
                    <a class="list-group-item list-group-item-action" href="./master/penerima">Referensi Penerima</a>
                    @endcan
                    @can('master.pembeli.browse')
                    <a class="list-group-item list-group-item-action" href="./master/pembeli">Referensi Pembeli</a>
                    @endcan
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
