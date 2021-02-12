{% extends 'base.html.twig' %}
{% block body %}
<style>
    .error {display:none;font-size: 0.75rem;color: red};
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
                        <label class="col-form-label col-md-3" for="kodehs">Kode HS</label>
                        <div class="col-md-9">
                            <input type="text" id="kodehs" name="kodehs" class="form-control form-control-sm validate">                        
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="booking">Booking</label>
                        <div class="col-md-9">
                            <input type="text" id="booking" name="booking" class="number form-control form-control-sm validate">                        
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="realisasi">Realisasi</label>
                        <div class="col-md-9">
                            <input type="text" id="realisasi" name="realisasi" class="number form-control form-control-sm validate">                        
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="satuan">Satuan</label>
                        <div class="col-md-9 pt-2">
                            <select class="form-control form-control-sm" id="satuan" name="satuan">
                                <option value=""></option>
                                {% for satuan in datasatuan %}
                                <option value="{{ satuan.id }}">{{ satuan.satuan }}</option>
                                {% endfor %}
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
                    Form Perekaman VO {{ notransaksi }}
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input type="hidden" value="{{ header.ID }}" id="idtransaksi" name="idtransaksi">
            <div class="row">
                <div class="col-md-7 co-sm-12 px-auto pt-0">   
                <div class="form-row px-2">
                    <label class="col-md-2 form-control-sm">Consignee</label>
                    <div class="col-md-4">
                        <select class="form-control form-control-sm" id="consignee" name="consignee" value="{{ header.CONSIGNEE }}">
                            <option value=""></option>
                            {% for imp in importir %}
                            <option {% if header.CONSIGNEE == imp.IMPORTIR_ID %}selected{% endif %} value="{{ imp.IMPORTIR_ID }}">{{ imp.NAMA }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <label class="col-md-1 col-form-label form-control-sm">No. PI</label>
                    <div class="col-md-5">
                        <input type="text" readonly maxlength="24" class="form-control form-control-sm" name="nopi" id="nopi" value="{{ header.NO_PI }}">
                        <input type="hidden" name="idpi" value="{{ header.ID_PI }}">
                    </div>
                </div>
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">No VO</label>
                    <div class="col-md-2">
                        <input maxlength="24" type="text" class="form-control form-control-sm" name="novo" value="{{ header.NO_VO }}" id="novo">
                        <p class="error nopen1">No VO harus diisi</p>
                    </div>   
                    <label class="col-form-label form-control-sm">Tgl VO</label>
                    <div class="col-md-2">
                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglvo" value="{{ header.TGL_VO }}" id="tglvo">
                    </div>         
                </div>   
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Kode HS</label>
                    <div class="col-md-10">
                        <textarea class="form-control form-control-sm" name="kodehs" id="kodehs">{{ header.KODE_HS_VO }}</textarea>                            
                    </div>           
                </div>
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Tanggal Periksa</label>
                    <div class="col-md-2">
                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ header.TGL_PERIKSA_VO }}" name="tglperiksa" id="tglperiksa">
                    </div>
                    <label class="col-md-2 col-form-label form-control-sm">Tanggal LS</label>
                    <div class="col-md-2">
                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ header.TGL_LS }}" name="tglls" id="tglls">
                    </div>                        
                    <label class="col-form-label form-control-sm">Status VO</label>
                    <div class="col-md-2">
                        <select class="form-control form-control-sm" id="status" name="status" value="{{ header.STATUS }}">
                            <option {% if header.STATUS == "" %}selected{% endif %} value=""></option>
                            <option {% if header.STATUS == "K" %}selected{% endif %} value="K">Konfirmasi</option>
                            <option {% if header.STATUS == "B" %}selected{% endif %} value="B">Belum Inspect</option>
                            <option {% if header.STATUS == "S" %}selected{% endif %} value="S">Sudah Inspect</option>
                            <option {% if header.STATUS == "R" %}selected{% endif %} value="R">Revisi FD</option>
                            <option {% if header.STATUS == "F" %}selected{% endif %} value="F">FD</option>
                            <option {% if header.STATUS == "L" %}selected{% endif %} value="L">LS Terbit</option>
                        </select>
                    </div>                                               
                </div>        
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Catatan VO</label>
                    <div class="col-md-10">
                        <textarea class="form-control form-control-sm" name="catatan" id="catatan">{{ header.CATATAN }}</textarea>                            
                    </div>           
                </div>                              
            </div>
            </div>
            <div class="row pt-2 px-2">
                <div class="card col-md-12 p-0">
                    <div class="card-body p-3">
                        <div class="form-row">
                            <div class="col primary-color text-white py-2 px-4">
                                Detail Quota
                            </div>
                        </div>                    
                        <div class="form-row">
                            <div class="col mt-2">
                                <table width="100%" id="griddetail" class="table">
                                    <thead>
                                        <tr>
                                            <th>Kode HS</th>
                                            <th>Booking</th>
                                            <th>Realisasi</th>
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
        <input type="hidden" name="deletedetail">
        </form>
    </div>
