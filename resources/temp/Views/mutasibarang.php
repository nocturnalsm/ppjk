{% extends 'base.html.twig' %}
{% block body %}
<style>
    .error {display:none;font-size: 0.75rem;color: red};
    .file-row > div {
      display: inline-block;
      vertical-align: top;
      padding: 8px;
    }
    #preview-container .dz-progress .dz-upload {
        background: #333;
        background: linear-gradient(to bottom, #666, #444);
        position: absolute;
        top: 25%;        
        right: 10%;
        height: 12px;
        width: 0;
        -webkit-transition: width 300ms ease-in-out;
        -moz-transition: width 300ms ease-in-out;
        -ms-transition: width 300ms ease-in-out;
        -o-transition: width 300ms ease-in-out;
        transition: width 300ms ease-in-out; 
    }
    #preview-container .dz-remove {
        margin-left: 10px;
    }
</style>
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">                    
                <form id="form" act="">            
                    <input type="hidden" name="idxdetail" id="idxdetail">
                    <input type="hidden" name="iddetail" id="iddetail">
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="nofaktur">No. Faktur</label>
                        <div class="col-md-9">
                            <input type="text" id="nofaktur" name="nofaktur" class="form-control form-control-sm validate">                        
                        </div>
                    </div>                    
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="kurs">Tgl Keluar</label>
                        <div class="col-md-9">
                            <input type="text" id="tglterima" name="tglterima" class="datepicker form-control form-control-sm validate">                        
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="nominal">Roll Out</label>
                        <div class="col-md-9">
                            <input type="text" id="rollout" name="rollout" class="text-right number form-control form-control-sm validate">                        
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="nominal">Qty Out</label>
                        <div class="col-md-9">
                            <input type="text" id="qtyout" name="qtyout" class="text-right number form-control form-control-sm validate">                        
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="produk">Satuan</label>
                        <div class="col-md-9">
                        <select class="form-control form-control-sm" id="satuan" name="satuan">
                            <option value=""></option>
                            {% for satuan in datasatuan %}
                            <option value="{{ satuan.id }}">{{ satuan.kode }}</option>
                            {% endfor %}
                        </select>
                        </div>
                    </div>
                </form>               
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <a id="savedetail" class="btn btn-primary">Simpan</a>
                <a class="btn btn-danger" data-dismiss="modal">Batal</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="card col-md-12 p-0">
        <div class="card-header font-weight-bold">
            <div class="row">
                <div class="col-md-4 py-0 pl-4 mt-1">
                    Form Mutasi Barang
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input type="hidden" value="{{ barang.ID }}" id="idtransaksi" name="idtransaksi">                
            <div class="row px-2">
                <div class="col-md-6 pt-0 col-sm-12">                    
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-3 col-form-label form-control-sm">Kode Barang</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="form-control form-control-sm" name="kodebarang" id="kodebarang" value="{{ header.KODEBARANG }}">
                                    </div>                                     
                                    <label class="col-md-3 col-form-label form-control-sm">Customer</label>                   
                                    <div class="col-md-9">                                                            
                                        <span id="formtglterima">{{ barang.TGLTERIMA |default("") }}</span>
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">                                     
                                    <label class="col-md-3 col-form-label form-control-sm">Jml Kemasan</label>                   
                                    <div class="col-md-3">                                                            
                                        <span id="formjmlkemasan">{{ barang.JMLKEMASAN |default("") }}</span>
                                    </div>
                                    <label class="col-md-3 col-form-label form-control-sm">Jml Sat Harga</label>                   
                                    <div class="col-md-3">                                                            
                                        <span id="formjmlsatharga">{{ barang.JMLSATHARGA |default("") }}</span>
                                    </div>
                                    <label class="col-md-3 col-form-label form-control-sm">Satuan</label>                   
                                    <div class="col-md-3">                                                            
                                        <span id="formsatuan">{{ barang.satuan |default("") }}</span>
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Harga</label>                   
                                    <div class="col-md-3">                                                            
                                        <span id="formharga">{{ barang.HARGA |default("") }}</span>
                                    </div>
                                    <label class="col-md-3 col-form-label form-control-sm">Harga Satuan</label>                   
                                    <div class="col-md-3">                                                            
                                        <span id="formsatuan">{{ barang.HARGASATUAN |default("") }}</span>
                                    </div>           
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Tot Roll Out</label>                   
                                    <div class="col-md-3">                                                            
                                        <span id="formtotrollout">{{ barang.TOTROLLOUT |default("") }}</span>
                                    </div>
                                    <label class="col-md-3 col-form-label form-control-sm">Tot Qty Out</label>                   
                                    <div class="col-md-3">                                                            
                                        <span id="formtotqtyout">{{ barang.TOTQTYOUT |default("") }}</span>
                                    </div>      
                                    <label class="col-md-3 col-form-label form-control-sm">Saldo Roll Out</label>                   
                                    <div class="col-md-3">                                                            
                                        <span id="formtotrollout">{{ barang.SAROLLOUT |default("") }}</span>
                                    </div>
                                    <label class="col-md-3 col-form-label form-control-sm">Saldo Qty Out</label>                   
                                    <div class="col-md-3">                                                            
                                        <span id="formtotqtyout">{{ barang.SAQTYOUT |default("") }}</span>
                                    </div>           
                                </div>
                            </div>
                        </div>
                    </div>                     
                </div>
                <div class="col-md-6 col-sm-12">                    
                    <div class="card-body py-0">
                        <div class="row">
                            <div id="dropzone" class="p-4 border">              
                                <div class="dz-message needsclick">
                                    Drag file ke kotak di bawah ini untuk meng-upload atau klik untuk memilih file Excel (.xls | .xlsx | .pdf).<br>
                                    <span class="note needsclick"></span>
                                </div>
                                <div id="preview-container" class="card-body">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row px-1 mt-2">
                            <div class="col-md-12 col-sm-12">
                                <table class="table table-bordered" id="listfiles">                                
                                {% for file in files %}
                                    <tr>
                                        <td>{{ file.FILEREALNAME }}</td>
                                        <td class="text-center">
                                            <a href="#" class="delete" title="Hapus File">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <input type="hidden" value="{{ file.ID }}" name="fileid">
                                            <a href="/transaksi/getfile?file={{ file.ID }}" tile="Download File" class="download">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                {% endfor %}
                                </table>
                            </div>
                        </div>
                    </div>                
                </div>
            </div>
            <div class="row px-2">
                <div class="col-md-12">
                    <div class="row mb-2">
                        <div class="card col-md-12 p-0">
                            <div class="card-body p-3">
                                <h5 class="card-title">Detail Mutasi</h5>
                                <div class="form-row">
                                    <div class="col primary-color text-white py-2 px-4">
                                        Detail Mutasi
                                    </div>
                                    <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                                        <a href="#modaldetail" data-toggle="modal" class="text-white" id="adddetail">Tambah Detail</a>
                                    </div>                            
                                </div>                    
                                <div class="form-row">
                                    <div class="col mt-2">
                                        <table width="100%" id="griddetail" class="table">
                                            <thead>
                                                <tr>
                                                    <th>No. Faktur</th>
                                                    <th>Tgl Keluar</th>
                                                    <th>Roll Out</th>
                                                    <th>Qty Out</th>
                                                    <th>Satuan</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>     
                                </div>                        
                            </div>
                        </div>
                    </div>                                                        
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/template" id="template">
<div class="file-row row border">
    <div class="col-md-9">
        <span class="name d-block" data-dz-name></span>   
    </div>    
    <div class="col-md-3 p-2">
        <div class="dz-progress mt-4">
            <span class="dz-upload" data-dz-uploadprogress></span>
        </div>
        <div class="dz-success-mark text-center"></div>
        <div class="dz-error-mark text-center"></div>
    </div>
