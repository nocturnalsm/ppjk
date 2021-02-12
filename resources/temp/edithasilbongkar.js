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
$("#aju1,#nopen1").inputmask({"mask": "999999","removeMaskOnSubmit": true});
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
function validate(){
    var error = false;
    if ($("#nobl").val().trim() === ""){
        $(".error.nobl").show();
        error = true;
    }
    else {
        $(".error.nobl").hide();
    }
    if ($("#jmlkemasan").val().trim() === ""){
        $(".error.jmlkemasan").show();
        error = true;
    }
    else {
        $(".error.jmlkemasan").hide();
    }
    if ($("#aju1").inputmask("unmaskedvalue").trim() != "" && !$("#aju1").inputmask("isComplete")){
        $(".error.aju1").show();
        error = true;
    }
    else {
        $(".error.aju1").hide();
    }
    if ($("#nopen1").inputmask("unmaskedvalue").trim() != "" && !$("#nopen1").inputmask("isComplete")){
        $(".error.nopen1").show();
        error = true;
    }
    else {
        $(".error.nopen1").hide();
    }
    return !error;
}
$("#nobl, #jmlkemasan, #aju1, #nopen1").on("input", function(){
    $(".error." + $(this).attr("id")).hide();
})
$("#btnsimpan").on("click", function(){    
    if (validate()){
        $("#btnsimpan").prop("disabled", true);
        $(".loader").show()
        $.ajax({
            url: "/transaksi/savehasilbongkar",
            data: {header: $("#transaksi").serialize()},
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

})