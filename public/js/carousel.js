$(document).ready(function(){
    $(".owl-carousel").owlCarousel({
        items: 4,
        margin: 15,
        stagePadding: 10,
        nav: true,
        dots: false,
        navText : ['<i class="fa fa-angle-left fa-2x" aria-hidden="true"></i>','<i class="fa fa-angle-right fa-2x" aria-hidden="true"></i>'],        
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            992: {
                items:3
            },
            1201:{
                items:4
            }
        }
    });
});