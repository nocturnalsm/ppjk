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
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="noinv">No</label>
                        <div class="col-md-9">
                            <input type="text" id="nomer" name="nomer" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="kodehs">Kode HS</label>
                        <div class="col-md-9">
                            <input type="text" id="kodehs" name="kodehs" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="kurs">Saldo Awal</label>
                        <div class="col-md-9">
                            <input type="text" id="saldoawal" name="saldoawal" class="number form-control form-control-sm validate">
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
                    Form Perekaman Quota
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input type="hidden" value="{{ $header->ID }}" id="idtransaksi" name="idtransaksi">
            <div class="row">
                <div class="col-md-7 col-sm-12 px-auto pt-0">
                    <div class="form-row px-2">
                        <label class="col-md-3 form-control-sm">Consignee</label>
                        <div class="col-md-9">
                            <select class="form-control form-control-sm" id="consignee" name="consignee" value="{{ $header->CONSIGNEE }}">
                                <option value=""></option>
                                @foreach($importir as $imp)
                                <option @if($header->CONSIGNEE == $imp->IMPORTIR_ID) selected @endif value="{{ $imp->IMPORTIR_ID }}">{{ $imp->NAMA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row px-2 pb-2">
                        <label class="col-md-3 col-form-label form-control-sm">No. PI</label>
                        <div class="col-md-4">
                            <input type="text" maxlength="24" class="form-control form-control-sm" name="nopi" id="nopi" value="{{ $header->NO_PI }}">
                            <p class="error noinv">Nomor PI harus diisi</p>
                        </div>
                        <label class="col-md-2 col-form-label form-control-sm">Tgl PI</label>
                        <div class="col-md-3">
                            <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglpi" value="{{ $header->TGL_PI }}" id="tglpi">
                        </div>
                    </div>
                    <div class="form-row px-2 pb-2">
                        <label class="col-md-3 col-form-label form-control-sm">Berlaku s/d</label>
                        <div class="col-md-3">
                            <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglberlaku" value="{{ $header->TGL_BERLAKU }}" id="tglberlaku">
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <label class="col-md-3 col-form-label form-control-sm">Status</label>
                        <div class="col-md-4">
                            <select class="form-control form-control-sm" id="status" name="status" value="{{ $header->STATUS }}">
                                <option @if($header->STATUS == "") selected @endif value=""></option>
                                <option @if($header->STATUS == "Y") selected @endif value="Y">Berlaku</option>
                                <option @if($header->STATUS == "T") selected @endif value="T">Tidak Berlaku</option>
                            </select>
                        </div>
                    </div>
                </div>
                </form>
                <div class="col-md-5 col-sm-12">
                    <h5 class="card-title">Lampiran</h5>
                    <div class="card-body py-0">
                        <div class="row">
                            <div id="dropzone" class="p-4 border">
                                <div class="dz-message needsclick">
                                    Drag file ke kotak di bawah ini untuk meng-upload atau klik untuk memilih file Excel (.xls | .xlsx | .pdf).<br>
                                    <span class="note needsclick"></span>
                                </div>
                                <div id="preview-container" class="card-body">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row px-1 mt-2">
                            <div class="col-md-12 col-sm-12">
                                <table class="table table-bordered" id="listfiles">
                                @foreach($files as $file)
                                    <tr>
                                        <td>
                                            {{ $file->FILEREALNAME }}
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="delete" title="Hapus File">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <input type="hidden" value="{{ $file->ID }}" name="fileid">
                                            <a href="/transaksi/getfile?file={{ $file->ID }}" tile="Download File" class="download">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row px-2">
                <div class="col-md-12">
                    <div class="row mb-2">
                        <div class="card col-md-12 p-0">
                            <div class="card-body p-3">
                                <div class="form-row">
                                    <div class="col primary-color text-white py-2 px-4">
                                        Detail Quota
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
                                                    <th>No</th>
                                                    <th>Kode HS</th>
                                                    <th>Saldo Awal</th>
                                                    <th>Satuan</th>
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
<script type="text/template" id="template">
<div class="file-row row border">
    <div class="col-md-9">
        <span class="name d-block" data-dz-name></span>
    </div>
    <div class="col-md-3 p-2">
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
    <link href="{{ asset('jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
@endpush
@push('scripts_end')
<script type="text/javascript" src="{{ asset('js/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<script>
    $(function(){

        var detail = @json($detail);
        datadetail = JSON.parse(detail);

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
        $("#savedetail").on("click", function(){
            $(this).prop("disabled", true);
            var no = $("#nomer").val();
            var saldoawal = $("#saldoawal").inputmask('unmaskedvalue');
            var kodehs = $("#kodehs").val();
            var satuan = $("#satuan").val();
            var namasatuan = $("#satuan option:selected").html();
            var act = $("#form").attr("act");

            if (act == "add"){
                tabel.row.add({NO: no, KODE_HS: kodehs, SALDO_AWAL: saldoawal, SATUAN_ID: satuan, satuan: namasatuan}).draw();
                $("#nomer").val("");
                $("#kodehs").val("");
                $("#saldoawal").val("");
                $("#satuan").val("");
                $("#nomer").focus();
            }
            else if (act == "edit"){
                var id = $("#iddetail").val();
                var idx = $("#idxdetail").val();
                tabel.row(idx).data({ID: id, NO: no, KODE_HS: kodehs, SALDO_AWAL: saldoawal, SATUAN_ID: satuan, satuan: namasatuan}).draw();
                $("#modaldetail").modal("hide");
            }
            $(this).prop("disabled", false);
        });
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
        function checkEmpty(elem){
            if ($("#" + elem).val().trim() === ""){
                $(".error." + elem).show();
                var empty = true;
            }
            else {
                $(".error." + elem).hide();
                var empty = false;
            }
            return empty;
        }
        function validate(){
            var valid = true;
            var check1 = ["noinv","nopo","nosc","nobl","noform"];
            $(check1).each(function(index, elem){
                if (checkEmpty(elem)){
                    valid = false;
                }
            });
            return valid;
        }
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

            rowCallback: function(row, data)
            {
                $('td:eq(2)', row).html(parseFloat(data.SALDO_AWAL).formatMoney(2,"",",","."));
                $('td:eq(4)', row).html('<a href="#modaldetail" class="edit" data-toggle="modal" id="' + data.ID +
                                        '"><i class="fa fa-edit"></i></a>' +
                                        '&nbsp;&nbsp;<a class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                        );
            },
            select: 'single',     // enable single row selection
            responsive: false,     // enable responsiveness,
            rowId: 0,
            pageLength: 1000,
            columns: [{
                target: 0,
                data: "NO"
            },
            { target: 1,
                data: "KODE_HS"
            },
            { target: 2,
                data: "SALDO_AWAL"
            },
            { target: 3,
                data: "satuan"
            },
            { target: 4,
                data: null
            }
            ],
        })
        $("#adddetail").on("click", function(){
            $("#nomer").val("");
            $("#kodehs").val("");
            $("#saldoawal").val("");
            $("#satuan").val("");
            $("#modaldetail .modal-title").html("Tambah ");
            $("#form").attr("act","add");
        })
        $("body").on("click", ".edit", function(){
            var row = $(this).closest("tr");
            var index = tabel.row(row).index();
            var row = tabel.rows(index).data();
            $("#nomer").val(row[0].NO);
            $("#kodehs").val(row[0].KODE_HS);
            $("#saldoawal").val(row[0].SALDO_AWAL);
            $("#satuan").val(row[0].SATUAN_ID);
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
                $(this).prop("disabled", true);
                var detail = [];
                var rows = tabel.rows().data();                                                                                                                                                                            ;
                $(rows).each(function(index,elem){
                    detail.push(elem);
                })
                $(".loader").show()
                var files = $("input[name=fileid]").map(function(index){
                    return {id: $(this).val()};
                }).get();
                $.ajax({
                    url: "/transaksi/crud",
                    data: {_token: "{{ csrf_token() }}", type: "userquota", header: $("#transaksi").serialize(), detail: detail, files: files},
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
                            $("#modal").modal("show");
                            setTimeout(function(){
                                window.location.reload();
                            }, 2000);
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
        var numFiles = $("#listfiles tr").length;
        var maxFiles = 6;
        if (numFiles == maxFiles){
            $("div#dropzone").hide();
        }
        var myDropzone = new Dropzone("#dropzone", {
            url: "/transaksi/upload",
            uploadMultiple: false,
            params: {filetype: 2},
            maxFiles: maxFiles - numFiles,
            maxFilesize: 4,
            previewsContainer: "#preview-container",
            previewTemplate: $("#template").html(),
            acceptedFiles: ".pdf",
            init:function(){
                var self = this;
                // config
                self.options.addRemoveLinks = true;
                self.options.dictRemoveFile = "Hapus";
                self.on("success", function(file, value) {                    
                    $(file.previewElement).append('<input type="hidden" name="fileid" value="' + value.id + '">');
                })
                // On removing file
                self.on("removedfile", function (file) {
                    var hidden = $(file.previewElement).find("input[name=fileid]").val();
                    if (hidden){
                        $.ajax({
                            url: "/transaksi/removefile",
                            data: {_token: "{{ csrf_token() }}", id: hidden},
                            method: "POST"
                        });
                    }
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
                self.on("sending", function(file, xhr, formData) {
                    formData.append("_token", "{{ csrf_token() }}");
                });
            }
        });
        $("#listfiles a.delete").on("click",function(){
            $(this).closest("tr").remove();
            myDropzone.options.maxFiles = maxFiles - $("#listfiles tr").length;
            $("div#dropzone").show();
        });
        $("#kodehs").inputmask("9999.99.99");
    })
</script>
@endpush
