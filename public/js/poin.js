$("#riwayat .collapse").on("shown.bs.collapse", function(){
    var icon = $(this).siblings("a").find("i");    
    $(icon).attr("class","fa fa-chevron-up");
});
$("#riwayat .collapse").on("hidden.bs.collapse", function(){
    var icon = $(this).siblings("a").find("i");    
    $(icon).attr("class","fa fa-chevron-down");
});
