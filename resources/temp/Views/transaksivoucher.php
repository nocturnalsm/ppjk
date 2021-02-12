{% extends "base.html.twig" %}
{% block body %}
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
                        <input type="hidden" name="deletedetail">
                        <div class="form-row mb-1">               
                            <label class="col-form-label col-md-3" for="uraian">Uraian</label>
                            <div class="col-md-9">
                                <input type="text" id="uraian" name="uraian" class="form-control form-control-sm validate">                        
                            </div>
                        </div>
                        <div class="form-row mb-1">               
                            <label class="col-form-label col-md-3" for="noinvbiaya">No.Inv.Biaya</label>
                            <div class="col-md-9">
                                <input type="text" id="noinvbiaya" name="noinvbiaya" class="form-control form-control-sm validate">                        
                            </div>
                        </div>
                        <div class="form-row mb-1">                        
                            <label class="col-form-label col-md-3" for="tglinvbiaya">Tgl Inv Biaya</label>
                            <div class="col-md-9">
                                <input type="text" id="tglinvbiaya" name="tglinvbiaya" class="datepicker form-control form-control-sm validate">                        
                            </div>
                        </div>
                        <div class="form-row mb-1">                        
                            <label class="col-form-label col-md-3" for="kurs">DPP</label>
                            <div class="col-md-9">
                                <input type="text" id="dpp" name="dpp" class="number form-control form-control-sm validate">                        
                            </div>
                        </div>
                        <div class="form-row mb-1">                        
                            <label class="col-form-label col-md-3" for="nominal">% Tax</label>
                            <div class="col-md-9">
                                <input type="text" id="tax" name="tax" class="number form-control form-control-sm validate">                        
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
    <div class="card-header font-weight-bold">
        <div class="row">
            <div class="col-md-6 py-0 pl-4 mt-1">
                <div class="row">
                    <span class="mt-1">Form Pengajuan Voucher - No. Voucher</span>
                    <div class="col-md-4">
                        {{ notransaksi }}
                    </div>
                </div>
            </div>
            <div class="col-md-6 py-0 pr-4 text-right">
                <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="form-row px-2 mt-4">
            <input type="hidden" name="do_id" id="do_id" value="">
            <label class="control-label col-md-2 form-control-sm">Tgl Voucher</label>
            <div class="col-md-1">
                <input disabled class="form-control form-control-sm" type="text" disabled id="tglvoucher" name="tglvoucher">
            </div>
        </div>
        <div class="form-row px-2 pb-1">                    
            <label class="col-md-2 col-form-label form-control-sm">No. BL</label>
            <div class="col-md-1">
                <input disabled type="text" class="form-control form-control-sm" name="nobl" id="nobl">
            </div>              
        </div>
        <div class="form-row px-2 pb-1">                    
            <div class="col-md-1"></div>                       
            <label class="col-md-2 col-form-label form-control-sm">Importir</label>                   
            <div class="col-md-1">                                                            
                <input disabled autocomplete="off" type="text" class="form-control form-control-sm" name="importir" id="importir">
            </div>
            <label class="col-md-2 col-form-label form-control-sm">Customer</label>
            <div class="col-md-2">
                <input disabled type="text" class="form-control form-control-sm" name="customer" id="customer">             
            </div>
        </div>
        <div class="form-row px-2 pb-1">
            <label class="col-md-2 col-form-label form-control-sm">No Inv</label>
            <div class="col-md-2">
                <input disabled type="text" class="form-control form-control-sm" name="noinv" id="noinv">             
            </div>                                          
            <label class="col-md-2 col-form-label form-control-sm">No. Kontainer</label>
            <div class="col-md-4">
                <input disabled type="text" class="form-control form-control-sm" name="nokontainer" id="nokontainer">
            </div>
        </div>
        <div class="form-row px-2 pb-1">
            <label class="col-md-2 col-form-label form-control-sm">No. Aju</label>
            <div class="col-md-2">
                <input disabled type="text" class="form-control form-control-sm" name="noaju" id="noaju">
            </div>
            <label class="col-md-2 col-form-label form-control-sm">Nopen</label>
            <div class="col-md-2">
                <input disabled type="text" class="form-control form-control-sm" name="nopen" id="nopen">
            </div>
            <label class="col-md-2 col-form-label form-control-sm">Tgl.Nopen</label>
            <div class="col-md-2">
                <input disabled type="text" class="form-control form-control-sm" name="tglnopen" id="tglnopen">
            </div>
        </div>
        <div class="form-row px-2 pb-1">
            <label class="col-md-2 col-form-label form-control-sm">Jml Kemasan</label>
            <div class="col-md-2">
                <input disabled type="text" class="number form-control form-control-sm" name="jmlkemasan" id="jmlkemasan">             
            </div>                                          
            <label class="col-md-2 col-form-label form-control-sm">No. Kontainer</label>
            <div class="col-md-4">
                <input disabled type="text" class="number form-control form-control-sm" name="totalbiaya" id="totalbiaya">
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="form-row">
                <div class="col primary-color text-white py-2 px-4">
                    Detail Biaya
                </div>
                <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                    <a href="#modaldetail" data-toggle="modal" class="text-white" id="adddetail">Tambah Detail</a>
                </div>                            
            </div>
        </div>
        <div class="col-md-12 pt-4">
            <table width="100%" id="gridtransaksi" class="table">
                <thead>
                    <tr>
                        <th>Tgl Muat</th>
                        <th>No SJ</th>
                        <th>No. Pol</th>
                        <th>Driver</th>                        
                        <th>Jml Kms Muat</th>
                        <th>Remarks</th>   
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
{% endblock %}
{% block scripts %}

