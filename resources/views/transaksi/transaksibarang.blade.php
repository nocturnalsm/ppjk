@extends('layouts.base')
@section('main')
<style>
    .error {display:none;font-size: 0.75rem;color: red};
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
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3">Seri Barang</label>
                        <div class="col-md-9 pt-2">
                            <span id="formseri"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="kodebarang">Kode Barang</label>
                        <div class="col-md-9">
                            <input type="text" id="kodebarang" name="kodebarang" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="uraian">Uraian Barang</label>
                        <div class="col-md-9">
                            <textarea rows="4" id="uraian" name="uraian" class="form-control form-control-sm validate"></textarea>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="nominal">Jumlah Kemasan</label>
                        <div class="col-md-9">
                            <input type="text" id="jmlkemasan" name="jmlkemasan" class="text-right number form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="satuan">Jenis Kemasan</label>
                        <div class="col-md-9 pt-2">
                            <select class="form-control form-control-sm" id="jeniskemasan" name="jeniskemasan">
                                <option value=""></option>
                                @foreach($jeniskemasan as $jenis)
                                <option @if($header->JENIS_KEMASAN == $jenis->JENISKEMASAN_ID) selected @endif value="{{ $jenis->JENISKEMASAN_ID }}">{{ $jenis->URAIAN }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="nominal">Jumlah Satuan Harga</label>
                        <div class="col-md-9">
                            <input type="text" id="jmlsatharga" name="jmlsatharga" class="text-right cifnumber form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="satuan">Satuan</label>
                        <div class="col-md-9 pt-2">
                        <select id="satuan" name="satuan" class="form-control">
                            @foreach($datasatuan as $satuan)
                            <option value="{{ $satuan->id }}">{{ $satuan->satuan }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="nominal">CIF</label>
                        <div class="col-md-9">
                            <input type="text" value="" id="cif" name="cif" class="text-right cifnumber form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="rupiah">Harga Satuan</label>
                        <div class="col-md-9">
                            <input type="text" readonly id="hargasatuan" name="hargasatuan" class="text-right cifnumber form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="nosptnp">No. SPTNP</label>
                        <div class="col-md-9">
                            <input type="text" id="nosptnp" name="nosptnp" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="tglsptnp">Tgl. SPTNP</label>
                        <div class="col-md-9">
                            <input type="text" id="tglsptnp" name="tglsptnp" class="datepicker form-control form-control-sm validate">
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
<div class="modal fade" id="modalupload" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Lampiran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
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
                <div class="row px-1 mt-2">
                    <div class="col-md-12 col-sm-12">
                        <table class="table table-bordered" data-id="" id="listfiles">
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <a data-detail="" id="saveupload" class="btn btn-primary">Simpan</a>
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
                    Form Perekaman Barang
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
            <input type="hidden" name="deletedetail" value="">
            <div class="row px-2">
                <div class="col-md-12 pt-0 col-sm-12">
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-1 col-form-label form-control-sm">Customer</label>
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" id="customer" name="customer" value="{{ $header->CUSTOMER }}">
                                            <option value=""></option>
                                            @foreach($customer as $cust)
                                            <option @if($header->CUSTOMER == $cust->id_customer) selected @endif value="{{ $cust->id_customer }}">{{ $cust->nama_customer }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm text-right">Importir</label>
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" id="importir" name="importir" value="{{ $header->IMPORTIR }}">
                                            <option value=""></option>
                                            @foreach($importir as $imp)
                                            <option @if($header->CONSIGNEE == $imp->IMPORTIR_ID)selected @endif value="{{ $imp->IMPORTIR_ID }}">{{ $imp->NAMA }}</option>
                                            @endforeach
                                        </select>
                                        <p class="error importir">Importir harus dipilih</p>
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-1 col-form-label form-control-sm">No. Inv</label>
                                    <div class="col-md-2">
                                        <input type="text" maxlength="24" class="form-control form-control-sm" name="noinv" id="noinv" value="{{ $header->NO_INV }}">
                                        <p class="error noinv">Nomor Inv harus diisi</p>
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">Tgl Inv</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglinv" value="{{ $header->TGL_INV }}" id="tglinv">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm text-right">Faktur</label>
                                    <div class="col-md-4">
                                        <input autocomplete="off" type="text" class="form-control form-control-sm" name="pengirim" value="{{ $header->PENGIRIM }}" id="pengirim">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-1 col-form-label form-control-sm">No. BL</label>
                                    <div class="col-md-2">
                                        <input type="text" maxlength="24" class="form-control form-control-sm" name="nobl" id="nobl" value="{{ $header->NO_BL }}">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">Tgl BL</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglbl" value="{{ $header->TGL_BL }}" id="tglbl">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm text-right">Jalur</label>
                                    <div class="form-check-inline d-inline mt-1">
                                        <label class="form-check-label pr-2">
                                            <input @if($header->JALUR == 'K') checked @endif type="radio" class="form-check-input" name="jalur" value="K">
                                            <span class="bg-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        </label>
                                        <label class="form-check-label pr-2">
                                            <input @if($header->JALUR == 'H') checked @endif type="radio" class="form-check-input" name="jalur" value="H">
                                            <span class="bg-success">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        </label>
                                        <label class="form-check-label pr-2">
                                            <input @if($header->JALUR == 'M') checked @endif type="radio" class="form-check-input" name="jalur" value="M">
                                            <span class="bg-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-1 col-form-label form-control-sm ">No. Form</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control form-control-sm" id="noform" name="noform" value="{{ $header->NO_FORM }}">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">Tgl Form</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglform" value="{{ $header->TGL_FORM }}" id="tglform">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-1 col-form-label form-control-sm ">No. LS</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control form-control-sm" id="nols" name="nols" value="{{ $header->NO_LS }}">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">Tgl LS</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglls" value="{{ $header->TGL_LS }}" id="tglls">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-1 col-form-label form-control-sm ">Tanggal SPPB</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ $header->TGL_SPPB }}" name="tglsppb" id="tglsppb">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm ">Tgl Keluar</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ $header->TGL_KELUAR }}" name="tglkeluar" id="tglkeluar">
                                    </div>
                                    <div class="col-md-1"></div>
                                    <label class="col-md-1 col-form-label form-control-sm">Tgl Terima</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglterimabrg" id="tglterimabrg" value="{{ $header->TGL_TERIMA }}">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-1 col-form-label form-control-sm">Jns Dokumen</label>
                                    <div class="col-md-2">
                                        <select class="form-control form-control-sm" id="jenisdokumen" name="jenisdokumen" value="{{ $header->JENIS_DOKUMEN }}">
                                            <option value=""></option>
                                            @foreach($jenisdokumen as $jenis)
                                            <option @if($header->JENIS_DOKUMEN == $jenis->JENISDOKUMEN_ID) selected @endif value="{{ $jenis->JENISDOKUMEN_ID }}">{{ $jenis->URAIAN }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">Nopen</label>
                                    <div class="col-md-2">
                                        <input maxlength="6" type="text" class="form-control form-control-sm" name="nopen" value="{{ $header->NOPEN }}" id="nopen">
                                        <p class="error nopen">Nopen 6 digit</p>
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">Tgl Nopen</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglnopen" value="{{ $header->TGL_NOPEN }}" id="tglnopen">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-1 col-form-label form-control-sm">No.Aju</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control form-control-sm" name="noaju" value="{{ $header->NOAJU }}" id="noaju">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">Tgl Aju</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglaju" value="{{ $header->TGL_AJU }}" id="tglaju">
                                    </div>
                                    <div class="col-md-1"></div>
                                    <label class="col-md-1 col-form-label form-control-sm">Jml Kemasan</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="number form-control form-control-sm" name="jumlahkemasan" value="{{ $header->JUMLAH_KEMASAN }}" id="jumlahkemasan">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-form-label col-md-1 form-control-sm" for="ukuran">Valuta</label>
                                    <div class="col-md-2">
                                    <select class="form-control form-control-sm" id="curr" name="curr">
                                        @foreach($matauang as $curr)
                                        <option @if($curr->MATAUANG_ID == $header->CURR) selected @endif value="{{ $curr->MATAUANG_ID }}">{{ $curr->MATAUANG }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                    <label class="col-form-label col-md-1 form-control-sm" for="ndpbm">NDPBM</label>
                                    <div class="col-md-2">
                                        <input type="text" class=" number form-control form-control-sm" name="ndpbm" value="{{ $header->NDPBM }}" id="ndpbm">
                                    </div>
                                    <label class="col-form-label col-md-1 form-control-sm" for="nilai">Nilai</label>
                                    <div class="col-md-2">
                                        <input type="text" class="cifnumber form-control form-control-sm" name="nilai" value="{{ $header->CIF }}" id="nilai">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-1 col-form-label form-control-sm ">BM</label>
                                    <div class="col-md-1">
                                        <input type="text" class="money form-control form-control-sm" id="bm" name="bm" value="{{ $header->BM }}">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">BMT</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="money form-control form-control-sm" name="bmt" value="{{ $header->BMT }}" id="bmt">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">PPN</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="money form-control form-control-sm" name="ppn" value="{{ $header->PPN }}" id="ppn">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">PPH</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="money form-control form-control-sm" name="pph" value="{{ $header->PPH }}" id="pph">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">Total</label>
                                    <div class="col-md-1">
                                        <input disabled autocomplete="off" type="text" class="money form-control form-control-sm" name="total" value="{{ $header->TOTAL }}" id="total">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">PPH Bebas</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="money form-control form-control-sm" name="pphbebas" value="{{ $header->PPH_BEBAS }}" id="pphbebas">
                                    </div>
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
                                <h5 class="card-title">Detail Barang</h5>
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
                                                    <th width="30%">Kode Barang<br>Uraian</th>
                                                    <th width="15%">Kemasan</th>
                                                    <th width="10%">Jml Sat Harga<br>Satuan</th>
                                                    <th width="12%">CIF</th>
                                                    <th width="12%">Harga Satuan</th>
                                                    <th width="11%">No.STPNP<br>Tgl.SPTNP</th>
                                                    <th width="10%">Opsi</th>
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
        </form>
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
        $('#modaldetail').on('shown.bs.modal', function () {
            $("#savedetail").removeClass("disabled");
            $('#kodebarang').focus();
        })
        function generate_uid(){
            return 'dt-' +Math.random().toString(36).substring(2,15) + Math.random().toString(36).substring(2,15);
        }
        $("#savedetail").on("click", function(){
            if ($("#kodebarang").val().trim() == ""){
                $("#modal .modal-body").html("Kode Barang Harus Diisi");
                $("#modal").modal("show");
                setTimeout(function(){
                    $("#modal").modal("hide");
                }, 5000);
                $("#kodebarang").focus();
                return false;
            }
            $(this).addClass("disabled");
            var seri = $("#formseri").html();
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
            var nosptnp = $("#nosptnp").val();
            var tglsptnp = $("#tglsptnp").val();
            var hargasatuan = $("#hargasatuan").inputmask('unmaskedvalue');
            var act = $("#form").attr("act");
            if (act == "add"){
                var data_id = generate_uid();
                tabel.row.add({ID: data_id, SERIBARANG: seri, KODEBARANG: kodebarang, URAIAN: uraian, SATUAN_ID: satuan, NAMASATUAN: namasatuan, JENISKEMASAN: jeniskemasan, NAMAJENISKEMASAN: namajenis, JMLKEMASAN: jmlkemasan, JMLSATHARGA: jmlsatharga, CIF: cif, HARGA: hargasatuan, NOSPTNP: nosptnp, TGLSPTNP: tglsptnp, files: []}).draw();
                $("#satuan").val("");
                $("#uraian").val("");
                $("#jeniskemasan").val("");
                $("#kodebarang").val("");
                $("#jmlkemasan").val("");
                $("#jmlsatharga").val("");
                $("#hargasatuan").val("");
                $("#nosptnp").val("");
                $("#tglsptnp").val("");
                var rowcount = ("00" + (tabel.rows().count() + 1)).substr(-2,2);
                $("#formseri").html(rowcount);

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
                var files = tabel.row(idx).data().files;
                tabel.row(idx).data({ID: id, KODEBARANG: kodebarang, SERIBARANG: seri, URAIAN: uraian, SATUAN_ID: satuan, NAMASATUAN: namasatuan, JENISKEMASAN: jeniskemasan, NAMAJENISKEMASAN: namajenis,JMLKEMASAN: jmlkemasan, JMLSATHARGA: jmlsatharga, CIF: cif, HARGA: hargasatuan,NOSPTNP: nosptnp, TGLSPTNP: tglsptnp, files: files}).draw();
                $("#modaldetail").modal("hide");
            }
            $(this).removeClass("disabled");
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
        $(".money").inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 0,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
        });
        $(".cifnumber").inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 3,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
        });
        var tabel = $("#griddetail").DataTable({
            processing: false,
            serverSide: false,
            data: datadetail,
            dom: "t",
            rowCallback: function(row, data)
            {        
                $('td:eq(0)', row).html('<div>' +data.KODEBARANG + "</div><div>" + (data.URAIAN || '&nbsp;') + "</div>");
                $('td:eq(1)', row).html(parseFloat(data.JMLKEMASAN).formatMoney(0,"",",",".") + " " +(data.NAMAJENISKEMASAN || ""));
                $('td:eq(2)', row).html('<div>' +parseFloat(data.JMLSATHARGA).formatMoney(2,"",",",".") + "</div><div>" + (data.NAMASATUAN || "&nbsp;") + "</div>");
                $('td:eq(4)', row).html(parseFloat(data.HARGA).formatMoney(3,"",",","."));
                $('td:eq(5)', row).html('<div>' +(data.NOSPTNP || '&nbsp;')+ "</div><div>" + (data.TGLSPTNP || "&nbsp;") + "</div>");
                var opsi = "";
                if (data.ID.toString().indexOf("dt-") == -1){
                    opsi += '<a title="Konversi" class="showkonversi" href="/transaksi/userkonversi/' + data.ID +'" data-id="' + data.ID +
                            '"><i class="fa fa-cog"></i></a>&nbsp;&nbsp;';
                }
                opsi +=  '<a title="Upload" class="uploadfile" href="#modalupload" data-toggle="modal" data-id="' + data.ID +
                        '"><i class="fa fa-upload"></i></a>&nbsp;&nbsp;' +
                        '<a title="Edit" href="#modaldetail" class="editdetail" data-toggle="modal" id="' + data.ID +
                        '"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;' +
                        '<a title="Hapus" class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>';

                $('td:eq(6)', row).html(opsi);
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
                data: "NOSPTNP"
            },
            { target: 6,
                data: null
            }
            ],
        })
        $("body").on("click", ".uploadfile", function(){
            var row = $(this).closest("tr");
            var index = tabel.row(row).index();
            var row = tabel.rows(index).data();
            var id = row[0].ID;
            var files = row[0].files;
            $("#saveupload").attr("data-detail", id);
            if (id.indexOf("dt-") == 0){
                myDropzone.options.maxFiles = maxFiles;
            }
            else {
                $("#listfiles").html("");
                if (files.length > 0){
                    $(files).each(function(index, elem){
                        $("#listfiles").append("<tr>" +
                                    '<td class="filename">' + elem.FILEREALNAME + '</td>' +
                                    '<td class="text-center">' +
                                        '<a href="#" class="delete" title="Hapus File">' +
                                            '<i class="fa fa-trash"></i>' +
                                        '</a>' +
                                        '<input type="hidden" value="' + elem.ID +'" class="fileid">' +
                                        '<a href="/transaksi/getfile?file=' + elem.ID +'" tile="Download File" class="download">' +
                                            '<i class="fa fa-download"></i>' +
                                        '</a>' +
                                    '</td>' +
                                '</tr>');
                    });
                }
                var numFiles = $("#listfiles tr").length;
                if (numFiles == maxFiles){
                    $("div#dropzone").hide();
                }
                myDropzone.options.maxFiles = maxFiles - $("#listfiles tr").length;
            }
        })
        $("#bm,#bmt,#ppn,#pph").on("change", function(){
            var bm  = parseInt($("#bm").val() != "" ? $("#bm").inputmask("unmaskedvalue") : 0);
            var bmt = parseInt($("#bmt").val() != "" ? $("#bmt").inputmask("unmaskedvalue") : 0);
            var ppn = parseInt($("#ppn").val() != "" ? $("#ppn").inputmask("unmaskedvalue") : 0);
            var pph = parseInt($("#pph").val() != "" ? $("#pph").inputmask("unmaskedvalue") : 0);
            $("#total").val(bm+bmt+ppn+pph);
        })
        $("#adddetail").on("click", function(){
            $("#satuan").val("");
            $("#kodebarang").val("");
            $("#jmlkemasan").val("");
            $("#jeniskemasan").val("");
            $("#jmlsatharga").val("");
            $("#hargasatuan").val("");
            $("#nosptnp").val("");
            $("#tglsptnp").val("");
            $("#uraian").val();
            var rowcount = ("00" + (tabel.rows().count() + 1)).substr(-2,2);
            $("#formseri").html(rowcount);
            var tglnopen = $("#tglnopen").val().replace(/9/g,'\\9').replace(/-/g,"");
            if (tglnopen == ""){
                tglnopen = "\\9\\9\\9\\9\\9\\9\\9\\9";
            }
            var nopen = $("#nopen").val().trim() != "" ? $("#nopen").val().replace(/9/g,'\\9') : "\\9\\9\\9\\9\\9\\9";
            $("#kodebarang").inputmask("999999" + "-" + nopen + "-" + tglnopen + "-" + rowcount.replace(/9/g,'\\9'));
            $("#modaldetail .modal-title").html("Tambah ");
            $("#form").attr("act","add");
            $("#tglsptnp").datepicker({dateFormat: "dd-mm-yy"});
        })
        $("body").on("click", ".editdetail", function(){
            var row = $(this).closest("tr");
            var index = tabel.row(row).index();
            var row = tabel.rows(index).data();
            $("#satuan").val(row[0].SATUAN_ID);
            $("#jeniskemasan").val(row[0].JENISKEMASAN);
            $("#formseri").html(row[0].SERIBARANG);
            $("#uraian").val(row[0].URAIAN);
            var kodebarang = row[0].KODEBARANG;
            kodebarang = kodebarang.substr(0,6);
            var tglnopen = $("#tglnopen").val().replace(/-/g,"").replace(/9/g,'\\9');
            if (tglnopen == ""){
                tglnopen = "\\9\\9\\9\\9\\9\\9\\9\\9";
            }
            var mask = kodebarang.substr(7);
            mask = mask.replace(/9/g,'\\9');
            var nopen = $("#nopen").val().trim() != "" ? $("#nopen").val().replace(/9/g,'\\9') : "\\9\\9\\9\\9\\9\\9";
            $("#kodebarang").inputmask("999999" + "-" + nopen + "-" + tglnopen + "-" + $("#formseri").html().replace(/9/g,'\\9'));
            $("#kodebarang").val(kodebarang);
            $("#jmlkemasan").val(row[0].JMLKEMASAN);
            $("#jmlsatharga").val(row[0].JMLSATHARGA);
            $("#cif").val(row[0].CIF);
            $("#tglsptnp").val(row[0].TGLSPTNP);
            $("#nosptnp").val(row[0].NOSPTNP);
            $("#hargasatuan").val(row[0].HARGA);
            $("#idxdetail").val(index);
            $("#iddetail").val(row[0].ID);
            $("#modaldetail .modal-title").html("Edit");
            $("#form").attr("act","edit");
            $("#tglsptnp").datepicker({dateFormat: "dd-mm-yy"});
        })
        $("body").on("click", ".del", function(){
            var row = $(this).closest("tr");
            var id = tabel.row(row).data().ID;
            if (typeof id != 'undefined'){
                $("input[name='deletedetail'").val($("input[name='deletedetail'").val() + id + ";");
            }
            var index = tabel.row(row).remove().draw();
        })
        $("#nopen").inputmask({"mask": "999999","removeMaskOnSubmit": true});

        $("#btnsimpan").on("click", function(e){
            $(this).prop("disabled", true);
            var detail = Array();
            var rows = tabel.rows().data();
            $(rows).each(function(index,elem){
                detail.push(elem);
            })
            $.ajax({
                url: "/transaksi/crud",
                data: {_token: "{{ csrf_token() }}", type: "barang", header: $("#transaksi").serialize(), detail: detail, /*files: files*/},
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
        $("body").on("change","#produk", function(){
            var selected = $(this).find("option:selected");
            if (selected.val() != ""){
                $("#formnamaproduk").html(selected.attr("namaproduk"));
                $("#harga").val(selected.attr("harga"));
            }
            else {
                $("#formnamaproduk").html("");
                $("#kodebarang").val("");
            }
        })
        $("#cif,#jmlsatharga").on("change", function(){
            var jmlsatharga = $("#jmlsatharga").val().replace(/,/g,"");
            var cif = $("#cif").val().replace(/,/g,"");
            var hargasatuan = parseFloat(cif)/parseFloat(jmlsatharga);
            hargasatuan = hargasatuan.toFixed(3).replace(/\d(?=(\d{3})+\.)/g, "$&,");
            $("#hargasatuan").val(hargasatuan);
        })
        var maxFiles = 2;
        var numFiles = $("#listfiles tr").length;
        if (numFiles == maxFiles){
            $("div#dropzone").hide();
        }
        var myDropzone = new Dropzone("#dropzone", {
            url: "/transaksi/upload",
            uploadMultiple: false,
            maxFilesize: 2,
            previewsContainer: "#preview-container",
            previewTemplate: $("#template").html(),
            acceptedFiles: ".xls, .xlsx",
            init:function(){
                var self = this;
                // config
                self.options.addRemoveLinks = true;
                self.options.dictRemoveFile = "Hapus";
                self.on("success", function(file, response) {
                    var value = JSON.parse(response);
                    $(file.previewElement).append('<input type="hidden" name="fileid" value="' + value.id + '">');
                })
                // On removing file
                self.on("removedfile", function (file) {
                    var hidden = $(file.previewElement).find("input[name=fileid]").val();
                    if (hidden){
                        $.ajax({
                            url: "/transaksi/removefile",
                            data: {id: hidden},
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
            }
        });
        $("#listfiles a.delete").on("click",function(){
            $(this).closest("tr").remove();
            myDropzone.options.maxFiles = maxFiles - $("#listfiles tr").length;
            $("div#dropzone").show();
        });
        $("#saveupload").on("click", function(){
            var id = $(this).attr("data-detail");
            var row = $(".uploadfile[data-id=" + id +"]").closest("tr");
            var index = tabel.row(row).index();
            var data = tabel.row(index).data();
            var files = [];
            $("#listfiles tr").each(function(index,elem){
                files.push({id: $(elem).find(".fileid"), filename: $(elem).find(".filename")});
            });
            var newfiles = myDropzone.files;
            $(newfiles).each(function(index,elem){
                var file_id = $(elem.previewElement).find("input[name=fileid]").val();
                var file_name = $(elem.previewElement).find(".data-dz-name").html();
                files.push({id: file_id, filename: file_name});
            })
        });
    })
</script>
@endpush
