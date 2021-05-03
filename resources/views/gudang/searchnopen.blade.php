@extends('layouts.base')
@section('main')
    <form id="formsearch">
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="card card-sm">
                    <div class="card-header">
                        Cari Transaksi Berdasarkan Nopen
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" class="form-control" id="term" name="term" placeholder="Masukkan Nopen">
                            <div class="input-group-append">
                                <button id="btnsearch" class="btn btn-primary m-0 px-3" type="button">
                                <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts_end')
<script>
$(function(){
    $("#btnsearch").on("click", function(){
        $("#formsearch").submit();
    })
    $("#formsearch").on("submit", function(e){
        e.preventDefault();
        $(".loader").show()
        $.ajax({
            url: "/gudang/find",
            data: $("#formsearch").serialize(),
            type: "GET",
            success: function(msg) {
                if (typeof msg.error != 'undefined'){
                    $("#modal .modal-body").html(msg.error);
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 1000);
                }
                else {
                      window.location.href = "/gudang/" + msg.ID;
                }
            },
            complete: function(){
                $(".loader").hide();
            }
        });
    })
})
</script>
@endpush