$(function(){
    
$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});

$("#no_do").on("change", function(e){
    e.preventDefault();
    var nodo = $(this).val().trim();
    if (nodo != ""){
        $(".loader").show();
        $.ajax({
            url: "/transaksi/searchdo",
            method: "POST",
            data: {no_do: nodo},
            success: function(response){
                if (typeof response.error != 'undefined'){
                    $("#modal .modal-body").html(response.error);
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 5000);
                    return false;
                }
                var resp = response.header.header;
                $("#tgldo").val(resp.TGL_DO);
                $("#do_id").val(resp.ID);
                $("#noinvjual").val(resp.NO_INV_JUAL);
                $("#tglinvjual").val(resp.TGL_INV_JUAL);
                $("#pembeli").val(resp.PEMBELI);
                $("#totjmlkemasankeluar").val(resp.TOTJMLKMSKELUAR);
                $("#totjmlsathargakeluar").val(resp.TOTJMLSATHARGAKELUAR);
                tabel.clear().draw();            
                if (response.detail.length > 0){
                    tabel.rows.add(response.detail).draw();
                }
            },
            complete: function(){
                $(".loader").hide();
            }
        })
    }
})
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
$(".number").inputmask("numeric", {
    radixPoint: ".",
    groupSeparator: ",",
    digits: 0,
    autoGroup: true,
    rightAlign: false,
    removeMaskOnSubmit: true,
});
var tabel = $("#gridtransaksi").DataTable({
    processing: false,
    processing: false,
    serverSide: false,
    dom: "rtip",
    "language": 
        {
            "lengthMenu": "Menampilkan _MENU_ record per halaman",
            "info": "",
            "infoEmpty": "Data tidak ada",
            "infoFiltered": "",
            "search":         "Cari:",
            "zeroRecords":    "Tidak ada data yang sesuai pencarian",
            "paginate": {
                "next":       ">>",
                "previous":   "<<"
            }
        },
    order: [[1, 'asc']],
    columns:[{
                "target": 0,
                "data": "TGL_MUAT"
            },
            {
                "target": 1,
                "data": "NO_SJ"
            },
            {
                "target": 2,
                "data": "NO_POL"
            }, {
                "target": 3,
                "data": "DRIVER"
            },{
                "target": 4,
                "data": "JMLKEMASAN"
            },{
                "target": 5,
                "data": "REMARKS"
            },{
                "target": 6,
                "data": null
            }
            
    ],
    rowCallback: function(row, data){        
        $('td:eq(4)', row).html(parseFloat(data.JMLKEMASAN).formatMoney(2,"",",","."));
        var opsi =  '<a title="Edit" href="#modaldetail" class="editdetail" data-toggle="modal" id="' + data.ID + 
                    '"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;' +
                    '<a title="Hapus" class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>';
        $('td:eq(6)', row).html(opsi);
    }
})
$('#modaldetail').on('shown.bs.modal', function () {
    $("#savedetail").removeClass("disabled");
    $('#tgl_muat').focus();
})
$("#savedetail").on("click", function(){
    $(this).prop("disabled", true);
    var tglmuat = $("#tgl_muat").val();
    var no_sj = $("#no_sj").val();
    var no_pol = $("#no_pol").val();
    var driver = $("#driver").val();
    var remarks = $("#remarks").val();
    var jmlkemasan = $("#jmlkemasan").inputmask('unmaskedvalue');
    var act = $("#form").attr("act");
    if (act == "add"){
        tabel.row.add({NO_POL: no_pol, TGL_MUAT: tglmuat, NO_SJ: no_sj, DRIVER: driver, REMARKS: remarks, JMLKEMASAN: jmlkemasan}).draw();
        $("#no_pol").val("");
        $("#no_sj").val("");
        $("#tgl_muat").val("");
        $("#driver").val("");
        $("#remarks").val("");
        $("#jmlkemasan").val("");
        $("#no_pol").focus();        
    }
    else if (act == "edit"){        
        var id = $("#iddetail").val();
        var idx = $("#idxdetail").val();
        tabel.row(idx).data({ID: id, TGL_MUAT: tglmuat, NO_SJ: no_sj, NO_POL: no_pol, DRIVER: driver, REMARKS: remarks, JMLKEMASAN: jmlkemasan}).draw();
        $("#modaldetail").modal("hide");
    }    
    $(this).prop("disabled", false);
});
$("#adddetail").on("click", function(){        
    $("#no_pol").val("");
    $("#no_sj").val("");
    $("#tgl_muat").val("");
    $("#driver").val("");
    $("#remarks").val("");
    $("#jmlkemasan").val("");
    $("#modaldetail .modal-title").html("Tambah Detail");
    $("#form").attr("act","add");
})
$("body").on("click", ".editdetail", function(){
    var row = $(this).closest("tr");
    var index = tabel.row(row).index();
    var row = tabel.rows(index).data();
    $("#no_pol").val(row[0].NO_POL);
    $("#tgl_muat").val(row[0].TGL_MUAT);
    $("#no_sj").val(row[0].NO_SJ);
    $("#driver").val(row[0].DRIVER);
    $("#jmlkemasan").val(row[0].JMLKEMASAN);
    $("#remarks").val(row[0].REMARKS);
    $("#idxdetail").val(index);
    $("#iddetail").val(row[0].ID);
    $("#modaldetail .modal-title").html("Edit Detail");
    $("#form").attr("act","edit");
})
$("#btnsimpan").on("click", function(){   
   var rows = tabel.rows().data();    
   var detail = [];  
   $(rows).each(function(index,elem){
        detail.push(elem);
    })    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ;
   $(this).prop("disabled", true);
   $(".loader").show();
   $.ajax({
       url: "/transaksi/crud",
       data: {type: "pengeluaran", do_id: $("#do_id").val(), header: detail},
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
               $('#modal').on('hidden.bs.modal', function (e) {
                    window.location.reload();
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
})
$("body").on("click", ".del", function(){
    var row = $(this).closest("tr");
    var index = tabel.row(row).remove().draw();
})
{% if (no_do != "") %}
    $("#no_do").val("{{ no_do }}");
    $("#no_do").trigger("change");
    $("#tlgldo").focus();
{% endif %}
 
})

{% endblock %}