function timeout(onSuccess){
    setTimeout(function(){
        $("#modal").modal("hide");
        onSuccess();
    }, 6000);  
}
$(".btn-tukar").on("click", function(e){    
    $(this).addClass("disabled");
    $(".loading").show();
    $.ajax({
        type: "POST",
        url: "/cart/add",
        data: {hadiah_id: $(this).attr("hadiah_id")},
        success: function(data){  
            if (data != ""){
                var msg = JSON.parse(data);                
                if (typeof msg.redirect != 'undefined'){
                    window.location.href = msg.redirect;
                }
                else {
                    $("#modal .modal-body").html(msg.message);
                    $("#modal").modal('show');                     
                    timeout(function(){
                        $("#modal").modal("hide");                        
                    });
                }
                $(e.target).removeClass("disabled");
            }
            else {
                window.location.href = "/cart";
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $("#modal .modal-body").html(errorThrown);
            $("#modal").modal('show');            
            timeout(function(){
                $("#modal").modal("hide");               
            });        
        },
        complete: function(){
            $(".loading").hide();
            $(e.target).removeClass("disabled");
        }
    });    
})
$(".card-edit a[data-toggle=modal]").on("click", function(){
    $("#formModal").html(buildForm("hadiah","Add Reward","ADD", formPlugin.fields_hadiah, "medium"));    
    var parent = $(this).closest(".row");
    if ($(parent)[0].hasAttribute("tag")){        
        if ($(parent).attr("tag") == "unggulan"){
            $("input[name=unggulan]").prop("checked", true);
        }
    }    
    else if ($(parent)[0].hasAttribute("kategori")){        
        $("#kategori").val($(parent).attr("kategori"));
    }
});