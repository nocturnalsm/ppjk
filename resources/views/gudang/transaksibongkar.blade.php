@extends('layouts.base')
@section('main')
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
            <input type="hidden" value="{{ $header->ID }}" id="idtransaksi" name="idtransaksi">
            <div class="row px-2">
                <div class="col-md-12 pt-0 col-sm-12">
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-1 col-form-label form-control-sm">Importir</label>
                                    <label class="col-md-4 col-form-label form-control-sm">{{ $header->NAMAIMPORTIR }}</label>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-1 col-form-label form-control-sm">No. Aju</label>
                                    <label class="col-md-2 col-form-label form-control-sm">{{ $header->NOAJU}}</label>
                                    <label class="col-md-1 col-form-label form-control-sm">Nopen</label>
                                    <label class="col-md-2 col-form-label form-control-sm">{{ $header->NOPEN }}</label>
                                    <label class="col-md-1 col-form-label text-right form-control-sm">Tgl Nopen</label>
                                    <label class="col-md-2 col-form-label form-control-sm">{{ $header->TGLNOPEN }}</label>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-1 col-form-label form-control-sm">Tgl Bongkar</label>
                                    <div class="col-md-1">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglbongkar" value="{{ $header->TGLBONGKAR }}" id="tglbl">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-1 col-form-label form-control-sm">Gudang</label>
                                    <div class="col-md-3">
                                        <select class="form-control form-control-sm" id="gudang" name="gudang" value="{{ $header->GUDANG_ID }}">
                                            <option value=""></option>
                                            @foreach($datagudang as $gudang)
                                            <option @if($header->GUDANG_ID == $gudang->GUDANG_ID) selected @endif value="{{ $gudang->GUDANG_ID }}">{{ $gudang->URAIAN }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-1 col-form-label form-control-sm">Hasil Bongkar</label>
                                    <div class="col-md-1">
                                        <select class="form-control form-control-sm" id="hasilbongkar" name="hasilbongkar" value="{{ $header->HASIL_BONGKAR }}">
                                            <option @if(!$header->HASIL_BONGKAR || $header->HASIL_BONGKAR == '') selected @endif value=""></option>
                                            <option @if($header->HASIL_BONGKAR == 'S') selected @endif value="S">Sesuai</option>
                                            <option @if($header->HASIL_BONGKAR == 'K') selected @endif value="K">Kurang</option>
                                            <option @if($header->HASIL_BONGKAR == 'L') selected @endif value="L">Lebih</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-1 col-form-label form-control-sm ">Catatan</label>
                                    <div class="col-md-4">
                                        <textarea rows="4" class="form-control form-control-sm" id="catatan" name="catatan">{{ $header->CATATAN }}</textarea>
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
        @csrf
        <input type="hidden" name="type" value="bongkar">
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
    <link href="{{ asset('jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
@endpush
@push('scripts_end')
<script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
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
        });
        $(".money").inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 0,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
        });

        $("#btnsimpan").on("click", function(e){
            $(this).prop("disabled", true);
            $.ajax({
                url: "/gudang/crud",
                data: $("#transaksi").serialize(),
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
