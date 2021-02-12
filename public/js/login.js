function validateEmail() {
        var email = $("#email").val().trim();
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var valid = re.test(String(email).toLowerCase());
        if (valid === false){                  
            //$("#form_login .invalid-feedback").html('Masukkan alamat email yang valid');                                                    
            //$("#form_login .invalid-feedback").show();
            $("#modal .modal-body").html('Masukkan alamat email yang valid');
            $("#modal").modal('show');
            timeout(function(){
                $("#email").focus();
            });
        }
        return valid;
} 
function timeout(onHide){
    setTimeout(function(){
        $("#modal").modal("hide");
        onHide();
    }, 6000);
}
$("#form_login").on("submit",function(e){
    e.preventDefault();
    var validate1 = validateEmail() !== false;
    if (validate1){
        $.ajax({
            type: "POST",
            url: "/auth/login",
            data: {email: $("#email").val().trim(), href: $("input[name='href']").val()},
            success: function(data){
                var msg = JSON.parse(data);
                if (typeof msg.redirect == 'undefined'){
                    if (msg.code == "0"){
                        $("#step1").hide();
                        $("#step2").show();
                        if (msg.message){
                            $("#modal .modal-body").html(msg.message);
                            $("#modal").modal('show');                        
                            timeout(function(){
                                $("#otp").focus();                            
                            });
                        }
                        $("#otp").focus();
                    }
                    else {
                        if (msg.message){
                            $("#modal .modal-body").html(msg.message);
                            $("#modal").modal('show');
                            timeout(function(){
                                $("#email").focus();
                            });
                        }
                    }
                }
                else {
                    window.location.href = msg.redirect;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {   
                $("#modal .modal-body").html(errorThrown);
                $("#modal").modal('show');
                timeout(function(){
                    $("#email").focus();
                })
            }
        });  
    }
});
$("#form_otp").on("submit",function(e){
    e.preventDefault();    
    if ($("#otp").val().trim() != ""){
        $.ajax({
            type: "POST",
            url: "/auth/login",
            data: {email: $("#email").val().trim(), otp: $("#otp").val().trim(), href: $("input[name='href']").val()},
            success: function(data){
                var msg = JSON.parse(data);  
                if (msg.redirect){              
                    window.location.href = msg.redirect;
                }
                else {
                    if (msg.code == "0"){
                        window.location.href = "/";
                    }
                    else {
                        if (msg.message){
                            $("#modal .modal-body").html(msg.message);
                            $("#modal").modal('show');
                            timeout(function(){
                                $("#otp").focus();
                            })
                        }
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {   
                $("#modal .modal-body").html(errorThrown);
                $("#modal").modal('show');
                timeout(function(){
                    $("#otp").focus();
                })
            }
        });  
    }
    else {
        $("#modal .modal-body").html('Kode verifikasi harus diisi');
        $("#modal").modal('show');
        timeout(function(){
            $("#otp").focus();
        })
        /*
        $(".invalid-feedback").html('Kode verifikasi harus diisi');                                                    
        $(".invalid-feedback").show();*/
    }
});
$("#email, #otp").on("input", function(){
    if ($(this).hasClass("is-invalid")){
        $(this).removeClass("is-invalid");
    }
    if ($(".invalid-feedback").is(":visible")){
        $(".invalid-feedback").hide();
    }
})
$("#email").focus();