</div>

{% endblock %}
{% block scripts %}

var detail = "{{ quota|escape('js') }}";
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
function checkComplete(elem){
    if ($("#" + elem).inputmask("unmaskedvalue").trim() != "" && !$("#" + elem).inputmask("isComplete")){
        $(".error." + elem).show();
        var complete = false;
    }
    else {
        $(".error." +elem).hide();
        var complete = true;
    }
    return complete;
}

function validate(){
    var valid = true;
    var check1 = ["novo"];
    $(check1).each(function(index, elem){
        if (checkEmpty(elem)){
            valid = false;
        }
    });
    /*
    var check2 = ["aju1","nopen1"];
    $(check2).each(function(index, elem){
        if (!checkComplete(elem)){
            valid = false;
        }
    });*/
    return valid;
}
$("#novo").on("input", function(){
    $(".error." + $(this).attr("id")).hide();
})
$("#btnsimpan").on("click", function(){   
    //if (validate()){
        var detail = [];
        var rows = tabel.rows().data();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          ;
        $(rows).each(function(index,elem){
            detail.push(elem);
        })    
        $(this).prop("disabled", true);
        $(".loader").show()
        $.ajax({
            url: "/transaksi/crud",
            data: {type: "uservo", header: $("#transaksi").serialize(), detail: detail},
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
$("#savedetail").on("click", function(){
    $(this).prop("disabled", true);
    var booking = $("#booking").inputmask('unmaskedvalue');
    var realisasi = $("#realisasi").inputmask('unmaskedvalue');
    var kodehs = $("#kodehs").val();
    var satuan = $("#satuan").val();
    var namasatuan = $("#satuan option:selected").html();
    var act = $("#form").attr("act");
    
    if (act == "add"){
        tabel.row.add({BOOKING: booking, KODE_HS: kodehs, REALISASI: realisasi, SATUAN_ID: satuan, satuan: namasatuan}).draw();
        $("#kodehs").val("");
        $("#booking").val("");
        $("#realisasi").val("");
        $("#satuan").val("");
        $("#kodehs").focus();        
    }
    else if (act == "edit"){        
        var id = $("#iddetail").val();
        var idx = $("#idxdetail").val();
        tabel.row(idx).data({ID: id, BOOKING: booking, KODE_HS: kodehs, REALISASI: realisasi, SATUAN_ID: satuan, satuan: namasatuan}).draw();   
        $("#modaldetail").modal("hide");
    }    
    $(this).prop("disabled", false);
});
var tabel = $("#griddetail").DataTable({
    processing: false,
    serverSide: false,
    data: datadetail,
    dom: "t",
    rowCallback: function(row, data)
    {        
        $('td:eq(1)', row).html(parseFloat(data.BOOKING).formatMoney(2,"",",","."));
        $('td:eq(2)', row).html(parseFloat(data.REALISASI).formatMoney(2,"",",","."));
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
        data: "KODE_HS"            
      },
      { target: 1,
        data: "BOOKING"
      },              
      { target: 2,
        data: "REALISASI"
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
    $("#kodehs").val("");
    $("#booking").val("");
    $("#realisasi").val("");
    $("#satuan").val("");
    $("#modaldetail .modal-title").html("Tambah ");
    $("#form").attr("act","add");
})
$("body").on("click", ".edit", function(){
    var row = $(this).closest("tr");
    var index = tabel.row(row).index();
    var row = tabel.rows(index).data();
    $("#booking").val(row[0].BOOKING);
    $("#kodehs").val(row[0].KODE_HS);
    $("#realisasi").val(row[0].REALISASI);
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
$("#kodehs").inputmask("9999.99.99");

})
{% endblock %}