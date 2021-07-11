@extends('layouts.base')
@section('main')
<style>
    .error {display:none;font-size: 0.75rem;color: red};
</style>
<style>
    .file-row > div {
      display: inline-block;
      vertical-align: top;
      padding: 8px;
    }
    #preview-container .dz-progress .dz-upload {
        background: #333;
        background: linear-gradient(to bottom, #666, #444);
        position: absolute;
        top: 25%;
        right: 10%;
        height: 12px;
        width: 0;
        -webkit-transition: width 300ms ease-in-out;
        -moz-transition: width 300ms ease-in-out;
        -ms-transition: width 300ms ease-in-out;
        -o-transition: width 300ms ease-in-out;
        transition: width 300ms ease-in-out;
    }
    #preview-container .dz-remove {
        margin-left: 10px;
    }
</style>
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <form id="form" act="">
                    <input {{ $readonly }} type="hidden" name="idxdetail" id="idxdetail">
                    <input {{ $readonly }} type="hidden" name="iddetail" id="iddetail">
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="invbilling">Inv Billing</label>
                        <div class="col-md-9">
                            <input {{ $readonly }} type="text" id="invbilling" name="invbilling" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="tglinvbilling">Tgl Inv Billing</label>
                        <div class="col-md-9">
                            <input {{ $readonly }} type="text" class="datepicker form-control form-control-sm" name="tglinvbilling" id="tglinvbilling">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Nominal</label>
                        <div class="col-md-9">
                            <input {{ $readonly }} type="text" class="number form-control form-control-sm" name="nominal" id="nominal">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">PPN</label>
                        <div class="col-md-9">
                            <input {{ $readonly }} type="text" class="number form-control form-control-sm" name="ppn" id="ppn">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Total</label>
                        <div class="col-md-9">
                            <input readonly type="text" class="number form-control form-control-sm" name="total" id="total">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                @if($readonly != 'readonly')
                <a id="savedetail" class="btn btn-primary">Simpan</a>
                <a class="btn btn-danger" data-dismiss="modal">Batal</a>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="card col-md-12 p-0">
        <div class="card-header font-weight-bold">
            <div class="row">
                <div class="col-md-4 py-0 pl-4 mt-1">
                    Form Perekaman Data {{ $header->JOB_ORDER }}
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    @if($readonly == '')
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                    @can('transaksi.delete')
                    @if($header->ID != '')
                    <button type="button" id="deletetrans" class="btn btn-danger btn-sm m-0">Hapus</button>
                    <form id="formdelete">
                    @csrf
                    <input {{ $readonly }} type="hidden" name="iddelete" value="{{ $header->ID }}">
                    </form>
                    @endif
                    @endcan
                    @endif
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input {{ $readonly }} type="hidden" value="{{ $header->ID }}" id="idtransaksi" name="idtransaksi">
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">Job Order</label>
                <div class="col-md-2">
                  <input type="text" readonly class="form-control form-control-sm" name="joborder" id="joborder" value="{{ $header->JOB_ORDER }}">
                </div>
                <div class="col-md-2"></div>
                <label class="col-md-1 col-form-label form-control-sm text-right">Tgl Job</label>
                <div class="col-md-2">
                  <input {{ $readonly }} type="text" class="datepicker form-control form-control-sm" name="tgljob" id="tgljob" value="{{ $header->TGL_JOB }}">
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">Jns Dokumen</label>
                <div class="col-md-2">
                    <select {{ $readonly == 'readonly' ? 'disabled' : '' }} class="form-control form-control-sm" id="jenisdokumen" name="jenisdokumen">
                        <option value=""></option>
                        @foreach($jenisdokumen as $jdok)
                        <option @if($header->JENIS_DOK == $jdok->JENISDOKUMEN_ID)selected @endif value="{{ $jdok->JENISDOKUMEN_ID }}">{{ $jdok->KODE }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">No Dokumen</label>
                <div class="col-md-3">
                    <input {{ $readonly }} maxlength="30" type="text" class="form-control form-control-sm" name="nodok" value="{{ $header->NO_DOK }}" id="nodok">
                </div>
                <div class="col-md-1"></div>
                <label class="col-md-1 col-form-label form-control-sm text-right">Tgl Dokumen</label>
                <div class="col-md-1">
                    <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tgldokumen" value="{{ $header->TGL_DOK }}" id="tgldokumen">
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">Customer</label>
                <div class="col-md-4">
                    <select {{ $readonly == 'readonly' ? 'disabled' : '' }} class="form-control form-control-sm" id="customer" name="customer" value="{{ $header->CUSTOMER }}">
                        <option value=""></option>
                        @foreach($customer as $cust)
                        <option @if($header->CUSTOMER == $cust->id_customer)selected @endif value="{{ $cust->id_customer }}">{{ $cust->nama_customer }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="col-md-1 col-form-label form-control-sm text-right">Total Billing</label>
                <div class="col-md-2">
                    <input type="text" id="totalbilling" name="totalbilling" class="number form-control form-control-sm" readonly value="{{ $header->TOTAL }}">
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">Tgl Tiba</label>
                <div class="col-md-1">
                    <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tgltiba" value="{{ $header->TGL_TIBA }}" id="tgltiba">
                </div>
                <label class="col-md-1 col-form-label form-control-sm">Jml Kontainer</label>
                <div class="col-md-2">
                    <input {{ $readonly }} type="text" class="form-control form-control-sm" name="jmlkontainer" value="{{ $header->JML_KONTAINER }}" id="jmlkontainer">
                </div>
                <label class="col-md-1 col-form-label form-control-sm text-right">Saldo</label>
                <div class="col-md-2">
                    <input readonly type="text" class="number form-control form-control-sm" name="saldo" value="{{ $header->SALDO }}" id="saldo">
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-1 col-form-label form-control-sm">No.Aju</label>
                <div class="col-md-1">
                    <input {{ $readonly }} maxlength="6" type="text" class="form-control form-control-sm" name="noaju" value="{{ $header->NOAJU }}" id="noaju">
                </div>
                <label class="col-md-1 col-form-label form-control-sm">Nopen</label>
                <div class="col-md-1">
                    <input {{ $readonly }} maxlength="6" type="text" class="form-control form-control-sm" name="nopen" value="{{ $header->NOPEN }}" id="nopen">
                </div>
                <label class="col-md-1 col-form-label form-control-sm text-right">Tgl Nopen</label>
                <div class="col-md-1">
                    <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tglnopen" value="{{ $header->TGL_NOPEN }}" id="tglnopen">
                </div>
                <label class="col-md-1 col-form-label form-control-sm text-right">Tgl SPPB</label>
                <div class="col-md-1">
                    <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tglsppb" value="{{ $header->TGL_SPPB }}" id="tglsppb">
                </div>
            </div>
            <div class="row mt-2">
                <div class="card col-sm-12 col-md-12 p-0">
                    <div class="card-body p-3">
                        <div class="form-row">
                            <div class="col primary-color text-white py-2 px-4">
                                Detail Dokumen
                            </div>
                            <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                                @if($readonly == '')
                                <a href="#modaldetail" data-toggle="modal" class="text-white" id="adddetail">Tambah Detail</a>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col mt-2">
                                <table width="100%" id="griddetail" class="table">
                                    <thead>
                                        <tr>
                                            <th>Inv Billing</th>
                                            <th>Tgl Inv Billing</th>
                                            <th>Nominal</th>
                                            <th>PPn</th>
                                            <th>Total</th>
                                            <th>Upload Bill</th>
                                            @if($readonly == '')
                                            <th>Opsi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <input {{ $readonly }} type="hidden" name="deletedetail">
        </form>
    </div>
</div>
<div id="dropzone" class="d-none"></div>
<script type="text/template" id="template">
<div class="file-row row border">
    <div class="col-md-12">
        <span class="name d-block" data-dz-name></span>
    </div>
    <div class="col-md-12 p-2">
        <div class="dz-progress mt-4">
            <span class="dz-upload" data-dz-uploadprogress></span>
        </div>
        <div class="dz-success-mark text-center"></div>
        <div class="dz-error-mark text-center"></div>
    </div>
</div>
</script>
@endsection
@push('stylesheets_end')
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
    <link href="{{ asset('jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
@endpush
@push('scripts_end')
<script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<script>

var detail = @json($detail);
datadetail = JSON.parse(detail);

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

var tabel = $("#griddetail").DataTable({
    processing: false,
    serverSide: false,
    data: datadetail,
    dom: "t",
    pageLength: 1000,
    rowCallback: function(row, data)
    {
        var files = "<ul>";
        for (var i in data.files){
            files += '<li file_id="' + data.files[i].ID +'">';
            files += data.files[i].FILENAME;
            @if($readonly == "")
            files += '<a href="#" class="deletefile" title="Hapus File">' +
                        '<i class="fa fa-trash"></i>' +
                     '</a>' +
                     '<input {{ $readonly }} type="hidden" value="' + data.files[i].ID +'" name="fileid">';
            @endif
            files += '<a href="/transaksi/getfile?file=' + data.files[i].ID +'" tile="Download File" class="download">' +
                     '<i class="fa fa-download"></i></a>';
            files += "</li>";
        }
        files += "</ul>";
        $('td:eq(2)', row).html(parseFloat(data.NOMINAL).formatMoney(2,"",",","."));
        $('td:eq(3)', row).html(parseFloat(data.PPN).formatMoney(2,"",",","."));
        $('td:eq(4)', row).html((parseFloat(data.NOMINAL)+parseFloat(data.PPN)).formatMoney(2,"",",","."));
        $('td:eq(5)', row).html(files);
        @if($readonly == '')
        $('td:eq(6)', row).html('<a class="uploadfile"><i class="fa fa-upload"></i></a>&nbsp;&nbsp;' +
                                '<a href="#modaldetail" class="edit" data-toggle="modal" id="' + data.ID +
                                '"><i class="fa fa-edit"></i></a>' +
                                '&nbsp;&nbsp;<a class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                );
        @endif
    },
    select: 'single',     // enable single row selection
    responsive: false,     // enable responsiveness,
    rowId: 0,
    columns: [
      { target: 0,
          data: "INV_BILLING"
      },
      { target: 1,
          data: "TGL_INV_BILLING",
      },
      { target: 2,
          data: "NOMINAL"
      },
      { target: 3,
          data: "PPN"
      },
      { target: 4,
          data: null
      },
      { target: 5,
          data: null
      },
      @if($readonly == '')
      { target: 6,
          data: null
      }
      @endif
     ],
})
@if($readonly == '')
var myDropzone = new Dropzone("#dropzone", {
    url: "/transaksi/upload",
    uploadMultiple: false,
    maxFiles: maxFiles - numFiles,
    maxFilesize: 4,
    previewsContainer: "#preview-container",
    previewTemplate: $("#template").html(),
    acceptedFiles: ".xls, .xlsx, .pdf, .jpg, .jpeg, .png",
    init:function(){
        var self = this;
        // config
        self.options.addRemoveLinks = true;
        self.options.dictRemoveFile = "Hapus";
        self.on("success", function(file, value) {
            $(file.previewElement).append('<input type="hidden" name="fileid" value="' + value + '">');
        })
        self.on("sending", function(file, xhr, formData) {
            formData.append("_token", "{{ csrf_token() }}");
        });
        self.on("addedfile", function(file) {
            if (this.files.length > self.options.maxFiles){
                this.removeFile(file);
            }
        });
        self.on("complete", function (file) {
            if(file.status == Dropzone.SUCCESS){
                success = true;
                $(file.previewElement).find(".dz-success-mark").html('<i class="fa fa-check-circle text-success">');
                $(file.previewElement).find(".dz-error-mark").hide();
                $(file.previewElement).find(".dz-progress").hide();
            }
            else if (file.status == Dropzone.ERROR){
                $(file.previewElement).find('.dz-error-mark').html('<i class="fa fa-times-circle text-danger"></i>');
                $(file.previewElement).find(".dz-success-mark").hide();
                $(file.previewElement).find(".dz-progress").hide();
            }
        });
    }
});
$("#btnsimpan").on("click", function(){
        var detail = [];
        var rows = tabel.rows().data();
        $(rows).each(function(index,elem){
            detail.push(elem);
        })

        $(this).prop("disabled", true);
        $(".loader").show()
        $.ajax({
            url: "/transaksi/crud",
            data: {header: $("#transaksi").serialize(), _token: "{{ csrf_token() }}", detail: detail},
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
                            var redirect = "/transaksi";
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
            url: "/transaksi/delete",
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
                    window.location.href = "/transaksi";
                }
            }
        })
    })
    $("#modal").modal("show");
})
function count_total(){
  var total = 0;
  $("#griddetail tbody tr").each(function(index,elem){
      var subtotal = $(elem).find("td").eq(4).html();
      total += subtotal.trim() == "" ? 0 : parseFloat(subtotal.replace(/,/g,""));
  });
  $("#totalbilling").val(total);
  var saldo = total - {{ $header->TOTAL_BIAYA ?? 0}};
  $("#saldo").val(saldo);
}
$("#savedetail").on("click", function(){
    $(this).prop("disabled", true);
    if ($("#invbilling").val().trim() == ""){
        $("#modal .modal-body").html("No Inv Billing Harus Diisi");
        $("#modal").modal("show");
        setTimeout(function(){
            $("#modal").modal("hide");
        }, 5000);
        $("#kodebarang").focus();
        return false;
    }
    var invbilling = $("#invbilling").val();
    var tglinvbilling = $("#tglinvbilling").val();
    var nominal = $("#nominal").inputmask('unmaskedvalue');
    var ppn = $("#ppn").inputmask('unmaskedvalue');
    nominal = nominal.trim() == "" ? 0 : nominal;
    ppn = ppn.trim() == "" ? 0 : ppn;
    var act = $("#form").attr("act");
    if (act == "add"){
        tabel.row.add({INV_BILLING: invbilling, TGL_INV_BILLING: tglinvbilling, NOMINAL: nominal, PPN: ppn}).draw();
        $("#invbilling").val("");
        $("#tglinvbilling").val("");
        $("#nominal").val("");
        $("#ppn").val("");
        $("#total").val("");
        $("#invbilling").focus();
    }
    else if (act == "edit"){
        var id = $("#iddetail").val();
        var idx = $("#idxdetail").val();
        tabel.row(idx).data({ID: id, INV_BILLING: invbilling, TGL_INV_BILLING: tglinvbilling, NOMINAL: nominal, PPN: ppn}).draw();
        $("#modaldetail").modal("hide");
    }
    count_total();
    $(this).prop("disabled", false);
});

