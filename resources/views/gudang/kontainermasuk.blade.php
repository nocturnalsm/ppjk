@extends('layouts.base')
@section('main')
    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                          <div class="input-group">
                              <input type="text" class="form-control" id="term" name="term" placeholder="Masukkan Nomor Kontainer">
                              <div class="input-group-append">
                                  <button id="btnsearch" class="btn btn-primary m-0 px-3" type="button">
                                  <i class="fa fa-search"></i>
                                  </button>
                              </div>
                          </div>
                        </div>
                    </div>
                    <div class="form-row pt-4 pb-2">
                      <label class="col-md-2">Importir</label>
                      <div class="col-md-6 col-sm-12">
                          <span id="namaimportir"></span>
                      </div>
                    </div>
                    <div class="form-row pt-2 pb-4">
                      <label class="col-md-2">No Aju</label>
                      <div class="col-md-2">
                          <span id="noaju"></span>
                      </div>
                      <label class="col-md-2">Nopen</label>
                      <div class="col-md-2">
                          <span id="nopen"></span>
                      </div>
                      <label class="col-md-2">Tanggal</label>
                      <div class="col-md-2">
                          <span id="tglform"></span>
                      </div>
                    </div>
                    <form id="formkontainer" method="POST">
                    <div class="form-row">
                      <label class="col-md-2">No Pol</label>
                      <div class="col-md-3">
                          <input type="text" class="form-control" name="nopol" id="nopol" value="">
                      </div>
                    </div>
                    <div class="form-row">
                      <label class="col-md-2">Gudang</label>
                      <div class="col-md-4">
                          <select class="form-control form-control" id="gudang" name="gudang">
                              <option value=""></option>
                              @foreach($datagudang as $gud)
                              <option value="{{ $gud->GUDANG_ID }}">{{ $gud->URAIAN }}</option>
                              @endforeach
                          </select>
                      </div>
                    </div>
                    <div class="form-row">
                      <label class="col-md-2">Tgl Masuk</label>
                      <div class="col-md-3">
                          <input type="text" autocomplete="off" class="datepicker form-control" name="tglmasuk" id="tglmasuk" value="">
                      </div>
                    </div>
                    <input type="hidden" name="idkontainer" id="idkontainer" value="">
                    <input type="hidden" name="type" id="type" value="kontainermasuk">
                    </form>
                    <div class="form-row pt-4">
                      <div class="col-md-12">
                          <button type="button" id="savebutton" class="btn btn-primary disabled">Simpan</button>
                          <button type="button" id="cancelbutton" class="btn btn-warning disabled">Batal</button>
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
<script type="text/javascript" src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<script>
    $(function(){
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
        $("#btnsearch").on("click", function(){
            $(this).addClass("disabled");
            var nomor = $("#term").val().trim();
            if (nomor != ""){
              $.ajax({
                  type: "POST",
                  url: "/gudang/kontainermasuk",
                  data: {_token:"{{ csrf_token() }}", nokontainer: nomor},
                  success: function(response){
                      if (typeof response.error == 'undefined'){
                          $("#namaimportir").html(response.NAMAIMPORTIR);
                          $("#idkontainer").val(response.ID);
                          $("#noaju").html(response.NOAJU);
                          $("#nopen").html(response.NOPEN);
                          $("#tglform").html(response.TGL_NOPEN);
                          if (typeof response.detail != 'undefined'){
                              $("#nopol").val(response.detail.NOPOL);
                              $("#gudang").val(response.detail.GUDANG_ID);
                              $("#tglmasuk").val(response.detail.TGL_MASUK);
                          }
                          else {
                              $("#nopol").val("");
                              $("#gudang").val("");
                              $("#tglmasuk").val("");
                          }
                          $("#savebutton").removeClass("disabled");
                          $("#cancelbutton").removeClass("disabled");
                      }
                      else {
                          $("#modal .modal-body").html(response.error);
                          $("#modal").modal("show");
                      }
                      $("#btnsearch").removeClass("disabled");
                  }
              })
            }
            else {
                $(this).removeClass("disabled");
            }
        })
        $("#savebutton").on("click", function(){
            $(this).addClass("disabled");
            $.ajax({
                url: '/gudang/crud',
                type: "POST",
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                data: $("#formkontainer").serialize(),
                success: function(msg){
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
                          $("#modal").modal("hide");
                      }, 5000);
                  }
                  $("#savebutton").removeClass("disabled");
                }
            })
        })
        $("#cancelbutton").on("click", function(){
            $("#formkontainer")[0].reset();
            $("#idkontainer").val("");
            $("#namaimportir").html("");
            $("#noaju").html("");
            $("#nopen").html("");
            $("#tglform").html("");
            $("#term").val("");
        })
    });
</script>
@endpush
