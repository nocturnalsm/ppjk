@extends('layouts.base')
@section('main')
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
                    Form Perekaman Bongkar
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <div class="col-md-12">
              <input type="hidden" value="{{ $header->ID }}" id="idtransaksi" name="idtransaksi">
              <div class="row px-2">
                  <div class="col-md-7 pt-0 col-sm-12">
                      <div class="row">
                          <div class="col-md-12 p-0 mb-4">
                              <div class="form-row px-2 pb-0">
                                  <label class="col-md-2 col-form-label form-control-sm">Importir</label>
                                  <label class="col-md-4 col-form-label form-control-sm">{{ $header->NAMAIMPORTIR }}</label>
                              </div>
                              <div class="form-row px-2 pb-0">
                                  <label class="col-md-2 col-form-label form-control-sm">No. Aju</label>
                                  <label class="col-md-2 col-form-label form-control-sm">{{ $header->NOAJU}}</label>
                                  <label class="col-md-1 col-form-label form-control-sm">Nopen</label>
                                  <label class="col-md-2 col-form-label form-control-sm">{{ $header->NOPEN }}</label>
                                  <label class="col-md-2 col-form-label text-right form-control-sm">Tgl Nopen</label>
                                  <label class="col-md-2 col-form-label form-control-sm">{{ $header->TGLNOPEN }}</label>
                              </div>
                              <div class="form-row px-2 pb-0">
                                  <label class="col-md-2 col-form-label form-control-sm">Gudang</label>
                                  <div class="col-md-3">
                                      <label class="col-form-label form-control-sm px-0">{{ $header->NAMAGUDANG }}</label>
                                  </div>
                              </div>
                              <div class="form-row px-2">
                                  <label class="col-md-2 col-form-label form-control-sm">Tgl Bongkar</label>
                                  <div class="col-md-2">
                                      <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglbongkar" value="{{ $header->TGLBONGKAR }}" id="tglbl">
                                  </div>
                              </div>
                              <div class="form-row px-2">
                                  <label class="col-md-2 col-form-label form-control-sm">Hasil Bongkar</label>
                                  <div class="col-md-2">
                                      <select class="form-control form-control-sm" id="hasilbongkar" name="hasilbongkar" value="{{ $header->HASIL_BONGKAR }}">
                                          <option @if(!$header->HASIL_BONGKAR || $header->HASIL_BONGKAR == '') selected @endif value=""></option>
                                          <option @if($header->HASIL_BONGKAR == 'S') selected @endif value="S">Sesuai</option>
                                          <option @if($header->HASIL_BONGKAR == 'K') selected @endif value="K">Kurang</option>
                                          <option @if($header->HASIL_BONGKAR == 'L') selected @endif value="L">Lebih</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-row px-2">
                                  <label class="col-md-2 col-form-label form-control-sm ">Catatan</label>
                                  <div class="col-md-6">
                                      <textarea rows="4" class="form-control form-control-sm" id="catatan" name="catatan">{{ $header->CATATAN }}</textarea>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-5 col-sm-12">
                      <h6 class="card-title">Upload BA dan Foto</h6>
                      <div class="card-body py-0">
                          <div class="row">
                            <div id="dropzone" class="p-4 border">
                                <div class="dz-message needsclick">
                                    Drag file ke kotak di bawah ini untuk meng-upload atau klik untuk memilih file Excel (.xls | .xlsx | .pdf | .jpg).<br>
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
            </div>
            <div class="row px-2">
                <div class="col-md-12">
                    <div class="row mb-2">
                        <div class="card col-md-12 p-0">
                            <div class="card-body p-3">
                                <div class="form-row">
                                    <div class="col primary-color text-white py-2 px-4">
                                        Detail Barang
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mt-2">
                                        <table width="100%" id="griddetail" class="table">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" width="30%">Kode Barang</th>
                                                    <th rowspan="2" width="15%">Satuan</th>
                                                    <th class="text-center" colspan="2" width="25%">Data PIB</th>
                                                    <th class="text-center" colspan="2" width="25%">Hsl Bongkar</th>
                                                </tr>
                                                <tr>
                                                    <th width="10%">Jml Kms</th>
                                                    <th width="15%">Jml Sat Harga</th>
                                                    <th width="10%">Jml Kms Bkr</th>
                                                    <th width="15%">Jml Sat Harga Bkr</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              @if($header->detail->count() > 0)
                                              @foreach($header->detail as $detail)
                                              <input type="hidden" name="iddetail[]" value="{{ $detail->ID }}">
                                              <input type="hidden" name="idheader[]" value="{{ $detail->ID_HEADER }}">
                                              <input type="hidden" name="kodebarang[]" value="{{ $detail->KODEBARANG }}">
                                              <tr>
                                                  <td>{{ $detail->KODEBRG}}</td>
                                                  <td>{{ $detail->satuan }}</td>
                                                  <td>{{ $detail->JMLKEMASAN }}</td>
                                                  <td>{{ $detail->JMLSATHARGA }}</td>
                                                  <td>
                                                    <input type="text" class="number form-control form-control-sm" autocomplete="off" name="kmsbkr[]" value="{{ $detail->JMLKEMASANBONGKAR }}">
                                                  </td>
                                                  <td>
                                                    <input type="text" class="number form-control form-control-sm" autocomplete="off" name="satbkr[]" value="{{ $detail->JMLSATHARGABONGKAR }}">
                                                  </td>
                                              </tr>
                                              @endforeach
                                              @else
                                                <tr>
                                                  <td colspan="6" class="text-center">Tidak ada data</td>
                                                </tr>
                                              @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<script type="text/template" id="template">
<div class="file-row row border">
    <div class="col-md-9">
        <span class="name d-block" data-dz-name></span>
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
    <link href="{{ asset('jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
@endpush
@push('scripts_end')
<script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dropzone.min.js') }}"></script>
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
        });
        $(".money").inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 0,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
        });
        var numFiles = $("#listfiles tr").length;
        var maxFiles = 4;
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
                    formData.append("filetype", 3);
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
        $("#btnsimpan").on("click", function(e){
            $(this).prop("disabled", true);
            var files = $("input[name=fileid]").map(function(index){
                return {id: $(this).val()};
            }).get();
            $.ajax({
                url: "/gudang/crud",
                data: {_token: "{{ csrf_token() }}", type: "bongkar", header: $("#transaksi").serialize(), files: files},
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
        })
    })
</script>
@endpush