$("#adddetail").on("click", function(){
    $("#invbilling").val("");
    $("#tglinvbilling").val("");
    $("#nominal").val("");
    $("#ppn").val("");
    $("#total").val("");
    $("#modaldetail .modal-title").html("Tambah ");
    $("#invbilling").focus();
    $("#form").attr("act","add");
})
$("body").on("click", ".edit", function(){
    var row = $(this).closest("tr");
    var index = tabel.row(row).index();
    var row = tabel.rows(index).data();
    $("#invbilling").val(row[0].INV_BILLING);
    $("#tglinvbilling").val(row[0].TGL_INV_BILLING);
    $("#nominal").val(row[0].NOMINAL);
    $("#ppn").val(row[0].PPN);
    $("#total").val(parseFloat(row[0].NOMINAL) + parseFloat(row[0].PPN));
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
    count_total();
})
$("#nominal,#ppn").on("change", function(){
    var nominal = $("#nominal").inputmask("unmaskedvalue");
    var ppn = $("#ppn").inputmask("unmaskedvalue");
    nominal = nominal.trim() == "" ? 0 : nominal;
    ppn = ppn.trim() == "" ? 0 : ppn;
    var total = parseFloat(nominal) + parseFloat(ppn);
    $("#total").val(total);
})
$('#modaldetail').on('shown.bs.modal', function (e) {
    $("#savedetail").removeClass("disabled");
    $('#invbilling').focus();
})
$("a.deletefile").on("click",function(){
    $(this).closest("li").remove();
    myDropzone.options.maxFiles = maxFiles - $("#listfiles tr").length;
    if($(this).hasClss("newfile")){
      $.ajax({
          url: "/transaksi/removefile",
          data: {_token: "{{ csrf_token() }}", id: hidden},
          method: "POST"
      });
    }
    $("div#dropzone").show();
});
$("body").on("click", ".uploadfile", function(){
    myDropzone.hiddenFileInput.click();
})
@endif
})
</script>
@endpush
