$(function(){

Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
	places = !isNaN(places = Math.abs(places)) ? places : 0;
	symbol = symbol !== undefined ? symbol : "";
	thousand = thousand || ",";
	decimal = decimal || ".";
	var number = this,
			negative = number < 0 ? "-" : "",
			i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
};
$("body").on("change","#noinv", function(){
    $.ajax({
        method: "GET",
        url: "/transaksi/searchinv?inv=" + $(this).val(),
        success: function(response){
            if (typeof response.error == 'undefined'){                
                $("#formcustomer").html(response.nama_customer);
                $("#formshipper").html(response.NAMASHIPPER);
                $("#formimportir").html(response.NAMAIMPORTIR);
                $("#formnoaju").html(response.NOAJU);
                $("#formnopen").html(response.NOPEN);
                $("#formtglnopen").html(response.TGL_NOPEN);
                $("#noinv_id").val(response.ID);
            }
            else {
                $("#modal .modal-body").html(response.error);
                $("#formcustomer").html("");
                $("#formshipper").html("");
                $("#formimportir").html("");
                $("#formnoaju").html("");
                $("#formnopen").html("");
                $("#formtglnopen").html("");
                $("#noinv_id").val("");
                $("#modal").modal("show");
                setTimeout(function(){
                    $("#modal").modal("hide");
                }, 5000);
            }
        }
    })
})
$('#modaldetail').on('shown.bs.modal', function () {
    $('#penerima').focus();
})
$("#dnominal,#kurs").on("change", function(){
    var nominal = parseFloat($("#dnominal").inputmask("unmaskedvalue"));
    var kurs = parseFloat($("#kurs").inputmask("unmaskedvalue"));
    var rupiah = nominal*kurs;
    $("#rupiah").val(rupiah);
})
$("#savedetail").on("click", function(){ 
    var noppu = $("#noppu").val();
    var noinv = $("#noinv").val();
    var noinv_id = $("#noinv_id").val();
    var customer = $("#formcustomer").html();
    var importir = $("#formimportir").html();
    var shipper = $("#formshipper").html();
    var noaju = $("#formnoaju").html();
    var nopen = $("#formnopen").html();
    var tglnopen = $("#formtglnopen").html();    
    var curr_id = $("#curr option:selected").val();
    var curr = $("#curr option:selected").html();
    if (!curr){
        curr = "";
        curr_id = "";
    }
    var kurs = $("#kurs").inputmask('unmaskedvalue');
    var nominal = $("#dnominal").inputmask('unmaskedvalue');    
    var rupiah = parseFloat(nominal)*parseFloat(kurs);
    var act = $("#form").attr("act");
    
    if (act == "add"){
        tabel.row.add({CUSTOMER: customer, NO_INV: noinv_id, IMPORTIR: importir, NO_PPU: noppu, NOINV: noinv, CURR: curr_id, MATAUANG: curr, KURS: kurs, RUPIAH: rupiah, NOMINAL: nominal, SHIPPER: shipper, NOAJU: noaju, NOPEN: nopen, TGL_NOPEN: tglnopen}).draw();
        $("#formcustomer").html("");
        $("#formimportir").html("");
        $("#formshipper").html("");
        $("#formnoaju").html("");
        $("#formnopen").html("");
        $("#formtglnopen").html("");
        $("#noppu").val("");
        $("#noinv").val("");
        $("#curr").val("");
        $("#kurs").val("");
        $("#dnominal").val("");
        $("#rupiah").val("");
        $("#catatan").val("");        
        $("#noinv").focus();        
    }
    else if (act == "edit"){        
        var id = $("#iddetail").val();
        var idx = $("#idxdetail").val();
        tabel.row(idx).data({ID: id, NO_INV: noinv_id, CUSTOMER: customer, IMPORTIR: importir, NO_PPU: noppu, NOINV: noinv, CURR: curr_id, MATAUANG: curr, KURS: kurs, RUPIAH: rupiah, NOMINAL: nominal, SHIPPER: shipper, NOAJU: noaju, NOPEN: nopen, TGL_NOPEN: tglnopen}).draw();   
        $("#modaldetail").modal("hide");
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
});
var tabel = $("#griddetail").DataTable({
    processing: false,
    serverSide: false,
    data: datadetail,
    dom: "t",
    rowCallback: function(row, data)
    {        
        $(row).attr("id-transaksi", data.id);
        $('td:eq(9)', row).html(parseFloat(data.KURS).formatMoney(0,"",",","."));
        $('td:eq(10)', row).html(parseFloat(data.NOMINAL).formatMoney(0,"",",","."));
        $('td:eq(11)', row).html(parseFloat(data.RUPIAH).formatMoney(0,"",",","."));
        $('td:eq(12)', row).html('<a href="#modaldetail" class="edit" data-toggle="modal" id="' + data.ID + 
                                '"><i class="fa fa-edit"></i></a>' +
                                '&nbsp;&nbsp;<a class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                );
    },
    select: 'single',     // enable single row selection
    responsive: false,     // enable responsiveness,
    rowId: 0,
    columns: [{
        target: 0,
        data: "NOINV"            
      },
      { target: 1,
        data: "CUSTOMER"
      },              
      { target: 2,
        data: "IMPORTIR"
      },
      { target: 3,
        data: "SHIPPER"
      },
      { target: 4,
        data: "NOAJU"
      },
      { target: 5,
        data: "NOPEN"
      },
      { target: 6,
        data: "TGL_NOPEN"
      },
      { target: 7,
        data: "NO_PPU"
      },
      { target: 8,
        data: "CURR"
      },
      { target: 9,
        data: "KURS"
      },
      { target: 10,
        data: "NOMINAL"
      },
      { target: 11,
        data: "RUPIAH"
      },
      { target: 12,
        data: null
      }
     ],
})
$("#adddetail").on("click", function(){
    $("#formcustomer").html("");
    $("#formimportir").html("");
    $("#formshipper").html("");
    $("#formnoaju").html("");
    $("#formnopen").html("");
    $("#formtglnopen").html("");
    $("#noppu").val("");
    $("#noinv").val("");
    $("#noinv_id").val("");
    $("#curr").val("");
    $("#kurs").val("");
    $("#dnominal").val("");
    $("#rupiah").val("");
    $("#modaldetail .modal-title").html("Tambah ");
    $("#form").attr("act","add");
})
$("body").on("click", ".edit", function(){
    var row = $(this).closest("tr");
    var index = tabel.row(row).index();
    var row = tabel.rows(index).data();
    $("#formcustomer").html(row[0].CUSTOMER);
    $("#formimportir").html(row[0].IMPORTIR);
    $("#formshipper").html(row[0].SHIPPER);
    $("#formnoaju").html(row[0].NOAJU);
    $("#formnopen").html(row[0].NOPEN);
    $("#formtglnopen").html(row[0].TGLNOPEN);
    $("#noppu").val(row[0].NO_PPU);
    $("#noinv").val(row[0].NOINV);
    $("#noinv_id").val(row[0].NO_INV);
    $("#curr").val(row[0].CURR);
    $("#kurs").val(row[0].KURS);
    $("#dnominal").val(row[0].NOMINAL);
    $("#rupiah").val(row[0].RUPIAH);
    $("#idxdetail").val(index);
    $("#iddetail").val(row[0].ID);
    $("#modaldetail .modal-title").html("Edit ");
    $("#form").attr("act","edit");
})
$("#cif, #nominal").on("change", function(){
    calculate();
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

$("#btnsimpan").on("click", function(){   
   
        //if (validate()){
        var detail = [];
        var rows = tabel.rows().data();
        var total = 0;
        $(rows).each(function(index,elem){
            total = total + parseFloat(elem.RUPIAH);
            detail.push(elem);
        })    
        $balance = $("#totpayment").inputmask('unmaskedvalue') - total;
        if ($balance != 0){
            $("#modal .modal-body").html("Nominal penarikan dan jumlah detail harus sama");
            $("#modal").modal("show");
            setTimeout(function(){
                $("#modal").modal("hide");
            }, 5000);
            return false;
        }
        $(this).prop("disabled", true);
        $(".loader").show()
        $.ajax({
            url: "/transaksi/crud",
            data: {type: "bayar", header: $("#transaksi").serialize(), detail: detail},
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
                    window.location.reload();
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

})