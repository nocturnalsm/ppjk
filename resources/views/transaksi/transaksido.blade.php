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
<div class="row">
    <div class="card col-md-12 p-0">
        <div class="card-header font-weight-bold">
            <div class="row">
                <div class="col-md-4 py-0 pl-4 mt-1">
                    Form Perekaman Dokumen {{ $notransaksi }}
                </div>
                @if($readonly == '')
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                </div>
                @endif
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        @csrf
        <div class="card-body">
            <input {{ $readonly }} type="hidden" value="{{ $header->ID }}" id="idtransaksi" name="idtransaksi">
            <div class="row">
                <div class="col-md-7 col-sm-12 px-auto pt-0">
                    <div class="form-row px-2">
                        <label class="col-md-2 col-form-label form-control-sm">No. Inv</label>
                        <div class="col-md-4">
                            <input {{ $readonly }} type="text" maxlength="24" class="form-control form-control-sm" name="noinv" id="noinv" value="{{ $header->NO_INV }}">
                            <p class="error noinv">Nomor Inv harus diisi</p>
                        </div>
                        <label class="col-md-2 col-form-label form-control-sm">Tgl Inv</label>
                        <div class="col-md-3">
                            <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tglinv" value="{{ $header->TGL_INV }}" id="tglinv">
                        </div>
                    </div>
                    <div class="form-row px-2 pb-0">
                        <label class="col-md-2 col-form-label form-control-sm">No. PO</label>
                        <div class="col-md-4">
                            <input {{ $readonly }} type="text" class="form-control form-control-sm" name="nopo" value="{{ $header->NO_PO }}" id="nopo">
                        </div>
                        <label class="col-md-2 col-form-label form-control-sm">Tgl PO</label>
                        <div class="col-md-3">
                            <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tglpo" value="{{ $header->TGL_PO }}" id="tglpo">
                        </div>
                    </div>
                    <div class="form-row px-2 pb-0">
                        <label class="col-md-2 col-form-label form-control-sm">No. S/C</label>
                        <div class="col-md-4">
                            <input {{ $readonly }} type="text" class="form-control form-control-sm" name="nosc" value="{{ $header->NO_SC }}" id="nosc">
                        </div>
                        <label class="col-md-2 col-form-label form-control-sm">Tgl S/C</label>
                        <div class="col-md-3">
                            <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tglsc" value="{{ $header->TGL_SC }}" id="tglsc">
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <label class="col-md-2 col-form-label form-control-sm">No. BL</label>
                        <div class="col-md-4">
                            <input {{ $readonly }} type="text" maxlength="24" class="form-control form-control-sm" name="nobl" id="nobl" value="{{ $header->NO_BL }}">
                        </div>
                        <label class="col-md-2 col-form-label form-control-sm">Tgl BL</label>
                        <div class="col-md-3">
                            <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tglbl" value="{{ $header->TGL_BL }}" id="tglbl">
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <label class="col-md-2 col-form-label form-control-sm ">No. Form</label>
                        <div class="col-md-4">
                            <input {{ $readonly }} type="text" class="form-control form-control-sm" id="noform" name="noform" value="{{ $header->NO_FORM }}">
                        </div>
                        <label class="col-md-2 col-form-label form-control-sm">Tgl Form</label>
                        <div class="col-md-3">
                            <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tglform" value="{{ $header->TGL_FORM }}" id="tglform">
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <label class="col-md-2 col-form-label form-control-sm">Kapal</label>
                        <div class="col-md-9">
                            <input {{ $readonly }} type="text" class="form-control form-control-sm" value="{{ $header->KAPAL }}" name="kapal" id="kapal">
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <label class="col-md-2 col-form-label form-control-sm">Pelabuhan Muat</label>
                        <div class="col-md-4">
                            <select {{ $readonly == 'readonly' ? 'disabled' : '' }} class="form-control form-control-sm" id="pelmuat" name="pelmuat" value="{{ $header->PEL_MUAT }}">
                                <option value=""></option>
                                @foreach($pelmuat as $pel)
                                <option @if($header->PEL_MUAT == $pel->PELMUAT_ID) selected @endif value="{{ $pel->PELMUAT_ID }}">{{ $pel->URAIAN }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="col-md-2 col-form-label form-control-sm">Jml Kemasan</label>
                        <div class="col-md-2">
                            <input {{ $readonly }} type="text" class="number form-control form-control-sm" value="{{ $header->JUMLAH_KEMASAN }}" name="jmlkemasan" id="jmlkemasan">
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <label class="col-md-2 col-form-label form-control-sm">Tgl Berangkat</label>
                        <div class="col-md-2">
                            <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tglberangkat" id="tglberangkat" value="{{ $header->TGL_BERANGKAT }}">
                        </div>
                        <label class="col-md-2 offset-md-2 col-form-label form-control-sm px-0">Tanggal Tiba</label>
                        <div class="col-md-3">
                            <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" value="{{ $header->TGL_TIBA }}" name="tgltiba" id="tgltiba">
                            <p class="error tgltiba">Tgl Tiba harus diisi</p>
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <label class="col-md-2 col-form-label form-control-sm">Pembayaran</label>
                        <div class="col-md-2">
                            <select {{ $readonly == 'readonly' ? 'disabled' : '' }} class="form-control form-control-sm" id="pembayaran" name="pembayaran" value="{{ $header->PEMBAYARAN }}">
                                <option value=""></option>
                                <option @if($header->PEMBAYARAN == 'Y') selected @endif value="Y">TT</option>
                                <option @if($header->PEMBAYARAN == 'T') selected @endif value="T">NON TT</option>
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label form-control-sm text-right">TOP</label>
                        <div class="col-md-auto">
                            <select {{ $readonly == 'readonly' ? 'disabled' : '' }} class="form-control form-control-sm" id="top" name="top" value="{{ $header->TOP }}">
                                <option value=""></option>
                                @foreach($top as $term)
                                <option @if($header->TOP == $term->TOP_ID) selected @endif value="{{ $term->TOP_ID }}">{{ $term->TOP }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="col-md-auto col-form-label form-control-sm px-2">Jth Tempo</label>
                        <div class="col-md-2">
                            <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" value="{{ $header->TGL_JATUH_TEMPO }}" name="tgljatuhtempo" id="tgljatuhtempo">
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <label class="col-md-2 col-form-label form-control-sm">Currency</label>
                        <div class="col-md-2">
                            <select {{ $readonly == 'readonly' ? 'disabled' : '' }} class="form-control form-control-sm" id="curr" name="curr" value="{{ $header->CURR }}">
                                <option value=""></option>
                                @foreach($matauang as $uang)
                                <option @if($header->CURR == $uang->MATAUANG_ID) selected @endif value="{{ $uang->MATAUANG_ID }}">{{ $uang->MATAUANG }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label form-control-sm text-right">CIF</label>
                        <div class="col-md-3">
                            <input {{ $readonly }} type="text" class="cifnumber form-control form-control-sm" name="cif" id="cif" value="{{ $header->CIF }}">
                        </div>
                        <label class="col-md-1 col-form-label form-control-sm">Faktur</label>
                        <div class="col-md-2">
                            <select {{ $readonly == 'readonly' ? 'disabled' : '' }} class="form-control form-control-sm" id="faktur" name="faktur" value="{{ $header->FAKTUR }}">
                                <option value=""></option>
                                <option @if($header->FAKTUR == 'A') selected @endif value="A">Semua</option>
                                <option @if($header->FAKTUR == 'P') selected @endif value="P">Sebagian</option>
                                <option @if($header->FAKTUR == 'T') selected @endif value="T">Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <label class="col-md-2 col-form-label form-control-sm">Tgl Dok Terima</label>
                        <div class="col-md-2">
                        <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" value="{{ $header->TGL_DOK_TRM }}" name="tgldoktrm" id="tgldoktrm">
                        </div>
                    </div>
                </div>
                </form>
                <div class="col-md-5 col-sm-12">
                    <h5 class="card-title">Lampiran</h5>
                    <div class="card-body py-0">
                        <div class="row">
                            @if($readonly == '')
                            <div id="dropzone" class="p-4 border">
                                <div class="dz-message needsclick">
                                    Drag file ke kotak di bawah ini untuk meng-upload atau klik untuk memilih file Excel (.xls | .xlsx | .pdf).<br>
                                    <span class="note needsclick"></span>
                                </div>
                                <div id="preview-container" class="card-body">
                                </div>
                            </div>
                            @endif
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
                                            <select {{ $readonly == 'readonly' ? 'disabled' : '' }} class="form-control jenisfile" name="jenisfile[]">
                                            @foreach($jenisfile as $jenis)
                                            <option @if($file->JENISFILE_ID == $jenis->ID) selected @endif value="{{ $jenis->ID }}">{{ $jenis->JENIS }}</option>
                                            @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            @if($readonly == "")
                                            <a href="#" class="delete" title="Hapus File">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <input {{ $readonly }} type="hidden" value="{{ $file->ID }}" name="fileid">
                                            @endif
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
        </div>
    </div>
</div>
<script type="text/template" id="template">
<div class="file-row row border">
    <div class="col-md-9">
        <span class="name d-block" data-dz-name></span>
        <select {{ $readonly == 'readonly' ? 'disabled' : '' }} class="form-control jenisfile" name="jenisfile[]">
        @foreach($jenisfile as $jenis)
        <option value="{{ $jenis->ID }}">{{ $jenis->JENIS }}</option>
        @endforeach
        </select>
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
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
    <link href="{{ asset('jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
@endpush
@push('scripts_end')
<script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<script>

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
        $(".cifnumber").inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 3,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
            oncleared: function () { self.setValue(''); }
        });
        @can('dokumen.transaksi')
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
        $("#noinv,#nopo,#nosc,#nobl,#noform").on("input", function(){
            $(".error." + $(this).attr("id")).hide();
        })
        $("#tglbl,#top").on("change", function(){
            var top = $("#top").val();
            if (top == ""){
                return false;
            }
            var term = 30;
            if (top == 1){
                term = 20;
            }
            else if (top == 3){
                term = 20;
            }
            else if (top == 5){
                term = 60;
            }
            else if (top == 6){
                term = 90;
            }
            var date = $('#tglbl').datepicker('getDate');
            if (!date){
                return false;
            }
            date.setDate(date.getDate() + term);
            $('#tgljatuhtempo').datepicker('setDate',date);
        });
        $("#btnsimpan").on("click", function(){
            //if (validate()){
                $(this).prop("disabled", true);
                $(".loader").show()
                var files = $("input[name=fileid]").map(function(index){
                    return {id: $(this).val(), jenisfile: $(".jenisfile").eq(index).find("option:selected").val()};
                }).get();
                $.ajax({
                    url: "/transaksi/crud",
                    data: {_token: "{{ csrf_token() }}", type: "userdo", header: $("#transaksi").serialize(), files: files},
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
                            $("#modal").on("hidden.bs.modal", function(){
                                window.location.reload();
                            });
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
        var numFiles = $("#listfiles tr").length;
        var maxFiles = 7;
        if (numFiles == maxFiles){
            $("div#dropzone").hide();
        }
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
        $("#listfiles a.delete").on("click",function(){
            $(this).closest("tr").remove();
            myDropzone.options.maxFiles = maxFiles - $("#listfiles tr").length;
            $("div#dropzone").show();
        });
        @endcan
    })
</script>
@endpush
