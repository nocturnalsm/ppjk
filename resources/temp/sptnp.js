$(function(){

$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
$("#btnsearch").on("click", function(){
    $("#formsearch").submit();
})
$("#formsearch").on("submit", function(e){
    e.preventDefault();
    $(".loader").show()
    $.ajax({
        url: "/transaksi/searchsptnp",
        data: $("#formsearch").serialize(),
        type: "GET",
        success: function(msg) {
            if (msg.length == 0){
                $("#modal .modal-body").html("Data tidak ditemukan");
                $("#modal").modal("show");
                setTimeout(function(){
                    $("#modal").modal("hide");
                }, 1000);  
            }
            else {
                window.location.href = "/transaksi/usersptnp/" +msg[0].ID;                
            }
        },
        complete: function(){
            $(".loader").hide();
        }
    });
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
function validate(){
    var valid = true;
    var check1 = ["nopen","tglnopen"];
    $(check1).each(function(index, elem){
        if (checkEmpty(elem)){
            valid = false;
        }
    });
    return valid;
}
$("#nopen,#tglnopen").on("input", function(){
    $(".error." + $(this).attr("id")).hide();
})
$("#btnsimpan").on("click", function(){   
    if (validate()){
        $(this).prop("disabled", true);
        $(".loader").show()
        $.ajax({
            url: "/transaksi/crud",
            data: {type: "usersptnp", header: $("#transaksi").serialize()},
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
    }
    else {
        return false;
    }
})
$(".number").inputmask("numeric", {
    radixPoint: ".",
    groupSeparator: ",",
    digits: 2,
    autoGroup: true,
    rightAlign: true,
    removeMaskOnSubmit: true,
    oncleared: function () { self.setValue(''); }
});

});