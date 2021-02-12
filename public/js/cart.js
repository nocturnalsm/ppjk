Number.prototype.formatNumber = function(places, symbol, thousand, decimal) {
	places = !isNaN(places = Math.abs(places)) ? places : 2;
	symbol = symbol !== undefined ? symbol : "$";
	thousand = thousand || ",";
	decimal = decimal || ".";
	var number = this,
			negative = number < 0 ? "-" : "",
			i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
};

$("th input.check").on("click", function(){    
    var checked = $(this).prop("checked");    
    $("tbody td input.check").prop("checked", checked);
})
$("tr input.check").on("click", function(){    
    var checked = $(this).prop("checked"); 
    if (!checked){
        $("th input.check").prop("checked", checked);
    }
    $(this).closest("tr").find(".jumlah").prop("disabled", !checked);
    sumall($(this));
})
function timeout(onSuccess, seconds){
    if (typeof seconds == 'undefined'){
        seconds = 6000;
    }
    setTimeout(function(){
        $("#modal").modal("hide");
        onSuccess();
    }, seconds);  
}
function sumall(e){
    $.ajax({
        type: "POST",
        url: "/cart/savecart",
        data: $("#form").serialize(),
        success: function(data){            
            if (data != ''){
                var msg = JSON.parse(data);      
                if (typeof msg.redirect != 'undefined'){
                    timeout(function(){
                        window.location.href = msg.redirect;
                    });                     
                }
                if (msg.code != ""){               
                    $("#modal .modal-body").html(msg.message);
                    $("#modal").modal('show');
                    if (typeof e != 'undefined'){
                        if (e.attr("type") == 'checkbox'){
                            var elem = e.closest("tr").find(".jumlah");
                        }
                        else if (e.attr("type") == "text"){
                            var elem = e;
                        }
                        $(e).val(msg.code);
                        sumall();
                    }
                    timeout(function(){
                        $("#modal").modal("hide");
                    });                    
                }
            }
            else {
                var total = 0;
                var point = 0;
                var jumlah = 0;
                var checked = true;
                $("#cart table tbody tr").each(function(index){
                    checked = $(this).find("input.check").prop("checked");  
                    if (checked){
                        point = parseInt($(this).find(".point").html().replace(/,/g,""));
                        jumlah = parseInt($(this).find(".jumlah").val().replace(/,/g,""));
                        total = total + point*jumlah;
                    }
                })        
                var availpoint = parseInt($("#availpoint").html().replace(/,/g,""));    
                var sisapoint = availpoint - total;
                $("#tukarpoint").html(total.formatNumber(0,"",",",""));
                $("#sisapoint").html(sisapoint.formatNumber(0,"",",",""));
                $("#trans").toggleClass("disabled", sisapoint < 0 || total <= 0);
                $("#pointidakcukup").toggleClass("d-none", sisapoint >= 0 && total >= 0);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {            
            $("#modal .modal-body").html(errorThrown);
            $("#modal").modal('show');
            timeout(function(){
                $("#modal").modal("hide");
                $("#otp").focus();
            });
        }        
    });        
}
$(".jumlah").on("input", function(){    
    if ($(this).val().trim() != ""){
        sumall($(this));
    }    
})
$(".jumlah").on("blur", function(){    
    if ($(this).val().trim() == ""){        
        $(this).val("1");
        sumall($(this));
    }    
})
$("#trans").on("click", function(){
    $(this).prop("disabled", true);
    $("#form").submit();
})
$("#form_otp").on("submit",function(e){
    e.preventDefault();        
    if ($("#otp").val().trim() != ""){
        $("#tukar").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: "/cart/verify",
            data: $("#form_otp").serialize(),
            success: function(data){
                var msg = JSON.parse(data);                
                if (msg.code == "0"){
                    $("#counter").remove();                    
                    $("#modal .modal-body").html(msg.message);
                    $("#modal").modal('show');
                    timeout(function(){
                        $("#modal").modal("hide");
                        window.location.href = "/poin";
                    });                    
                }
                else {
                    $("#tukar").prop("disabled", false);
                    if (msg.message){
                        $("#modal .modal-body").html(msg.message);
                        $("#modal").modal('show');
                        timeout(function(){
                            $("#modal").modal("hide");
                            $("#otp").focus();
                        }); 
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#tukar").prop("disabled", false);
                $("#modal .modal-body").html(errorThrown);
                $("#modal").modal('show');
                timeout(function(){
                    $("#modal").modal("hide");
                    $("#otp").focus();
                });
            }
        });  
    }
    else {
        $("#modal .modal-body").html('Kode verifikasi harus diisi');                                                    
        $("#modal").modal('show');
        timeout(function(){
            $("#modal").modal("hide");
            $("#otp").focus();
        });
    }
});
function countdown( elementName, endTime )
{
    var element, msLeft, time;

    function twoDigits( n )
    {
        return (n <= 9 ? "0" + n : n);
    }

    function updateTimer()
    {
        msLeft = new Date(endTime) - (+new Date);
        if ( msLeft < 1000 ) {
            $("#modal .modal-body").html('Waktu Anda habis');                                                    
            $("#modal").modal('show');
            timeout(function(){
                $("#divform").remove();
                $("#timesup").toggleClass("d-block");
            });            
        } else {
            time = new Date( msLeft );
            hours = time.getUTCHours();
            mins = time.getUTCMinutes();
            element.innerHTML = 'Waktu Anda tinggal: ' + (hours ? hours + ' jam ' + twoDigits( mins ) : mins) + ' menit ' + twoDigits( time.getUTCSeconds() + ' detik' );
            setTimeout( updateTimer, time.getUTCMilliseconds() + 500 );
        }
    }

    element = document.getElementById( elementName );
    updateTimer();
}