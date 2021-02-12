@extends('layouts.base')
@section('main')
<style>
    .error {display:none;font-size: 0.75rem;color: red};
</style>
<div class="modal fade" id="modalpengeluaran" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <form id="form" act="">
                    <input type="hidden" name="idxpengeluaran" id="idxpengeluaran">
                    <input type="hidden" name="idpengeluaran" id="idpengeluaran">
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="no_do">Tanggal Muat</label>
                        <div class="col-md-9">
                            <input type="text" id="tgl_muat" name="tglmuat" class="datepicker form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="no_do">No.SJ</label>
                        <div class="col-md-9">
                            <input type="text" id="no_sj" name="nosj" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="no_do">No. Pol</label>
                        <div class="col-md-9">
                            <input type="text" id="no_pol" name="nopol" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="penerima">Driver</label>
                        <div class="col-md-9">
                            <input type="text" id="driver" name="driver" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="jmlkemasan">Jumlah Kemasan Muat</label>
                        <div class="col-md-9">
                            <input type="text" id="jmlkemasan" name="jmlkemasan" class="text-right number form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="remarks">Remarks</label>
                        <div class="col-md-9">
                            <textarea id="remarks" name="remarks" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <a id="savepengeluaran" class="btn btn-primary">Simpan</a>
                <a class="btn btn-danger" data-dismiss="modal">Batal</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <form id="form" act="">
                    <input type="hidden" name="idxdetail" id="idxdetail">
                    <input type="hidden" name="iddetail" id="iddetail">
                    <input type="hidden" name="deletedetail">
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="noinv">Kode Barang</label>
                        <div class="col-md-9">
                            <input type="text" id="kodebarang" name="kodebarang" class="form-control form-control-sm validate">
                            <input type="hidden" id="kodebarang_id" name="kodebarang_id">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3">Produk</label>
                        <div class="col-md-9 pt-2">
                            <span id="formproduk"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3">Harga Jual</label>
                        <div class="col-md-9 pt-2">
                            <span id="formhargajual"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="noppu">Tgl Keluar</label>
                        <div class="col-md-9">
                            <input type="text" id="tglkeluar" name="tglkeluar" class="datepicker form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="kurs">Jml Kemasan Keluar</label>
                        <div class="col-md-9">
                            <input type="text" id="jmlkemasankeluar" name="jmlkemasankeluar" class="number form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="nominal">Jml Satuan Harga Keluar</label>
                        <div class="col-md-9">
                            <input type="text" id="jmlsathargakeluar" name="jmlsathargakeluar" class="number form-control form-control-sm validate">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <a id="savedetail" class="btn btn-primary">Simpan</a>
                <a class="btn btn-danger" data-dismiss="modal">Batal</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="card col-md-12 p-0">
        <div class="card-header font-weight-bold">
            <div class="row">
                <div class="col-md-4 py-0 pl-4 mt-1">
                    Form Perekaman Delivery Order
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                    @if (isset($header))
                    <a id="deletetransaksi" class="btn btn-warning btn-sm m-0" data-dismiss="modal">Hapus</a>
                    @endif
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input type="hidden" value="{{ $header->ID }}" id="idtransaksi" name="idtransaksi">
            <div class="row px-2">
                <div class="col-md-12 pt-0 col-sm-12">
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <div class="form-row px-2 pb-1">
                                    <label class="col-md-2 col-form-label form-control-sm">No. Delivery Order</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="form-control form-control-sm" name="nodo" value="{{ $header->NO_DO }}" id="nodo">
                                    </div>
                                    <div class="col-md-1"></div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Delivery Order</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tgldo" value="{{ $header->TGL_DO }}" id="tgldo">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-1">
                                    <label class="col-md-2 col-form-label form-control-sm">No. Inv Jual</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control form-control-sm" name="noinvjual" value="{{ $header->NO_INV_JUAL }}" id="noinvjual">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Inv Jual</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglinvjual" value="{{ $header->TGL_INV_JUAL }}" id="tglinvjual">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-1">
                                    <label class="col-md-2 col-form-label form-control-sm">Total Jml Kemasan Keluar</label>
                                    <div class="col-md-2">
                                        <input disabled type="text" class="number2 form-control form-control-sm" name="totjmlkemasankeluar" id="totjmlkemasankeluar" value="{{ $header->TOTJMLKMSKELUAR }}">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Total Jml Satuan Harga Keluar</label>
                                    <div class="col-md-2">
                                        <input disabled type="text" class="number2 form-control form-control-sm" name="totjmlsathargakeluar" id="totjmlsathargakeluar" value="{{ $header->TOTJMLSATHARGAKELUAR }}">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-2 col-form-label form-control-sm">Pembeli</label>
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" id="pembeli" name="pembeli">
                                            <option @if($header->PEMBELI == '' || !$header->PEMBELI) selected @endif value=""></option>
                                            @foreach($datapembeli as $pembeli)
                                            <option @if($header->PEMBELI == $pembeli->ID) selected @endif value="{{ $pembeli->ID }}">{{ $pembeli->KODE }} - {{ $pembeli->NAMA }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-1">
                                    <label class="col-md-2 col-form-label form-control-sm">Total Penjualan</label>
                                    <div class="col-md-2">
                                        <input type="text" class="number2 form-control form-control-sm" name="total" id="total" value="{{ $header->TOTAL }}">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Total Kemasan Muat</label>
                                    <div class="col-md-2">
                                        <input disabled type="text" class="number form-control form-control-sm" name="totalmuat" id="totalmuat" value="{{ $header->TOTALMUAT }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <div class="row px-2">
                <div class="col-md-12">
                    <div class="row mb-2">
                        <div class="card col-md-6 p-0">
                            <div class="card-body p-3">
                                <div class="form-row">
                                    <div class="col primary-color text-white py-2 px-4">
                                        Detail DO
                                    </div>
                                    <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                                        <a href="#modaldetail" data-toggle="modal" class="text-white" id="adddetail">Tambah Detail</a>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mt-2">
                                        <table width="100%" id="griddetail" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Kode Barang</th>
                                                    <th>Kode Produk</th>
                                                    <th>Tgl KelUar</th>
                                                    <th>Jml Kmsn Keluar</th>
                                                    <th>Jml Sat Harga Keluar</th>
                                                    <th>Harga Jual</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card col-md-6 p-0">
                            <div class="card-body p-3">
                                <div class="form-row">
                                    <div class="col primary-color text-white py-2 px-4">
                                        Detail Pengeluaran
                                    </div>
                                    <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                                        <a href="#modalpengeluaran" data-toggle="modal" class="text-white" id="addpengeluaran">Tambah Pengeluaran</a>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mt-2">
                                         <table width="100%" id="gridpengeluaran" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Tgl Muat</th>
                                                    <th>No SJ</th>
                                                    <th>No. Pol</th>
                                                    <th>Driver</th>
                                                    <th>Jml Kms Muat</th>
                                                    <th>Remarks</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('stylesheets_end')
    <link href="{{ asset('jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
@endpush
@push('scripts_end')
<script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<script>

var detail = @json($detail);
datadetail = JSON.parse(detail);
var pengeluaran = @json($pengeluaran);
datapengeluaran = JSON.parse(pengeluaran);

$(function(){

Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
	places = !isNaN(places = Math.abs(places)) ? places : 2;
	symbol = symbol !== undefined ? symbol : "";
	thousand = thousand || ",";
	decimal = decimal || ".";
	var number = this,
			negative = number < 0 ? "-" : "",
			i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
};
$("body").on("change","#kodebarang", function(){
    $.ajax({
        method: "GET",
        url: "/transaksi/searchkodebarang?kode=" + $(this).val(),
        success: function(response){
            $("#kodebarang_id").val("");
            if (typeof response.error == 'undefined'){
                $("#formproduk").html(response.kode);
                $("#formhargajual").html(response.HARGAJUAL);
                $("#kodebarang_id").val(response.ID);
            }
            else {
                $("#modal .modal-body").html(response.error);
                $("#formproduk").html("");
                $("#formhargajual").html("");
                $("#kodebarang_id").val("");
                $("#modal").modal("show");
                setTimeout(function(){
                    $("#modal").modal("hide");
                }, 5000);
            }
        }
    })
})
$('#modaldetail').on('shown.bs.modal', function () {
    $('#kodebarang').focus();
})
$("#savedetail").on("click", function(){
    if ($("#kodebarang").val().trim() == ""){
        $("#modal .modal-body").html("Kode Barang harus diisi");
        $("#modal").modal("show");
        setTimeout(function(){
            $("#modal").modal("hide");
        }, 5000);
        return false;
    }
    if ($("#kodebarang_id").val().trim() == ""){
        $("#modal .modal-body").html("Kode Barang tidak ada");
        $("#modal").modal("show");
        setTimeout(function(){
            $("#modal").modal("hide");
        }, 5000);
        return false;
    }
    $(this).prop("disabled", true);
    var kodebarang_id = $("#kodebarang_id").val();
    var tglkeluar = $("#tglkeluar").val();
    var kodebarang = $("#kodebarang").val();
    var produk = $("#formproduk").html();
    var hargajual = $("#formhargajual").html();
    var jmlkemasankeluar = $("#jmlkemasankeluar").inputmask('unmaskedvalue');
    var jmlsathargakeluar = $("#jmlsathargakeluar").inputmask('unmaskedvalue');
    var act = $("#form").attr("act");

    if (act == "add"){
        tabel.row.add({KODEBARANG_ID: kodebarang_id, KODEBARANG: kodebarang, TGL_KELUAR: tglkeluar, kode: produk, HARGAJUAL: hargajual, JMLKMSKELUAR: jmlkemasankeluar, JMLSATHARGAKELUAR: jmlsathargakeluar}).draw();
        $("#formproduk").html("");
        $("#formhargajual").html("");
        $("#kodebarang").val("");
        $("#kodebarang_id").val("");
        $("#tglkeluar").val("");
        $("#jmlkemasankeluar").val("");
        $("#jmlsathargakeluar").val("");
        $("#kodebarang").focus();
    }
    else if (act == "edit"){
        var id = $("#iddetail").val();
        var idx = $("#idxdetail").val();
        tabel.row(idx).data({ID: id, KODEBARANG: kodebarang, KODEBARANG_ID: kodebarang_id, TGL_KELUAR: tglkeluar, kode: produk, HARGAJUAL: hargajual, JMLKMSKELUAR: jmlkemasankeluar, JMLSATHARGAKELUAR: jmlsathargakeluar}).draw();
        $("#modaldetail").modal("hide");
    }
    $(this).prop("disabled", false);
    var rows = tabel.rows().data();
    var totalkemasan = 0;
    var totalsatharga = 0;
    $(rows).each(function(index,elem){
        totalkemasan = totalkemasan + parseFloat(elem.JMLKMSKELUAR);
        totalsatharga = totalsatharga + parseFloat(elem.JMLSATHARGAKELUAR);
    })
    $("#totjmlkemasankeluar").val(totalkemasan);
    $("#totjmlsathargakeluar").val(totalsatharga);
});
$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
$(".number").inputmask("numeric", {
    radixPoint: ".",
    groupSeparator: ",",
    digits: 2,
    autoGroup: true,
    rightAlign: false,
    removeMaskOnSubmit: true,
});
$(".number2").inputmask("numeric", {
    radixPoint: ".",
    groupSeparator: ",",
    digits: 2,
    autoGroup: true,
    rightAlign: true,
    removeMaskOnSubmit: true,
});
var tabel = $("#griddetail").DataTable({
    processing: false,
    serverSide: false,
    data: datadetail,
    dom: "t",
    rowCallback: function(row, data)
    {
        $('td:eq(3)', row).html(parseFloat(data.JMLKMSKELUAR).formatMoney(2,"",",","."));
        $('td:eq(4)', row).html(parseFloat(data.JMLSATHARGAKELUAR).formatMoney(2,"",",","."));
        $('td:eq(5)', row).html(parseFloat(data.HARGAJUAL).formatMoney(0,"",",","."));
        $('td:eq(6)', row).html('<a href="#modaldetail" class="edit" data-toggle="modal" id="' + data.ID +
                                '"><i class="fa fa-edit"></i></a>' +
                                '&nbsp;&nbsp;<a class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                );
    },
    select: 'single',     // enable single row selection
    responsive: false,     // enable responsiveness,
    rowId: 0,
    columns: [{
        target: 0,
        data: "KODEBARANG"
      },
      { target: 1,
        data: "kode"
      },
      { target: 2,
        data: "TGL_KELUAR"
      },
      { target: 3,
        data: "JMLKMSKELUAR"
      },
      { target: 4,
        data: "JMLSATHARGAKELUAR"
      },
      { target: 5,
        data: "HARGAJUAL"
      },
      { target: 6,
        data: null
      }
     ],
})
$("#adddetail").on("click", function(){
    $("#formproduk").html("");
    $("#formhargajual").html("");
    $("#kodebarang").val("");
    $("#kodebarang_id").val("");
    $("#tglkeluar").val("");
    $("#jmlkemasankeluar").val("");
    $("#jmlsathargakeluar").val("");
    $("#modaldetail .modal-title").html("Tambah ");
    $("#form").attr("act","add");
})
$("body").on("click", ".edit", function(){
    var row = $(this).closest("tr");
    var index = tabel.row(row).index();
    var row = tabel.rows(index).data();
    $("#formproduk").html(row[0].kode);
    $("#formhargajual").html(row[0].HARGAJUAL);
    $("#kodebarang").val(row[0].KODEBARANG);
    $("#kodebarang_id").val(row[0].KODEBARANG_ID);
    $("#tglkeluar").val(row[0].TGL_KELUAR);
    $("#jmlkemasankeluar").val(row[0].JMLKMSKELUAR);
    $("#jmlsathargakeluar").val(row[0].JMLSATHARGAKELUAR);
    $("#idxdetail").val(index);
    $("#iddetail").val(row[0].ID);
    $("#modaldetail .modal-title").html("Edit ");
    $("#form").attr("act","edit");
})
$("body").on("click", ".del", function(){
    var row = $(this).closest("tr");
    var id = tabel.row(row).data().ID;
    if (typeof id != 'undefined'){
        $("input[name='deletedetail'").val($("input[name='deletedetail'").val() + id + ";");
    }
    var index = tabel.row(row).remove().draw();
})

$("#btnsimpan").on("click", function(){

    //if (validate()){
    var detail = [];
    var rows = tabel.rows().data();                                                                                                                                                                                                                                                                                                                                                                                                                          ;
    $(rows).each(function(index,elem){
        detail.push(elem);
    })
    var pengeluaran = [];
    var rows = tabelpengeluaran.rows().data();
    $(rows).each(function(index,elem){
        pengeluaran.push(elem);
    })

    $(this).prop("disabled", true);
    $(".loader").show()
    $.ajax({
        url: "/transaksi/crud",
        data: {_token: "{{ csrf_token() }}", type: "deliveryorder", header: $("#transaksi").serialize(), detail: detail, pengeluaran: pengeluaran },
        type: "POST",
        cache: false,
        success: function(msg) {
            if (typeof msg.error != 'undefined'){
                $("#modal .modal-body").html(msg.error);
                $("#modal").modal("show");
                setTimeout(function(){
                    $("#modal").modal("hide");
                }, 5000);
            }
            else {
                $("#modal .modal-body").html("Penyimpanan berhasil");
                $('#modal').on('hidden.bs.modal', function (e) {
                    window.location.reload();
                })
                $("#modal").modal("show");
                setTimeout(function(){
                    $("#modal").modal("hide");
                }, 5000);
            }
        },
        complete: function(){
            $("#btnsimpan").prop("disabled", false);
            $(".loader").hide();
        }
    })
})
@if ($header->ID)
$("a#deletetransaksi").on("click", function(){
    $("#modal .btn-ok").removeClass("d-none");
    $("#modal .btn-close").html("Batal");
    $("#modal .modal-body").html("Apakah Anda ingin menghapus data ini?");
    $("#modal .btn-ok").html("Ya").on("click", function(){

        $.ajax({
            url: "/transaksi/crud",
            data: {_token: "{{ csrf_token() }}", type: "deliveryorder", delete: "{{ $header->ID}}"},
            type: "POST",
            success: function(msg){
                $("#modal .btn-ok").addClass("d-none");
                if (typeof msg.error != 'undefined'){
                    $("#modal .modal-body").html(msg.error);
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 5000);
                }
                else {
                    $("#modal .modal-body").html("Data berhasil dihapus");
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 10000);
                    window.location.href = "/transaksi/deliveryorder";
                }
            }
        })
    });
    $("#modal").modal("show");
});
@endif

var tabelpengeluaran = $("#gridpengeluaran").DataTable({
    processing: false,
    processing: false,
    serverSide: false,
    dom: "t",
    data: datapengeluaran,
    "language":
        {
            "lengthMenu": "Menampilkan _MENU_ record per halaman",
            "info": "",
            "infoEmpty": "Data tidak ada",
            "infoFiltered": "",
            "search":         "Cari:",
            "zeroRecords":    "Tidak ada data yang sesuai pencarian",
            "paginate": {
                "next":       ">>",
                "previous":   "<<"
            }
        },
    order: [[1, 'asc']],
    columns:[{
                "target": 0,
                "data": "TGL_MUAT"
            },
            {
                "target": 1,
                "data": "NO_SJ"
            },
            {
                "target": 2,
                "data": "NO_POL"
            }, {
                "target": 3,
                "data": "DRIVER"
            },{
                "target": 4,
                "data": "JMLKEMASAN"
            },{
                "target": 5,
                "data": "REMARKS"
            },{
                "target": 6,
                "data": null
            }

    ],
    rowCallback: function(row, data){
        $('td:eq(4)', row).html(parseFloat(data.JMLKEMASAN).formatMoney(2,"",",","."));
        var opsi =  '<a title="Edit" href="#modalpengeluaran" class="editpengeluaran" data-toggle="modal" id="' + data.ID +
                    '"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;' +
                    '<a title="Hapus" class="delpengeluaran" id="' + data.ID + '"><i class="fa fa-trash"></i></a>';
        $('td:eq(6)', row).html(opsi);
    }
})
$('#modalpengeluaran').on('shown.bs.modal', function () {
    $("#savepengeluaran").removeClass("disabled");
    $('#tgl_muat').focus();
})
function calcTotal(){
    var rows = tabelpengeluaran.rows().data();
    var totalmuat = 0;
    $(rows).each(function(index,elem){
        totalmuat = totalmuat + parseFloat(elem.JMLKEMASAN);
    })
    $("#totalmuat").val(totalmuat);
    if (totalmuat != parseFloat($("#totjmlkemasankeluar").val())){
        $(".editdo").html('<a href="/transaksi/deliveryorder/' + $("#do_id").val() + '"><i class="fa fa-edit"></i></a>');
    }
    else {
        $(".editdo").html("");

    }
}
$("#savepengeluaran").on("click", function(){
    $(this).prop("disabled", true);
    var tglmuat = $("#tgl_muat").val();
    var no_sj = $("#no_sj").val();
    var no_pol = $("#no_pol").val();
    var driver = $("#driver").val();
    var remarks = $("#remarks").val();
    var jmlkemasan = $("#jmlkemasan").inputmask('unmaskedvalue');
    var act = $("#form").attr("act");
    if (act == "add"){
        tabelpengeluaran.row.add({NO_POL: no_pol, TGL_MUAT: tglmuat, NO_SJ: no_sj, DRIVER: driver, REMARKS: remarks, JMLKEMASAN: jmlkemasan}).draw();
        $("#no_pol").val("");
        $("#no_sj").val("");
        $("#tgl_muat").val("");
        $("#driver").val("");
        $("#remarks").val("");
        $("#jmlkemasan").val("");
        $("#tgl_muat").focus();
    }
    else if (act == "edit"){
        var id = $("#idpengeluaran").val();
        var idx = $("#idxpengeluaran").val();
        tabelpengeluaran.row(idx).data({ID: id, TGL_MUAT: tglmuat, NO_SJ: no_sj, NO_POL: no_pol, DRIVER: driver, REMARKS: remarks, JMLKEMASAN: jmlkemasan}).draw();
        $("#modalpengeluaran").modal("hide");
    }
    calcTotal();
    $(this).prop("disabled", false);
});
$("#addpengeluaran").on("click", function(){
    $("#no_pol").val("");
    $("#no_sj").val("");
    $("#tgl_muat").val("");
    $("#driver").val("");
    $("#remarks").val("");
    $("#jmlkemasan").val("");
    $("#modalpengeluaran .modal-title").html("Tambah Pengeluaran");
    $("#form").attr("act","add");
})
$("body").on("click", ".editpengeluaran", function(){
    var row = $(this).closest("tr");
    var index = tabelpengeluaran.row(row).index();
    var row = tabelpengeluaran.rows(index).data();
    $("#no_pol").val(row[0].NO_POL);
    $("#tgl_muat").val(row[0].TGL_MUAT);
    $("#no_sj").val(row[0].NO_SJ);
    $("#driver").val(row[0].DRIVER);
    $("#jmlkemasan").val(row[0].JMLKEMASAN);
    $("#remarks").val(row[0].REMARKS);
    $("#idxpengeluaran").val(index);
    $("#idpengeluaran").val(row[0].ID);
    $("#modalpengeluaran .modal-title").html("Edit Pengeluaran");
    $("#form").attr("act","edit");
})
$("body").on("click", ".delpengeluaran", function(){
    var row = $(this).closest("tr");
    var index = tabelpengeluaran.row(row).remove().draw();
    calcTotal();
})

})
</script>
@endpush