</div>
</script>
{% endblock %}
{% block scripts %}
$(function(){
    var transaksi = "{{ transaksi|escape('js') }}";
    datadetail = JSON.parse(transaksi);    
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
    $('#modaldetail').on('shown.bs.modal', function () {
        $('#nofaktur').focus();
    })
    $("#dnominal,#kurs").on("change", function(){
        var nominal = parseFloat($("#dnominal").inputmask("unmaskedvalue"));
        var kurs = parseFloat($("#kurs").inputmask("unmaskedvalue"));
        var rupiah = nominal*kurs;
        $("#rupiah").val(rupiah);
    })
    $("#savedetail").on("click", function(){ 
        var seri = $("#formseri").html();
        var satuan = $("#formsatuan").html();
        var produk = $("#produk option:selected").val();
        var kodebarang = $("#kodebarang").val();
        var namaproduk = $("#formnamaproduk").html();
        var tglterima = $("#tglterima").val();
        var jmlkemasan = $("#jmlkemasan").inputmask('unmaskedvalue');
        var jmlsatharga = $("#jmlsatharga").inputmask('unmaskedvalue');
        var harga = $("#harga").inputmask('unmaskedvalue');
        var hargasatuan = $("#hargasatuan").inputmask('unmaskedvalue');
        var act = $("#form").attr("act");
        
        if (act == "add"){
            tabel.row.add({SERIBARANG: seri, KODEBARANG: kodebarang, PRODUK_ID: produk, NAMAPRODUK: namaproduk, satuan: satuan, TGL_TERIMA: tglterima, JMLKEMASAN: jmlkemasan, JMLSATHARGA: jmlsatharga, HARGA: harga, HARGASATUAN: hargasatuan}).draw();            
            $("#formsatuan").html("");
            $("#formnamaproduk").html("");
            $("#produk").val("");
            $("#kodebarang").val("");
            $("#jmlkemasan").val("");
            $("#jmlsatharga").val("");
            $("#harga").val("");
            $("#hargasatuan").val("");
            var rowcount = ("0000" + (tabel.rows().count() + 1)).substr(-4,4);
            $("#formseri").html(rowcount);
            var tglnopen = $("#formtglnopen").html().replace(/9/g,'\\9').replace("-","");
            var nopen = $("#formnopen").html().replace(/9/g,'\\9');
            $("#kodebarang").inputmask("999999" + "-" + nopen + "-" + tglnopen + "-99");
            $("#kodebarang").focus();
        }
        else if (act == "edit"){        
            var id = $("#iddetail").val();
            var idx = $("#idxdetail").val();
            tabel.row(idx).data({ID: id, SERIBARANG: seri, KODEBARANG: kodebarang, PRODUK_ID: produk, NAMAPRODUK: namaproduk, satuan: satuan, TGL_TERIMA: tglterima, JMLKEMASAN: jmlkemasan, JMLSATHARGA: jmlsatharga, HARGA: harga, HARGASATUAN: hargasatuan}).draw();   
            $("#modaldetail").modal("hide");
        }    
    });
    $(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
    $(".number").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
    });
    var tabel = $("#griddetail").DataTable({
        processing: false,
        serverSide: false,
        data: datadetail,
        dom: "t",
        rowCallback: function(row, data)
        {        
            $(row).attr("id-transaksi", data.id);
            $('td:eq(4)', row).html(parseFloat(data.JMLKEMASAN).formatMoney(0,"",",","."));
            $('td:eq(5)', row).html(parseFloat(data.JMLSATHARGA).formatMoney(0,"",",","."));
            $('td:eq(7)', row).html(parseFloat(data.HARGA).formatMoney(0,"",",","."));
            $('td:eq(8)', row).html(parseFloat(data.HARGASATUAN).formatMoney(2,"",",","."));
            $('td:eq(9)', row).html('<a href="#modaldetail" class="edit" data-toggle="modal" id="' + data.ID + 
                                    '"><i class="fa fa-edit"></i></a>' +
                                    '&nbsp;&nbsp;<a class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                    );
        },
        select: 'single',     // enable single row selection
        responsive: false,     // enable responsiveness,
        rowId: 0,
        columns: [{
            target: 0,
            data: "SERIBARANG"            
        },
        { target: 1,
            data: "KODEBARANG"
        },              
        { target: 2,
            data: "NAMAPRODUK"
        },
        { target: 3,
            data: "TGL_TERIMA"
        },
        { target: 4,
            data: "JMLKEMASAN"
        },
        { target: 5,
            data: "JMLSATHARGA"
        },
        { target: 6,
            data: "satuan"
        },
        { target: 7,
            data: "HARGA"
        },
        { target: 8,
            data: "HARGASATUAN"
        },        
        { target: 9,
            data: null
        }
        ],
    })
    $("#adddetail").on("click", function(){        
        $("#formsatuan").html("");
        $("#formnamaproduk").html("");
        $("#produk").val("");
        $("#kodebarang").val("");
        $("#jmlkemasan").val("");
        $("#jmlsatharga").val("");
        $("#tglterima").val("");
        $("#harga").val("");
        $("#hargasatuan").val("");
        var rowcount = ("0000" + (tabel.rows().count() + 1)).substr(-4,4);
        $("#formseri").html(rowcount);
        var tglnopen = $("#formtglnopen").html().replace(/9/g,'\\9').replace("-","");
        var nopen = $("#formnopen").html().replace(/9/g,'\\9');
        $("#kodebarang").inputmask("999999" + "-" + nopen + "-" + tglnopen + "-99");
        $("#modaldetail .modal-title").html("Tambah ");
        $("#form").attr("act","add");
    })
    $("body").on("click", ".edit", function(){
        var row = $(this).closest("tr");
        var index = tabel.row(row).index();
        var row = tabel.rows(index).data();
        $("#formsatuan").html(row[0].satuan);
        $("#formnamaproduk").html(row[0].NAMAPRODUK);
        $("#formseri").html(row[0].SERIBARANG);
        $("#produk").val(row[0].PRODUK_ID);
        $("#kodebarang").val(row[0].KODEBARANG);
        $("#jmlkemasan").val(row[0].JMLKEMASAN);
        $("#jmlsatharga").val(row[0].JMLSATHARGA);
        $("#harga").val(row[0].HARGA);
        $("#tglterima").val(row[0].TGL_TERIMA);
        $("#hargasatuan").val(row[0].HARGASATUAN);
        $("#idxdetail").val(index);
        $("#iddetail").val(row[0].ID);
        $("#modaldetail .modal-title").html("Edit");
        $("#form").attr("act","edit");
    })   
    $("body").on("click", ".del", function(){
        var row = $(this).closest("tr");
        var id = tabel.row(row).data().ID;
        if (typeof id != 'undefined'){
            $("input[name='deletedetail'").val($("input[name='deletedetail'").val() + id + ";");
        }
        var index = tabel.row(row).remove().draw();
    })
    $("#nopen").inputmask({"mask": "999999","removeMaskOnSubmit": true});

    $("#btnsimpan").on("click", function(){   
    
            //if (validate()){          
            var detail = Array();
            var rows = tabel.rows().data();
            $(rows).each(function(index,elem){                
                detail.push(elem);
            }) 
            console.log(detail);
            $(this).prop("disabled", true);
            var files = $("input[name=fileid]").map(function(index){
                return $(this).val();
            }).get();
            
            //$(".loader").show();
            $.ajax({
                url: "/transaksi/crud",
                data: {type: "barang", header: $("#transaksi").serialize(), detail: detail, files: files},
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
                        window.location.reload();
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
    $("body").on("change","#produk", function(){
        var selected = $(this).find("option:selected");
        var tglnopen = $("#formtglnopen").html().replace(/9/g,'\\9').replace("-","");
        var nopen = $("#formnopen").html().replace(/9/g,'\\9');
        if (selected.val() != ""){
            $("#formsatuan").html(selected.attr("satuan"));
            $("#formnamaproduk").html(selected.attr("namaproduk"));
            $("#harga").val(selected.attr("harga"));
            var hscode = selected.attr("hscode").replace(".","").substring(0,6).replace(/9/g,'\\9');            
            $("#kodebarang").inputmask(hscode + "-" + nopen + "-" + tglnopen + "-99");
        }
        else {
            $("#formsatuan").html("");
            $("#formnamaproduk").html("");
            $("#kodebarang").val("");
            $("#kodebarang").inputmask("999999" + "-" + nopen + "-" + tglnopen + "-99");
        }
        $("#harga").trigger("change");
    })        
    $("#harga,#jmlsatharga").on("change", function(){
        var jmlsatharga = $("#jmlsatharga").val().replace(/,/g,"");         
        var harga = $("#harga").val().replace(/,/g,""); 
        var hargasatuan = parseFloat(harga)/parseFloat(jmlsatharga);
        hargasatuan = hargasatuan.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");   
        $("#hargasatuan").val(hargasatuan);
    })
    var numFiles = $("#listfiles tr").length;    
    var maxFiles = 4;
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
        acceptedFiles: ".xls, .xlsx",        
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
{% endblock %}