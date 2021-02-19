@extends('layouts.base')
@section('main')
<style>
    .error {display:none;font-size: 0.75rem;color: red};
</style>
<div class="row">
    <div class="card col-md-12 p-0">
        <div class="card-header font-weight-bold">
            <div class="row">
                <div class="col-md-4 py-0 pl-4 mt-1">
                    Form Perekaman SPTNP {{ $notransaksi }}
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
            <div class="form-row px-2">
                <label class="col-md-2 form-control-sm">Kantor</label>
                <div class="col-md-2">
                    <select class="form-control form-control-sm" id="kantor" name="kantor" value="{{ $header->KANTOR_ID }}">
                        <option value=""></option>
                        @foreach($kodekantor as $kantor)
                        <option @if($header->KANTOR_ID == $kantor->KANTOR_ID)selected @endif value="{{ $kantor->KANTOR_ID }}">{{ $kantor->URAIAN }}</option>
                        @endforeach
                    </select>
                    <p class="error kantor">Kode Kantor harus dipilih</p>
                </div>
                <label class="col-md-1 form-control-sm text-right">Importir</label>
                <div class="col-md-4">
                    <select class="form-control form-control-sm" id="importir" name="importir" value="{{ $header->IMPORTIR }}">
                        <option value=""></option>
                        @foreach($importir as $imp)
                        <option @if($header->IMPORTIR == $imp->IMPORTIR_ID)selected @endif value="{{ $imp->IMPORTIR_ID }}">{{ $imp->NAMA }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row px-2">
                <label class="col-md-2 col-form-label form-control-sm">No. SPTNP</label>
                <div class="col-md-2">
                    <input type="text" maxlength="24" class="form-control form-control-sm" name="nosptnp" id="nosptnp" value="{{ $header->NO_SPTNP }}">
                </div>
                <label class="col-md-1 col-form-label form-control-sm text-right">Tgl SPTNP</label>
                <div class="col-md-1">
                    <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglsptnp" value="{{ $header->TGL_SPTNP }}" id="tglsptnp">
                </div>
            </div>
            <div class="form-row px-2">
              <label class="col-md-2 col-form-label form-control-sm">Nopen</label>
              <div class="col-md-2">
                  <input maxlength="6" type="text" class="form-control form-control-sm" name="nopen" value="{{ $header->NOPEN }}" id="nopen">
                  <p class="error nopen">Nopen 6 digit</p>
              </div>
              <label class="col-md-1 col-form-label form-control-sm text-right">Tgl Nopen</label>
              <div class="col-md-1">
                  <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglnopen" value="{{ $header->TGL_NOPEN }}" id="tglnopen">
              </div>
              <label class="col-md-auto col-form-label form-control-sm">No.Aju</label>
              <div class="col-md-1">
                  <input type="text" maxlength="6" class="form-control form-control-sm" name="noaju" value="{{ $header->NOAJU }}" id="noaju">
              </div>
            </div>
            <div class="row px-2">
                <div class="col-md-6 pt-4">
                    <div class="row pr-4">
                        <div class="card col-md-12 p-0  mb-2">
                            <div class="card-body p-3">
                                <h5 class="card-title">Tagihan SPTNP</h5>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-2 col-form-label form-control-sm">BM</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="number form-control form-control-sm" name="bmtb" value="{{ $header->BMTB }}" id="bmtb">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">BM KITE</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="number form-control form-control-sm" name="bmkite" value="{{ $header->BMKITE }}" id="bmkite">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-2 col-form-label form-control-sm">BMT</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="number form-control form-control-sm" name="bmttb" value="{{ $header->BMTTB }}" id="bmttb">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">PPN</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="number form-control form-control-sm" name="ppntb" value="{{ $header->PPNTB }}" id="ppntb">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-2 col-form-label form-control-sm">PPNBM</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="number form-control form-control-sm" name="ppnbm" value="{{ $header->PPNBM }}" id="ppnbm">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">PPH Pasal 22</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="number form-control form-control-sm" name="pphtb" value="{{ $header->PPHTB }}" id="pphtb">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-2 col-form-label form-control-sm">Denda</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="number form-control form-control-sm" name="dendatb" value="{{ $header->DENDA_TB }}" id="dendatb">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Total</label>
                                    <div class="col-md-3">
                                        <input readonly autocomplete="off" type="text" class="number form-control form-control-sm" name="totaltb" value="{{ $header->TOTAL_TB }}" id="totaltb">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-2 col-form-label form-control-sm">Jenis SPTNP</label>
                                    <div class="col-md-3">
                                        <select class="form-control form-control-sm" id="jenissptnp" name="jenissptnp" value="{{ $header->JENIS_SPTNP }}">
                                            <option @if($header->JENIS_SPTNP == "")selected @endif value=""></option>
                                            <option @if($header->JENIS_SPTNP == "NP")selected @endif value="NP">NP</option>
                                            <option @if($header->JENIS_SPTNP == "NP+FORM")selected @endif value="NP+FORM">NP+FORM</option>
                                            <option @if($header->JENIS_SPTNP == "FORM")selected @endif value="FORM">FORM</option>
                                            <option @if($header->JENIS_SPTNP == "BMT")selected @endif value="BMT">BMT</option>
                                        </select>
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Jth Tempo</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tgljthtemposptnp" value="{{ $header->TGL_JATUH_TEMPO_SPTNP }}" id="tgljthtemposptnp">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pt-4">
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <h5 class="card-title">Data Keberatan/Pelunasan</h5>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Lunas</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tgllunas" value="{{ $header->TGL_LUNAS }}" id="tgllunas">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl BRT</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglbrt" value="{{ $header->TGL_BRT }}" id="tglbrt">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-2 col-form-label form-control-sm">Hasil BRT</label>
                                    <div class="col-md-3">
                                        <select class="form-control form-control-sm" id="hslbrt" name="hslbrt" value="{{ $header->HSL_BRT }}">
                                            <option @if($header->HSL_BRT == "")selected @endif value=""></option>
                                            <option @if($header->HSL_BRT == "TRM")selected @endif value="TRM">TRM</option>
                                            <option @if($header->HSL_BRT == "TLK")selected @endif value="TLK">TLK</option>
                                            <option @if($header->HSL_BRT == "SBGN")selected @endif value="SBGN">SBGN</option>
                                        </select>
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">No. Kep Berat</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" maxlength="6" class="form-control form-control-sm" name="nokepbrt" value="{{ $header->NO_KEPBRT }}" id="nokepbrt">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Kep Berat</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglkepbrt" value="{{ $header->TGL_KEPBRT }}" id="tglkepbrt">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Jth Tempo Bdg</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tgljthtmpbdg" value="{{ $header->TGL_JTHTEMPO_BDG }}" id="tgljthtmpbdg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row px-2">
                <div class="col-md-8 pt-4">
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <h5 class="card-title">Data Banding</h5>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">No Bdg</label>
                                    <div class="col-md-2">
                                        <input type="text" maxlength="4" class="form-control form-control-sm" name="nobdg" value="{{ $header->NO_BDG }}" id="nobdg">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">Tgl Bdg</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglbdg" value="{{ $header->TGL_BDG }}" id="tglbdg">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">Majelis</label>
                                    <div class="col-md-2">
                                        <input type="text" maxlength="2" class="form-control form-control-sm" name="majelis" value="{{ $header->MAJELIS }}" id="majelis">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">SDG.01</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="sdg01" value="{{ $header->SDG01 }}" id="sdg01">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">SDG.02</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="sdg02" value="{{ $header->SDG02 }}" id="sdg02">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">SDG.03</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="sdg03" value="{{ $header->SDG03 }}" id="sdg03">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">SDG.04</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="sdg04" value="{{ $header->SDG04 }}" id="sdg04">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">SDG.05</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="sdg05" value="{{ $header->SDG05 }}" id="sdg05">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm">SDG.06</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="sdg06" value="{{ $header->SDG06 }}" id="sdg06">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">SDG.07</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="sdg07" value="{{ $header->SDG05 }}" id="sdg05">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">Hasil Bdg</label>
                                    <div class="col-md-5">
                                        <textarea rows="3" class="form-control form-control-sm" name="hasilbdg" id="hasilbdg">{{ $header->HASIL_BDG }}</textarea>
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">No Kep Bdg</label>
                                    <div class="col-md-2">
                                        <input type="text" maxlength="6" class="form-control form-control-sm" name="nokepbdg" value="{{ $header->NO_KEP_BDG }}" id="nokepbdg">
                                    </div>
                                    <label class="col-md-1 col-form-label form-control-sm pr-0">Tgl Kep Bdg</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglkepbdg" value="{{ $header->TGL_KEP_BDG }}" id="tglkepbdg">
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
    oncleared: function () { self.setValue(''); }
});
$("#btnsimpan").on("click", function(){
        $(this).prop("disabled", true);
        $(".loader").show()
        $.ajax({
            url: "/transaksi/crud",
            data: {header: $("#transaksi").serialize(), type: "usersptnp", _token: "{{ csrf_token() }}"},
            type: "POST",
            success: function(msg) {
                if (typeof msg.error != 'undefined'){
                    $("#modal .modal-body").html(msg.error);
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 3000);
                }
                else {
                    $("#modal .modal-body").html("Penyimpanan berhasil");
                    $('#modal').on('hidden.bs.modal', function (e) {
                        if ($("#idtransaksi").val().trim() == ""){
                            document.location.href = "/transaksi/usersptnp";
                        }
                        else {
                            document.location.reload();
                        }
                    })
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 3000);
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
$("#bmtb,#bmttb,#bmkite,#ppnbm,#ppntb,#pphtb,#dendatb").on("change", function(){
    var bmtb = parseFloat($("#bmtb").inputmask("unmaskedvalue")) || 0;
    var bmttb = parseFloat($("#bmttb").inputmask("unmaskedvalue")) || 0;
    var ppntb = parseFloat($("#ppntb").inputmask("unmaskedvalue")) || 0;
    var pphtb = parseFloat($("#pphtb").inputmask("unmaskedvalue")) || 0;
    var ppnbm = parseFloat($("#ppnbm").inputmask("unmaskedvalue")) || 0;
    var bmkite = parseFloat($("#bmkite").inputmask("unmaskedvalue")) || 0;
    var dendatb = parseFloat($("#dendatb").inputmask("unmaskedvalue")) || 0;
    $("#totaltb").val(bmtb+bmttb+ppntb+pphtb+dendatb+bmkite+ppnbm);
})
$("#tglsptnp").on("change", function(){
    if ($("#tglsptnp").val().trim() == ""){
        $("#tgljthtemposptnp").val("");
    }
    else {
      var date = $(this).datepicker( "getDate");
      date.setDate(date.getDate()+60);
      $("#tgljthtemposptnp").datepicker("setDate", date);
    }
})
$("#tglkepbrt").on("change", function(){
    if ($("#tglkepbrt").val().trim() == ""){
        $("#tgljthtmpbdg").val("");
    }
    else {
      var date = $(this).datepicker( "getDate");
      date.setDate(date.getDate()+60);
      $("#tgljthtmpbdg").datepicker("setDate", date);
    }
})

})
</script>
@endpush
