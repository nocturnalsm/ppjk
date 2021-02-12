
$(function(){
    
    if ($("#outerDiv").width() > $(window).height()){         
        if ($("#outerDiv").width() < 992){
            var w_height = 957 -256;
        }
        else {
            var w_height = $(window).height() - 256;    
        }
    }
    else {     
        if ($("#outerDiv").width() < 580){
            var w_height = $("#outerDiv").width()/800*957;            
        }
        else {
            var w_height = $(window).height() - 256;    
        }        
    }    
    var defaultText = $("#text").val();
    var defaultBackground = $("#slider li a").eq(0).attr("backgroundname");
    var maxchar = 14;
    $("#outerDiv").css("height",w_height+10);        
    var draw = SVG('art').size(w_height/957*800, w_height);    
    draw.viewbox(0, 0, 800, 957);
    draw.svg($("#svgfont").html());
    $("#listfont a span").each(function(index,elem){
        $(this).css("font-family",$(this).html());
    })

    var backgroundObj = null;
    var strapObj = null;
    var textTopLeft = null;
    var textTopRight = null;
    var textMiddleLeft = null;
    var textMiddleRight = null;
    var textBottomLeft = null;
    var textBottomRight = null;

    WebFont.load({
        google: {
            families: ['Roboto', 'Roboto Slab:300,400,700:latin,greek','Droid Sans:300,400,700:latin,greek']
        },
        custom: {
            families: ['Lucida Calligraphy', 'Jokerman','Comic','Times New Roman']
        }
    });

    backgroundObj = draw.image(background[defaultBackground].backgroundURI).loaded(function(loader) {
        this.size('100%', '100%');
        background[defaultBackground].drawText(draw,"\n");            
        background[defaultBackground].textMiddleLeft.hide();
        background[defaultBackground].textMiddleRight.hide();
        background[defaultBackground].textBottomLeft.hide();
        background[defaultBackground].textBottomRight.hide();
        strapObj = draw.image(background[defaultBackground].strapURI).loaded(function(loader){            
            this.size('100%', '100%');
            $(".loading").hide();
        });                     
    });   
    function generateSVGDataURL(html){        
        var $svg = $(html);
        $(".loading").show();
        $svg.find("image").eq(1).css("display","block");
        var svg = $svg[0].outerHTML;
        var imgsrc = 'data:image/svg+xml,'+ encodeURIComponent(svg);         
        var canvas = document.createElement("canvas");            
        context = canvas.getContext("2d");
        var image = new Image;
        image.src = imgsrc;     
        image.onload = function() {                        
            canvas.width = 500;
            canvas.height = 610;
            context.drawImage(image, 0, 0,image.width,image.height,0,0,500,610);
            var canvasdata = canvas.toDataURL("png");  
            var e_address =  $("#email").val().trim();                                
            $.ajax({
                type: "POST",
                url: "/backend/saveimage",
                data: {email: e_address, data: canvasdata},
                success: function(msg){                    
                    if (msg == "0"){
                        $("#success_sent").html(e_address);
                        $('#modalSave').modal('show');
                    }
                    else {                        
                        $("#modal .modal-body").html(msg);
                        $("#modal").modal('show');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#modal .modal-body").html(errorThrown);
                    $("#modal").modal('show');                    
                },
                complete: function(){
                    $(".loading").hide();
                }
            });       
        };    
    }
    $('#modalSave').on('shown.bs.modal', function(){
        var myModal = $(this);
        clearTimeout(myModal.data('hideInterval'));
        myModal.data('hideInterval', setTimeout(function(){
            myModal.modal('hide');
        }, 1200));
    });
    $("#email").on("input", function(){
        if ($(this).hasClass("is-invalid")){
            $(this).removeClass("is-invalid");
        }
    })
    $("#reference_code").on("input", function(){
        if ($(this).hasClass("is-invalid")){
            $(this).removeClass("is-invalid");
        }
    })
    function validateEmail() {
        var email = $("#email").val().trim();
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var valid = re.test(String(email).toLowerCase());
        if (valid === false){ 
            $("#email").addClass('is-invalid');                                                      
        }
        return valid;
    }     
    $("#btn_saveemail").on("click", function(event){                        
        if (validateEmail() !== false){        
            generateSVGDataURL($("#art").html());
        } 
    })    
    $('.card-body .collapse').on('hidden.bs.collapse', function () {
        var id = $(this).attr("id");
        $("span[href='#" +id +"']").find("i").removeClass("fa fa-chevron-up");
        $("span[href='#" +id +"']").find("i").addClass("fa fa-chevron-down");
    })
    $('.card-body .collapse').on('shown.bs.collapse', function () {
        var id = $(this).attr("id");
        $("span[href='#" +id +"']").find("i").removeClass("fa fa-chevron-down");
        $("span[href='#" +id +"']").find("i").addClass("fa fa-chevron-up");
    })
    $("a.link_background").on("click", function(event){
        var name = $(this).attr("backgroundname"); 
        $(".link_background").removeClass("active");
        $(this).addClass("active");
        draw.clear();
        draw.svg($("#svgfont").html());
        $(".loading").show();
        backgroundObj = draw.image(background[name].backgroundURI).loaded(function(loader) {
            this.size('100%', '100%');            
            background[name].drawText(draw,$("#text").val().trim().toUpperCase());                          
            background[name].setPosition($("#listlocation a.active").index());
            strapObj = draw.image(background[name].strapURI).loaded(function(loader){            
                this.size('100%', '100%');
                $(".loading").hide();                
            });                     
        });
        event.preventDefault();
        event.stopPropagation();
    })
    $("#arrow_background").on("click", function(){
        $("#link_background").click();    
    })
    $("#arrow_text").on("click", function(){
        $("#link_text").click();
        $(this).find("i").removeClass("fa fa-chevron-down");
        $(this).find("i").addClass("fa fa-chevron-up");
    })
    $("#step1").on("click", function(){
        $("#link_text").click();
        $("#text").focus();
        $("#text").select();    
    })
    $('#card_text').on('shown.bs.collapse', function () {
        var name = $(".link_background.active").attr("backgroundname");
        background[name].showText();
    })
    $("#text").on("input", function(){        
        var count = $(this).val().length;
        if (count > maxchar) {
            $(this).val($(this).val().substring(0,maxchar));
        }
        var text = $(this).val().toUpperCase();
        var name = $(".link_background.active").attr("backgroundname");
        background[name].setText(text);
        if (count == 0){
            $("#counter").html("");
        }
        else {        
            $("#counter").html((maxchar - $(this).val().length) + " characters remaining");
        }
    })    
    $("#reset").on("click",function(){
        $("#text").val("");
        $("#slider li").eq(0).find("a.link_background").click();
        $("#listfont a").eq(0).click();        
        $("#listlocation a").eq(0).click();
        $("#divreference").show();
        $("#thankyou_submit").hide();
    })
    $("#step2").on("click", function(){    
        $("#link_position").click();    
    })
    $("#link_background").on("click", function(){
        $("#card_background").collapse("show");
        $("#card_text").collapse("hide");
        $("#card_position").collapse("hide");
    });
    $("#link_text").on("click", function(){
        $("#card_text").collapse("show");
        $("#card_background").collapse("hide");
        $("#card_position").collapse("hide");
    });
    $("#link_position").on("click", function(){
        $("#card_position").collapse("toggle");
        $("#card_text").collapse("hide");
        $("#card_background").collapse("hide");
    });
    $("#listfont a").on("click", function(event){
        $("#listfont a").removeClass("active");
        var name = $(".link_background.active").attr("backgroundname");
        $(this).addClass("active");
        var font = $("span", this).html();
        background[name].setFont(font);
        event.preventDefault();
    });
    $("#listlocation a").on("click", function(event){
        $("#listlocation a").removeClass("active");
        var name = $(".link_background.active").attr("backgroundname");
        var index = $(this).index();
        background[name].setPosition(index);                
        $(this).addClass("active");
        event.preventDefault();
    });
    var slider;    
    $("#form_reference").on("submit", function(event){                
        if ($("#reference_code").val().trim() != ""){
            var data = {design: $(".link_background.active").attr("backgroundname"), text: $("#text").val().trim(), font: $("#listfont a.active span").attr("class"),pos: $("#listlocation a.active").index()};
            $(".loading").show();
            $.ajax({
                type: "POST",
                url: "/backend/process",
                data: {email: $("#email").val().trim(),code: $("#reference_code").val().trim(), data: data},
                success: function(msg){                    
                    var mess = JSON.parse(msg);
                    if (mess.success == "1"){
                        $("#divreference").hide();
                        $("#success_sent").html($("#email").val().trim());
                        $('#modalSave').modal('show');
                        $("#thankyou_submit").show();                                                
                    }
                    else {
                        $("#modal .modal-body").html(mess.message);
                        $("#modal").modal('show');                    
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {                    
                    $("#modal .modal-body").html(errorThrown);
                    $("#modal").modal('show');
                },
                complete: function(){
                    $(".loading").hide();
                }
            });               
        }
        else {
            $("#reference_code").addClass("is-invalid");
        }
        event.preventDefault();
        event.stopPropagation();
    })
    $('.slideControls .slidePrev').click(function(event) {    
        slider.goToPrevSlide();
        event.preventDefault();
    });

    $('.slideControls .slideNext').click(function(event) {
        slider.goToNextSlide();
        event.preventDefault();
    });   
    function resize() {
        var ratio = 800/957;        
        var height = $("#outerDiv").height();
        var width = $("#outerDiv").width();             
        draw.size(height/957*800,height);
        var name = $(".link_background.active").attr("backgroundname");
        /*
        if (width/height < ratio){
            background[name].textMiddleLeft.scale(ratio,1);
            background[name].textMiddleRight.scale(ratio,1);
        }*/
        resizeMenu();
    }
    function resizeMenu(){
        var width = $(window).width();
        var visible = $("#moremenu").is(":visible");
        if (width > 750){        
            $("#moremenu .dropdown-item").eq(5).toggle(width <= 1190);
            $("#moremenu .dropdown-item").eq(4).toggle(width <= 1172);
            $("#moremenu .dropdown-item").eq(3).toggle(width <= 1044);
            $("#moremenu .dropdown-item").eq(2).toggle(width <= 990);
            $("#moremenu .dropdown-item").eq(1).toggle(width <= 913);
            $("#moremenu .dropdown-item").eq(0).toggle(width <= 813);
        }
        else {
            $("#moremenu").siblings("li").eq(9).show();
            $("#moremenu").siblings("li").eq(8).show();
            $("#moremenu").siblings("li").eq(7).show();
            $("#moremenu").siblings("li").eq(6).show();
            $("#moremenu").siblings("li").eq(5).show();
            $("#moremenu").siblings("li").eq(4).show();
            $("#moremenu").siblings("li").eq(3).show();
        }
    }
    $("#check_strap").on("click", function(event){
        var name = $(".link_background.active").attr("backgroundname");
        var checked = strapObj.visible();  
        if (checked){
            strapObj.hide();
        }
        else {
            strapObj.show();
        }
    })
    $(".back_btn").on("click", function(){            
        history.back();
    });
    function gotoFinish(){
        $("#divpreview .card-header").hide();
        $("#divsetting").hide();
        $("#divconfirm").show();
        $("#divhowtobuy").hide();
        $("#divtoped").hide();
        $("#div_strapcheck").hide();
    }
    function gotoValidate(sender){   
        if (validateEmail() !== false){
            if (sender[0].id == "btn_howtobuy"){                      
                var data = {design: $(".link_background.active").attr("backgroundname"), text: $("#text").val().trim(), font: $("#listfont a.active span").attr("class"),pos: $("#listlocation a.active").index()};            
                $(".loading").show();
                $.ajax({
                    type: "POST",
                    url: "/backend/process",
                    data: {email: $("#email").val().trim(), data: data},
                    success: function(msg){
                        var mess = JSON.parse(msg);                        
                        if (mess.success == "1"){
                            $("#code").html(mess.message);       
                            $("#success_sent").html($("#email").val().trim());
                            $('#modalSave').modal('show');
                            gotoBuy();             
                            history.pushState("buy",null,'buy');
                        }         
                        else {
                            $("#modal .modal-body").html(mess.message);
                            $("#modal").modal('show');
                        }           
                    },
                    error: function(jqXHR, textStatus, errorThrown) {                    
                        $("#modal .modal-body").html(errorThrown);
                        $("#modal").modal('show');
                    },
                    complete: function(){
                        $(".loading").hide();
                    }
                });
            }
            else if (sender[0].id == "btn_already"){
                history.pushState("toped",null,"toped");
                gotoToped();
            }         
        }                          
    }
    function gotoBuy(){
        $("#divhowtobuy").show();
        $("#divconfirm").hide();  
    }
    function gotoToped(){
        $("#divtoped").show();
        $("#divconfirm").hide();  
        $("#reference_code").val("");
        $("#divreference").show();
        $("#thankyou_submit").hide();
    }
    $("#finish").on("click",function(){            
        gotoFinish();
        history.pushState("finish",null,'finish');
    })
    $("#btn_howtobuy").on("click", function(){           
        gotoValidate($(this));
    })
    $("#btn_already").on("click", function(){
        gotoValidate($(this));
    })
    $(window).on("popstate", function(e){
        var state = e.originalEvent.state;
        if (state == null){
            $("#divconfirm").hide();
            $("#divsetting").show();            
            $("#divhowtobuy").hide();
            $("#divtoped").hide();   
            $("#divpreview .card-header").show();
            $("#div_strapcheck").show();
        }
        else if (state == "finish"){                        
            gotoFinish();          
        }
        else if (state == "buy"){
            if (validateEmail() !== false){
                gotoBuy();   
            }
            else {
                //history.replaceState("",null,"finish");
            }
        }
        else if (state == "toped"){
            if (validateEmail() !== false){
                gotoToped();   
            }
            else {
                //history.replaceState("",null,"finish");
            }    
        }
    })
    $(".goto_toped").on("click", function(){
        window.open("https://www.tokopedia.com/siantarmaju", "_blank"); 
    })
    $("#moremenu .tree").on("click", function(){
        $(this).siblings(".submenu-item").toggle();
    })
    $(".leftmenu .tree").on("click", function(){
        $(this).siblings(".submenu-item").toggle();
    })
    $(".leftmenu .title a").on("click", function(){
        $("i.menu-rotate").toggleClass("down");
        $(".navbar-toggler").click();    
    });
    slider = $("#slider").lightSlider({
        item: 3,    
        pager: true,
        controls: false,
        onSliderLoad: function(el){            
            $("#card_background div.preloader").hide();
        },
        responsive : [
            {
                breakpoint:1440,
                settings: {
                    item:3,
                    slideMove:1,
                    slideMargin:2,
                }
            },
            {
                breakpoint:1200,
                settings: {
                    item:2,
                    slideMove:1,
                    slideMargin:2,
                }
            },
            {
                breakpoint:992,
                settings: {
                    item:5,
                    slideMove:1,
                    slideMargin:2,
                }
            },
            {
                breakpoint:745,
                settings: {
                    item:4,
                    slideMove:1,
                    slideMargin:2,
                }
            },
            {
                breakpoint:480,
                settings: {
                    item:2,
                    slideMove:1
                }
            }
        ]
    });     
    $(window).on('resize', resize);
    $(window).on('load', resizeMenu);
    $("#slider").show();    
    $(".slideControls").show();   

});