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
    $(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
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
        var check1 = ["noinv","nopo","nosc","nobl","noform"];
        $(check1).each(function(index, elem){
            if (checkEmpty(elem)){
                valid = false;
            }
        });
        return valid;
    }
    $("#noinv,#nopo,#nosc,#nobl,#noform").on("input", function(){
        $(".error." + $(this).attr("id")).hide();
    })
    $("#tglbl,#top").on("change", function(){
        var top = $("#top").val();
        if (top == ""){
            return false;
        }
        var term = 30;
        if (top == 1){
            term = 20;
        }
        else if (top == 3){
            term = 20;
        }
        else if (top == 5){
            term = 60;
        }
        else if (top == 6){
            term = 90;
        }        
        var date = $('#tglbl').datepicker('getDate');
        if (!date){
            return false;
        }
        date.setDate(date.getDate() + term);
        $('#tgljatuhtempo').datepicker('setDate',date);
    });
    $(".number").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        oncleared: function () { self.setValue(''); }
    });
    $(".cifnumber").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 3,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        oncleared: function () { self.setValue(''); }
    });
    $("#btnsimpan").on("click", function(){   
        //if (validate()){
            $(this).prop("disabled", true);
            $(".loader").show()
            var files = $("input[name=fileid]").map(function(index){
                return {id: $(this).val(), jenisfile: $(".jenisfile").eq(index).find("option:selected").val()};
            }).get();            
            $.ajax({
                url: "/transaksi/crud",
                data: {type: "userdo", header: $("#transaksi").serialize(), files: files},
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
                            window.location.reload();
                        }, 2000);                        
                    }      
                },
                complete: function(){
                    $("#btnsimpan").prop("disabled", false);
                    $(".loader").hide();
                }
            }) 
        /*}
        else {
            return false;
        }*/
    })    
    var numFiles = $("#listfiles tr").length;    
    var maxFiles = 6;
    if (numFiles == maxFiles){
        $("div#dropzone").hide();
    }
    var myDropzone = new Dropzone("#dropzone", { 
        url: "/transaksi/upload",
        uploadMultiple: false,
        maxFiles: maxFiles - numFiles,        
        maxFilesize: 2,     
        previewsContainer: "#preview-container",
        previewTemplate: $("#template").html(),
        acceptedFiles: ".xls, .xlsx, .pdf",        
        init:function(){
            var self = this;
            // config
            self.options.addRemoveLinks = true;
            self.options.dictRemoveFile = "Hapus";            
            self.on("success", function(file, response) {                            
                var value = JSON.parse(response);
                $(file.previewElement).append('<input type="hidden" name="fileid" value="' + value.id + '">');                                
            })
            // On removing file
            self.on("removedfile", function (file) {
                var hidden = $(file.previewElement).find("input[name=fileid]").val();
                if (hidden){
                    $.ajax({
                        url: "/transaksi/removefile",
                        data: {id: hidden},
                        method: "POST"
                    });
                }
            });    
            self.on("addedfile", function(file) {
                if (this.files.length > self.options.maxFiles){
                    this.removeFile(file);
                }
            });  
            self.on("complete", function (file) {
                if(file.status == Dropzone.SUCCESS){
                    success = true;
                    $(file.previewElement).find(".dz-success-mark").html('<i class="fa fa-check-circle text-success">');
                    $(file.previewElement).find(".dz-error-mark").hide();
                    $(file.previewElement).find(".dz-progress").hide();
                }
                else if (file.status == Dropzone.ERROR){
                    $(file.previewElement).find('.dz-error-mark').html('<i class="fa fa-times-circle text-danger"></i>');                          
                    $(file.previewElement).find(".dz-success-mark").hide();
                    $(file.previewElement).find(".dz-progress").hide();
                }
            });     
        }
    });
    $("#listfiles a.delete").on("click",function(){        
        $(this).closest("tr").remove();
        myDropzone.options.maxFiles = maxFiles - $("#listfiles tr").length;
        $("div#dropzone").show();
    });
})