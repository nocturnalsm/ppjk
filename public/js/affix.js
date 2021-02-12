$('body').scrollspy({ target: '#spy', offset: 150});
$("#spy a.list-group-item").on("click", function(){
    var href = $(this).attr("href");
    $('html, body').animate({
        scrollTop: $(href).offset().top - 100
    }, 1000);
})
