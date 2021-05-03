@extends('layouts.base')
@section('main')
<style>
    .error {display:none;font-size: 0.75rem;color: red};
</style>
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                    <input type="hidden" name="formseri" id="formseri" value="">
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="kodebarang">Kode Barang</label>
                        <div class="col-md-9">
                            <input type="text" id="kodebarang" name="kodebarang" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="kodebarang">Uraian Barang</label>
                        <div class="col-md-9">
                            <textarea rows="5" id="uraian" name="kodebarang" class="form-control form-control-sm validate"></textarea>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Jumlah Kemasan</label>
                        <div class="col-md-9">
                            <input type="text" class="number form-control form-control-sm" name="jmlkemasan" id="jmlkemasan">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                      <label class="col-md-3 col-form-label">Jenis Kemasan</label>
                      <div class="col-md-4">
                          <select class="form-control form-control-sm" id="jeniskemasan" name="jeniskemasan">
                              <option value=""></option>
                              @foreach($jeniskemasan as $jenis)
                              <option @if($header->JENIS_KEMASAN == $jenis->JENISKEMASAN_ID)selected @endif value="{{ $jenis->JENISKEMASAN_ID }}">{{ $jenis->URAIAN }}</option>
                              @endforeach
                          </select>
                      </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Jumlah Satuan Harga</label>
                        <div class="col-md-9">
                            <input type="text" class="number form-control form-control-sm" name="jmlsatharga" id="jmlsatharga">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="satuan">Satuan</label>
                        <div class="col-md-9 pt-2">
                            <select class="form-control form-control-sm" id="satuan" name="satuan">
                                <option value=""></option>
                                @foreach($datasatuan as $satuan)
                                <option value="{{ $satuan->id }}">{{ $satuan->satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">CIF</label>
                        <div class="col-md-9">
                            <input type="text" class="number form-control form-control-sm" name="cif" id="cif">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Harga Satuan</label>
                        <div class="col-md-9">
                            <input readonly type="text" class="number form-control form-control-sm" name="harga" id="harga">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Rupiah</label>
                        <div class="col-md-9">
                            <input readonly type="text" class="number form-control form-control-sm" name="rupiah" id="rupiah">
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
<div class="modal fade" id="modalkontainer" tabindex="-1" role="dialog" aria-labelledby="modalkontainer" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <form id="formkontainer" act="">
                    <input type="hidden" name="idxdetailkontainer" id="idxdetailkontainer">
                    <input type="hidden" name="iddetailkontainer" id="iddetailkontainer">
                    <div class="mb-1">
                        <label for="nokontainer">No. Kontainer</label>
                        <input type="text" maxlength="15" id="nokontainer" name="nokontainer" class="form-control form-control-sm validate">
                    </div>
                    <div class="mb-1">
                        <label for="ukuran">Ukuran Kontainer</label>
                        <select class="form-control form-control-sm" id="ukuran" name="ukuran">
                            @foreach($ukurankontainer as $ukur)
                            <option value="{{ $ukur->KODE }}">{{ $ukur->URAIAN }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <a id="savekontainer" class="btn btn-primary">Simpan</a>
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
                    Form Perekaman Data {{ $notransaksi }}
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                    @can('gudang.transaksi.delete')
                    <button type="button" id="deletetrans" @if($header->id == '') disabled @endif class="btn btn-danger btn-sm m-0">Hapus</button>
                    <form id="formdelete">
                    @csrf
                    <input type="hidden" name="iddelete" value="{{ $header->ID }}">
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input type="hidden" value="{{ $header->ID }}" id="idtransaksi" name="idtransaksi">
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">Kantor</label>
                <div class="col-md-2">
                    <select class="form-control form-control-sm" id="kantor" name="kantor" value="{{ $header->KANTOR_ID }}">
                        <option value=""></option>
                        @foreach($kodekantor as $kantor)
                        <option @if($header->KANTOR_ID == $kantor->KANTOR_ID)selected @endif value="{{ $kantor->KANTOR_ID }}">{{ $kantor->URAIAN }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">Importir</label>
                <div class="col-md-4">
                    <select class="form-control form-control-sm" id="importir" name="importir" value="{{ $header->IMPORTIR }}">
                        <option value=""></option>
                        @foreach($importir as $imp)
                        <option @if($header->IMPORTIR == $imp->IMPORTIR_ID)selected @endif value="{{ $imp->IMPORTIR_ID }}">{{ $imp->NAMA }}</option>
                        @endforeach
                    </select>
                    <p class="error importir">Importir harus dipilih</p>
                </div>
                <label class="col-md-1 col-form-label form-control-sm text-right">Customer</label>
                <div class="col-md-4">
                    <select class="form-control form-control-sm" id="customer" name="customer" value="{{ $header->CUSTOMER }}">
                        <option value=""></option>
                        @foreach($customer as $cust)
                        <option @if($header->CUSTOMER == $cust->id_customer)selected @endif value="{{ $cust->id_customer }}">{{ $cust->nama_customer }}</option>
                        @endforeach
                    </select>
                    <p class="error customer">Customer harus dipilih</p>
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">No.Aju</label>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="noaju" value="{{ $header->NOAJU }}" id="noaju">
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">Nopen</label>
                <div class="col-md-2">
                    <input maxlength="6" type="text" class="form-control form-control-sm" name="nopen" value="{{ $header->NOPEN }}" id="nopen">
                    <p class="error nopen">Nopen 6 digit</p>
                </div>
                <div class="col-md-2"></div>
                <label class="col-md-1 col-form-label form-control-sm text-right">Tgl Nopen</label>
                <div class="col-md-2">
                    <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglentri" value="{{ $header->TGL_NOPEN }}" id="tglnopen">
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">No. Inv</label>
                <div class="col-md-2">
                    <input type="text" maxlength="24" class="form-control form-control-sm" name="noinv" id="noinv" value="{{ $header->NO_INV }}">
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">Kurs</label>
                <div class="col-md-2">
                    <input type="text" class="number form-control form-control-sm" name="kurs" id="kurs" value="{{ $header->NDPBM }}">
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">Tgl SPPB</label>
                <div class="col-md-2">
                    <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglsppb" value="{{ $header->TGL_SPPB }}" id="tglsppb">
                </div>
            </div>
            <div class="row mt-4">
                <div class="card col-sm-12 col-md-12 p-0">
                    <div class="card-body p-3">
                        <h5 class="card-title">Data Kontainer</h5>
                        <div class="form-row">
                            <div class="col primary-color text-white py-2 px-4">
                                Detail Kontainer
                            </div>
                            <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                                <a href="#modalkontainer" data-toggle="modal" class="text-white" id="addkontainer">Tambah Detail</a>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col mt-2">
                                <table width="100%" id="gridkontainer" class="table">
                                    <thead>
                                        <tr>
                                            <th>Nomor</th>
                                            <th>Ukuran</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="card col-sm-12 col-md-12 p-0">
                    <div class="card-body p-3">
                        <h5 class="card-title">Data Barang</h5>
                        <div class="form-row">
                            <div class="col primary-color text-white py-2 px-4">
                                Detail Barang
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
                                            <th>Kode Barang<br>Uraian</th>
                                            <th>Kms<br>Jns Kms</th>
                                            <th>Jml Sat Harga<br>Satuan</th>
                                            <th>CIF</th>
                                            <th>Harga Satuan</th>
                                            <th>Rupiah</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <input type="hidden" name="deletekontainer">
        <input type="hidden" name="deletedetail">
        </form>
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
var detailkontainer = @json($kontainer);
datadetailkontainer = JSON.parse(detailkontainer);

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
$('#modalkontainer').on('shown.bs.modal', function () {
    $('#nokontainer').focus();
})
$("#savekontainer").on("click", function(){
    var nomor = $("#nokontainer").val();
    var ukuran = $("#ukuran").val();
    var namaukuran = $("#ukuran option:selected").html();
    var act = $("#formkontainer").attr("act");
    if (act == "add"){
        tabelkontainer.row.add({NOMOR_KONTAINER: nomor, UKURAN_KONTAINER: ukuran, URAIAN: namaukuran}).draw();
        $("#nokontainer").val("");
        $("#ukuran").val("");
        $("#nokontainer").focus();
    }
    else if (act == "edit"){
        var id = $("#iddetailkontainer").val();
        var idx = $("#idxdetailkontainer").val();
        tabelkontainer.row(idx).data({ID: id, NOMOR_KONTAINER: nomor, UKURAN_KONTAINER: ukuran, URAIAN: namaukuran}).draw();
        $("#modalkontainer").modal("hide");
    }
});
$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
$(".number").inputmask("numeric", {
    radixPoint: ".",
    groupSeparator: ",",
    digits: 2,
    autoGroup: true,
    rightAlign: false,
    removeMaskOnSubmit: true,
    oncleared: function () { self.setValue(''); }
});
var tabelkontainer = $("#gridkontainer").DataTable({
    processing: false,
    serverSide: false,
    data: datadetailkontainer,
    dom: "t",
    pageLength: 50,
    rowCallback: function(row, data)
    {
        $(row).attr("id-transaksi", data.id);
        $('td:eq(1)', row).html('<input type="hidden" class="ukurankontainer" value="' + data.UKURAN_KONTAINER + '">' + data.URAIAN);
        $('td:eq(2)', row).html('<a href="#modalkontainer" class="editkontainer" data-toggle="modal" idkontainer="' + data.ID +
                                '"><i class="fa fa-edit"></i></a>' +
                                '&nbsp;&nbsp;<a class="delkontainer" idkontainer="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                );
    },
    select: 'single',     // enable single row selection
    responsive: false,     // enable responsiveness,
    rowId: 0,
    columns: [{
                target: 0,
                data: "NOMOR_KONTAINER"
              },
              { target: 1,
                data: "URAIAN"
              },
              { target: 2,
                data: null
              }
             ]
})
$("#addkontainer").on("click", function(){
    $("#nokontainer").val("");
    $("#ukuran").val("");
    $("#modalkontainer .modal-title").html("Tambah Kontainer");
    $("#formkontainer").attr("act","add");
})
$("body").on("click", ".editkontainer", function(){
    var row = $(this).closest("tr");
    var index = tabelkontainer.row(row).index();
    var row = tabelkontainer.rows(index).data();
    $("#nokontainer").val(row[0].NOMOR_KONTAINER);
    $("#ukuran").val(row[0].UKURAN_KONTAINER);
    $("#idxdetailkontainer").val(index);
    $("#iddetailkontainer").val(row[0].ID);
    $("#modalkontainer .modal-title").html("Edit Kontainer");
    $("#formkontainer").attr("act","edit");
})
$("body").on("click", ".delkontainer", function(){
    var row = $(this).closest("tr");
    var id = tabelkontainer.row(row).data().ID;
    if (typeof id != 'undefined'){
        $("input[name='deletekontainer'").val($("input[name='deletekontainer'").val() + id + ";");
    }
    var index = tabelkontainer.row(row).remove().draw();
})
$("#btnsimpan").on("click", function(){
        var detail = [];
        var rows = tabel.rows().data();
        $(rows).each(function(index,elem){
            detail.push(elem);
        })

        $(this).prop("disabled", true);
        var detailkontainer = [];
        var rowskontainer = tabelkontainer.rows().data();
        $(rowskontainer).each(function(index,elem){
            detailkontainer.push(elem);
        })
        $(".loader").show()
        $.ajax({
            url: "/gudang/crud",
            data: {header: $("#transaksi").serialize(), _token: "{{ csrf_token() }}", kontainer: detailkontainer, detail: detail},
            type: "POST",
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
                        if ($("#idtransaksi").val().trim() == ""){
                            var redirect = "/gudang";
                            if (typeof msg.id != 'undefined'){
                                redirect = redirect + "/" +msg.id;
                            }
                            document.location.href = redirect;
                        }
                        else {
                            document.location.reload();
                        }
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
    /*}
    else {
        return false;
    }*/
})
$("#deletetrans").on("click", function(){
    $("#modal .btn-ok").removeClass("d-none");
    $("#modal .btn-close").html("Batal");
    $("#modal .modal-body").html("Apakah Anda ingin menghapus data ini?");
    $("#modal .btn-ok").html("Ya").on("click", function(){
        $.ajax({
            url: "/gudang/delete",
            data: $("#formdelete").serialize(),
            type: "POST",
            success: function(msg) {
                $("#modal").modal("hide");
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
                    window.location.href = "/gudang";
                }
            }
        })
    })
    $("#modal").modal("show");
})
$("#savedetail").on("click", function(){
    $(this).prop("disabled", true);
    if ($("#kodebarang").val().trim() == ""){
        $("#modal .modal-body").html("Kode Barang Harus Diisi");
        $("#modal").modal("show");
        setTimeout(function(){
            $("#modal").modal("hide");
        }, 5000);
        $("#kodebarang").focus();
        return false;
    }
    var seri = $("#formseri").val();
    var satuan = $("#satuan option:selected").val();
    if (!satuan){
        satuan = "";
    }
    var namasatuan = $("#satuan option:selected").html();
    namasatuan = typeof namasatuan == 'undefined' ? '' : namasatuan;
    var kodebarang = $("#kodebarang").val();
    var uraian = $("#uraian").val();
    var jeniskemasan = $("#jeniskemasan option:selected").val();
    if (!jeniskemasan){
        jeniskemasan = "";
    }
    var namajenis = $("#jeniskemasan option:selected").html();
    namajenis = typeof namajenis == 'undefined' ? '' : namajenis;
    var cif = $("#cif").inputmask('unmaskedvalue');
    var jmlkemasan = $("#jmlkemasan").inputmask('unmaskedvalue');
    var jmlsatharga = $("#jmlsatharga").inputmask('unmaskedvalue');
    var hargasatuan = $("#harga").inputmask('unmaskedvalue');
    var act = $("#form").attr("act");
    if (act == "add"){
        tabel.row.add({KODEBARANG: kodebarang, URAIAN: uraian, SATUAN_ID: satuan, NAMASATUAN: namasatuan, JENISKEMASAN: jeniskemasan, NAMAJENISKEMASAN: namajenis, JMLKEMASAN: jmlkemasan, JMLSATHARGA: jmlsatharga, CIF: cif, HARGA: hargasatuan}).draw();
        $("#satuan").val("");
        $("#uraian").val("");
        $("#jeniskemasan").val("");
        $("#kodebarang").val("");
        $("#jmlkemasan").val("");
        $("#jmlsatharga").val("");
        $("#harga").val("");
        $("#cif").val("");
        $("#rupiah").val("");
        var rowcount = ("00" + (tabel.rows().count() + 1)).substr(-2,2);
        $("#formseri").val(rowcount);

        var tglnopen = $("#tglnopen").val().replace(/9/g,'\\9').replace(/-/g,"");
        if (tglnopen == ""){
            tglnopen = "\\9\\9\\9\\9\\9\\9\\9\\9";
        }
        var nopen = $("#nopen").val().trim() != "" ? $("#nopen").val().replace(/9/g,'\\9') : "\\9\\9\\9\\9\\9\\9";
        $("#kodebarang").inputmask("999999" + "-" + nopen + "-" + tglnopen + "-" + rowcount.replace(/9/g,'\\9'));

        $("#kodebarang").focus();
    }
    else if (act == "edit"){
        var id = $("#iddetail").val();
        var idx = $("#idxdetail").val();
        tabel.row(idx).data({ID: id, KODEBARANG: kodebarang, URAIAN: uraian, SATUAN_ID: satuan, NAMASATUAN: namasatuan, JENISKEMASAN: jeniskemasan, NAMAJENISKEMASAN: namajenis,JMLKEMASAN: jmlkemasan, JMLSATHARGA: jmlsatharga, CIF: cif, HARGA: hargasatuan}).draw();
        $("#modaldetail").modal("hide");
    }
    $(this).prop("disabled", false);
});
var tabel = $("#griddetail").DataTable({
    processing: false,
    serverSide: false,
    data: datadetail,
    dom: "t",
    pageLength: 1000,
    rowCallback: function(row, data)
    {
        $('td:eq(0)', row).html('<div>' +data.KODEBARANG + "</div><div>" + (data.URAIAN || '&nbsp;') + "</div>");
        $('td:eq(1)', row).html(parseFloat(data.JMLKEMASAN).formatMoney(0,"",",",".") + " " +(data.NAMAJENISKEMASAN || ""));
        $('td:eq(2)', row).html('<div>' +parseFloat(data.JMLSATHARGA).formatMoney(2,"",",",".") + "</div><div>" + (data.NAMASATUAN || "&nbsp;") + "</div>");
        $('td:eq(3)', row).html(parseFloat(data.CIF).formatMoney(2,"",",","."));
        $('td:eq(4)', row).html(parseFloat(data.HARGA).formatMoney(3,"",",","."));
        $('td:eq(5)', row).html((parseFloat(data.HARGA)*parseFloat($("#kurs").val().replace(/,/g,""))).formatMoney(2,"",",","."));
        $('td:eq(6)', row).html('<a href="#modaldetail" class="edit" data-toggle="modal" id="' + data.ID +
                                '"><i class="fa fa-edit"></i></a>' +
                                '&nbsp;&nbsp;<a class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                );
    },
    select: 'single',     // enable single row selection
    responsive: false,     // enable responsiveness,
    rowId: 0,
    columns: [
      { target: 0,
          data: "KODEBARANG"
      },
      { target: 1,
          data: "JMLKEMASAN"
      },
      { target: 2,
          data: "JMLSATHARGA"
      },
      { target: 3,
          data: "CIF"
      },
      { target: 4,
          data: "HARGA"
      },
      { target: 5,
          data: null
      },
      { target: 6,
          data: null
      }
     ],
})
$("#adddetail").on("click", function(){
    $("#satuan").val("");
    $("#kodebarang").val("");
    $("#jmlkemasan").val("");
    $("#jeniskemasan").val("");
    $("#jmlsatharga").val("");
    $("#harga").val("");
    $("#rupiah").val("");
    $("#uraian").val();
    $("#cif").val();
    var rowcount = ("00" + (tabel.rows().count() + 1)).substr(-2,2);
    $("#formseri").val(rowcount);
    var tglnopen = $("#tglnopen").val().replace(/9/g,'\\9').replace(/-/g,"");
    if (tglnopen == ""){
        tglnopen = "\\9\\9\\9\\9\\9\\9\\9\\9";
    }
    var nopen = $("#nopen").val().trim() != "" ? $("#nopen").val().replace(/9/g,'\\9') : "\\9\\9\\9\\9\\9\\9";
    $("#kodebarang").inputmask("999999" + "-" + nopen + "-" + tglnopen + "-" + rowcount.replace(/9/g,'\\9'));
    $("#modaldetail .modal-title").html("Tambah ");

    $("#form").attr("act","add");
})
$("#nopen").inputmask({"mask": "999999","removeMaskOnSubmit": true});
function nopenChange(){
    tabel.rows().every(function(index, tabLoop, rowLoop){
        var data = this.data();
        var kode = data.KODEBARANG.substr(0,6);
        var tglnopen = $("#tglnopen").val().trim().replace(/-/g,"");
        if (tglnopen == ""){
            tglnopen = "99999999";
        }
        var nopen = $("#nopen").val().trim();
        if (nopen == ""){
            nopen = "999999";
        }
        data.KODEBARANG = kode + "-" + nopen + "-" + tglnopen + "-" + data.SERIBARANG;
        this.data(data);
    }).draw();
}
$("#nopen").on("change", nopenChange);
$("#tglnopen").on("change", nopenChange);
$("body").on("click", ".edit", function(){
    var row = $(this).closest("tr");
    var index = tabel.row(row).index();
    var row = tabel.rows(index).data();
    $("#satuan").val(row[0].SATUAN_ID);
    $("#jeniskemasan").val(row[0].JENISKEMASAN);
    $("#uraian").val(row[0].URAIAN);
    var kodebarang = row[0].KODEBARANG;
    var elemkodebarang = row[0].KODEBARANG.split("-");
    $("#formseri").val(elemkodebarang[3]);
    kodebarang = kodebarang.substr(0,6);
    var tglnopen = $("#tglnopen").val().replace(/-/g,"").replace(/9/g,'\\9');
    if (tglnopen == ""){
        tglnopen = "\\9\\9\\9\\9\\9\\9\\9\\9";
    }
    var mask = kodebarang.substr(7);
    mask = mask.replace(/9/g,'\\9');
    var nopen = $("#nopen").val().trim() != "" ? $("#nopen").val().replace(/9/g,'\\9') : "\\9\\9\\9\\9\\9\\9";
    $("#kodebarang").inputmask("999999" + "-" + nopen + "-" + tglnopen + "-" + $("#formseri").val().replace(/9/g,'\\9'));
    $("#kodebarang").val(kodebarang);
    $("#jmlkemasan").val(row[0].JMLKEMASAN);
    $("#jmlsatharga").val(row[0].JMLSATHARGA);
    $("#cif").val(row[0].CIF);
    $("#rupiah").val((row[0].HARGA*parseFloat($("#kurs").val().replace(/,/g,""))).toFixed(2));
    $("#harga").val(row[0].HARGA);
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
$("#cif,#jmlsatharga").on("change", function(){
    var jmlsatharga = $("#jmlsatharga").val().replace(/,/g,"");
    var cif = $("#cif").val().replace(/,/g,"");
    var hargasatuan = parseFloat(cif)/parseFloat(jmlsatharga);
    hargasatuan = hargasatuan.toFixed(3).replace(/\d(?=(\d{3})+\.)/g, "$&,");
    var rupiah = hargasatuan*parseFloat($("#kurs").val().replace(/,/g,""));
    $("#harga").val(hargasatuan);
    $("#rupiah").val(rupiah.toFixed(2));
})
$('#modaldetail').on('shown.bs.modal', function (e) {
    $("#savedetail").removeClass("disabled");
    $('#kodebarang').focus();
})
})
</script>
@endpush
