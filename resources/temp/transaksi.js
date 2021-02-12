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
$('#modalkontainer').on('shown.bs.modal', function () {
    $('#nokontainer').focus();
})
$("#savekontainer").on("click", function(){ 
    var nomor = $("#nokontainer").val();
    var ukuran = $("#ukuran").val();
    var namaukuran = $("#ukuran option:selected").html();
    var act = $("#formkontainer").attr("act");
    if (act == "add"){
        tabelkontainer.row.add({NOMOR_KONTAINER: nomor, UKURAN_KONTAINER: ukuran, URAIAN: namaukuran}).draw();
        $("#nokontainer").val("");
        $("#ukuran").val("");
        $("#nokontainer").focus();
    }
    else if (act == "edit"){        
        var id = $("#iddetailkontainer").val();
        var idx = $("#idxdetailkontainer").val();
        tabelkontainer.row(idx).data({ID: id, NOMOR_KONTAINER: nomor, UKURAN_KONTAINER: ukuran, URAIAN: namaukuran}).draw();   
        $("#modalkontainer").modal("hide");
    }    
});
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
var tabelkontainer = $("#gridkontainer").DataTable({
    processing: false,
    serverSide: false,
    data: datadetailkontainer,
    dom: "t",
    rowCallback: function(row, data)
    {        
        $(row).attr("id-transaksi", data.id);
        $('td:eq(1)', row).html('<input type="hidden" class="ukurankontainer" value="' + data.UKURAN_KONTAINER + '">' + data.URAIAN);
        $('td:eq(2)', row).html('<a href="#modalkontainer" class="editkontainer" data-toggle="modal" idkontainer="' + data.ID + 
                                '"><i class="fa fa-edit"></i></a>' +
                                '&nbsp;&nbsp;<a class="delkontainer" idkontainer="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                );
    },
    select: 'single',     // enable single row selection
    responsive: false,     // enable responsiveness,
    rowId: 0,
    columns: [{
                target: 0,
                data: "NOMOR_KONTAINER"            
              },
              { target: 1,
                data: "URAIAN"
              },              
              { target: 2,
                data: null
              }
             ]
})
$("#addkontainer").on("click", function(){
    $("#nokontainer").val("");
    $("#ukuran").val("");
    $("#modalkontainer .modal-title").html("Tambah Kontainer");
    $("#formkontainer").attr("act","add");
})
$("body").on("click", ".editkontainer", function(){
    var row = $(this).closest("tr");
    var index = tabelkontainer.row(row).index();
    var row = tabelkontainer.rows(index).data();
    $("#nokontainer").val(row[0].NOMOR_KONTAINER);
    $("#ukuran").val(row[0].UKURAN_KONTAINER);
    $("#idxdetailkontainer").val(index);
    $("#iddetailkontainer").val(row[0].ID);
    $("#modalkontainer .modal-title").html("Edit Kontainer");
    $("#formkontainer").attr("act","edit");
})
$("body").on("click", ".delkontainer", function(){
    var row = $(this).closest("tr");
    var id = tabelkontainer.row(row).data().ID;
    if (typeof id != 'undefined'){
        $("input[name='deletekontainer'").val($("input[name='deletekontainer'").val() + id + ";");
    }
    var index = tabelkontainer.row(row).remove().draw();
})
$("#nopen").inputmask({"mask": "999999","removeMaskOnSubmit": true});
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
    var check1 = ["noinv","jmlkemasan","customer","importir","tgltiba",
                  "jmlkontainer","jenisbarang","kantor"];
    var check2 = ["nopen"];
    $(check1).each(function(index, elem){
        if (checkEmpty(elem)){
            valid = false;
        }
    });
    $(check2).each(function(index, elem){
        if (!checkComplete(elem)){
            valid = false;
            console.log(elem);
        }
    });
    console.log(valid);
    return valid;
}
$("#noinv, #jmlkemasan, #tgltiba, #customer, #importir, #jmlkontainer, #kantor, #jenisbarang, #nopen").on("input", function(){
    $(".error." + $(this).attr("id")).hide();
})
$("#btnsimpan").on("click", function(){   
   
        $(this).prop("disabled", true);
        var detailkontainer = [];
        var rowskontainer = tabelkontainer.rows().data();
        $(rowskontainer).each(function(index,elem){
            detailkontainer.push(elem);
        })    
        $(".loader").show()
        $.ajax({
            url: "/transaksi/crud",
            data: {header: $("#transaksi").serialize(), kontainer: detailkontainer},
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
                        $("#modal").modal("hide");                    
                    }, 5000);
                    if ($("#idtransaksi").val().trim() == ""){
                        window.location.href = "/transaksi";
                    }
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
$("#deletetrans").on("click", function(){
    $("#modal .btn-ok").removeClass("d-none");
    $("#modal .btn-close").html("Batal");
    $("#modal .modal-body").html("Apakah Anda ingin menghapus data ini?");        
    $("#modal .btn-ok").html("Ya").on("click", function(){
        $.ajax({
            url: "/transaksi/delete",
            data: $("#formdelete").serialize(),
            type: "POST",
            success: function(msg) { 
                $("#modal").modal("hide");
                $("#modal .btn-ok").addClass("d-none");
                if (typeof msg.error != 'undefined'){
                    $("#modal .modal-body").html(msg.error);
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 5000);
                }
                else {
                    $("#modal .modal-body").html("Data berhasil dihapus");
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 10000);                    
                    window.location.href = "/transaksi/search";
                }      
            }
        })
    })
    $("#modal").modal("show");
})


})