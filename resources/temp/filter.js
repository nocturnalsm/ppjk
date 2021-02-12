$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});

$("#print").on("click", function(){
    $("#form").submit();
})