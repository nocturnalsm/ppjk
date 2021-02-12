{% extends 'base.html.twig' %}
{% block body %}
<div class="modal fade" id="modalkonversi" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">                    
                <form id="formkonversi" act="">            
                    <input type="hidden" name="idxkonversi" id="idxkonversi">
                    <input type="hidden" name="idkonversi" id="idkonversi">
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="produk">Produk</label>
                        <div class="col-md-9">
                        <select class="form-control form-control-sm" id="produk" name="produk">
                            <option value=""></option>
                            {% for produk in dataproduk %}
                            <option harga = "{{ produk.harga }}" kodeproduk="{{ produk.kode }}" value="{{ produk.id }}">{{ produk.nama }}</option>
                            {% endfor %}
                        </select>
                        </div>
                    </div>                    
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="rate">Rate</label>
                        <div class="col-md-9">
                        <select class="form-control form-control-sm" id="rate" name="rate">
                            <option value=""></option>
                            {% for rate in datarate %}
                            <option value="{{ rate }}">{{ rate }} %</option>
                            {% endfor %}
                        </select>
                        </div>
                    </div>                    
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="dpp">DPP</label>
                        <div class="col-md-9">
                            <input readonly type="text" id="dpp" name="dpp" class="text-right number form-control form-control-sm validate">                        
                        </div>
                    </div>                    
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="kurs">Tgl Terima</label>
                        <div class="col-md-9">
                            <input type="text" id="tglterima" name="tglterima" class="datepicker form-control form-control-sm validate">                        
                        </div>
                    </div>
                </form>               
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <a id="savekonversi" class="btn btn-primary">Simpan</a>
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
                    Form Konversi Barang
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input type="hidden" value="{{ header.ID }}" id="idtransaksi" name="idtransaksi">                
            <input type="hidden" name="deletedetail" id="deletedetail" value="">
            <div class="row px-2">
                <div class="col-md-12 pt-0 col-sm-12">                    
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <div class="form-row px-2 pb-0">                                     
                                    <label class="col-md-1 col-form-label form-control-sm">Kode Barang</label>                   
                                    <div class="col-md-4 mt-1">                                                            
                                        {{ header.KODEBARANG }}
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-1 col-form-label form-control-sm">Uraian Barang</label>
                                    <div class="col-md-6 mt-1">
                                        {{ header.URAIAN }}
                                    </div>
                                </div>
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
                                <h5 class="card-title">Detail Konversi</h5>
                                <div class="form-row">
                                    <div class="col primary-color text-white py-2 px-4">
                                        Konversi Barang
                                    </div>
                                    <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                                        <a href="#modalkonversi" data-toggle="modal" class="text-white" id="addkonversi">Tambah Konversi</a>
                                    </div>                            
                                </div>                    
                                <div class="form-row">
                                    <div class="col mt-2">
                                        <table width="100%" id="gridkonversi" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="15%">Kode Produk</th>
                                                    <th width="30%">Nama Produk</th>
                                                    <th width="30%">Rate</th>
                                                    <th width="30%">DPP</th>
                                                    <th width="12%">Tgl Terima</th>
                                                    <th width="12%">Opsi</th>
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
{% endblock %}
{% block styles %}
    td[colspan=11]{
        background-color: #e6e6e6 !important;
    }
    .divkonversi {
        background-color: #e6e6e6 !important;
    }
{% endblock %}
{% block scripts %}
$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
    var konversi = "{{ konversi|escape('js') }}";
    datakonversi = JSON.parse(konversi);    
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
    $('#modalkonversi').on('shown.bs.modal', function () {
        $('#produk').focus();
    })
    function calcDPP(){
        var rate = $("#rate").find("option:selected").val();
        rate = rate == "" ? 0 : parseFloat(rate);
        $("#dpp").val({{ header.NDPBM | default(0) }}*rate/100*{{ header.HARGA | default(0) }});
    }
    
    $("#produk").on("change", calcDPP);
    $("#rate").on("change", calcDPP);
    $(".number").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
    });
    $("body").on("click", ".del", function(){
        var row = $(this).closest("tr");
        var id = tabel.row(row).data().ID;
        if (typeof id != 'undefined'){
            $("input[name='deletedetail'").val($("input[name='deletedetail'").val() + id + ";");
        }
        var index = tabel.row(row).remove().draw();
    })
    var tabel = $("#gridkonversi").DataTable({
        processing: false,
        serverSide: false,
        data: datakonversi,
        dom: "t",
        rowCallback: function(row, data)
        {        
            $('td:eq(2)', row).html(parseFloat(data.RATE).formatMoney(1,"",",",".") + " %");
            $('td:eq(3)', row).html(parseFloat(data.DPP).formatMoney(0,"",",","."));
            var opsi = '<a title="Edit" href="#modalkonversi" class="editkonversi" data-toggle="modal" id="' + data.ID + 
                    '"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;' +
                    '<a title="Hapus" class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>';
            $('td:eq(5)', row).html(opsi);
        },
        select: 'single',     // enable single row selection
        responsive: false,     // enable responsiveness,
        rowId: 0,
        columns: [
            { target: 0,
                data: "KODEPRODUK"
            },              
            { target: 1,
                data: "NAMAPRODUK"
            },
            { target: 2,
                data: "RATE"
            },
            { target: 3,
                data: "DPP"
            },
            { target: 4,
                data: "TGLTERIMA"
            },
            { target: 5,
                data: null
            }
        ],
    })
    $("#btnsimpan").on("click", function(){   
        $(this).prop("disabled", true);
        var detail = Array();
        var rows = tabel.rows().data();
        $(rows).each(function(index,elem){                
            detail.push(elem);
        }) 
        $.ajax({
            url: "/transaksi/crud",
            data: {type: "konversi", header: $("#transaksi").serialize(), detail: detail},
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
                    $("#modal").on("hidden.bs.modal", function(){
                        window.location.reload();
                    });
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");                    
                    }, 5000);                                         
                }      
            },
            complete: function(){
                $("#btnsimpan").prop("disabled", false);
                $(".loader").hide();
            }
        }) 
    })
    function generate_uid(){
        return 'dt-' +Math.random().toString(36).substring(2,15) + Math.random().toString(36).substring(2,15);
    }
    $("#savekonversi").on("click", function(){ 
        var produk = $("#produk option:selected").val();
        var kodeproduk = $("#produk option:selected").attr("kodeproduk");
        var namaproduk = $("#produk option:selected").html();
        var tglterima = $("#tglterima").val();
        var dpp = $("#dpp").inputmask('unmaskedvalue');
        var rate = $("#rate").val();
        var idkonversi = $("#idkonversi").val();
        var idxkonversi = $("#idxkonversi").val();
        var act = $("#formkonversi").attr("act");
        if (act == "add"){
            var data_id = generate_uid();
            tabel.row.add({ID: data_id, KODEPRODUK: kodeproduk, NAMAPRODUK: namaproduk, PRODUK_ID: produk, TGLTERIMA: tglterima, RATE: rate, DPP: dpp}).draw();   
            $("#produk").val("");
            $("#dpp").val("");
            $("#rate").val("");
            $("#tglterima").val("");
            $("#produk").focus();
        }
        else if (act == "edit"){        
            var id = $("#idkonversi").val();
            var idx = $("#idxkonversi").val();
            tabel.row(idx).data({ID: id, KODEPRODUK: kodeproduk, NAMAPRODUK: namaproduk, PRODUK_ID: produk, TGLTERIMA: tglterima, RATE: rate, DPP: dpp}).draw();   
            $("#modalkonversi").modal("hide");
        }    
    });
    $("body").on("click", ".editkonversi", function(){
        var row = $(this).closest("tr");
        var index = tabel.row(row).index();
        var row = tabel.rows(index).data();
        $("#produk").val(row[0].PRODUK_ID);
        $("#dpp").val(row[0].DPP);
        $("#rate").val(row[0].RATE);
        $("#tglterima").val(row[0].TGLTERIMA);
        $("#idkonversi").val(row[0].ID);
        $("#modalkonversi .modal-title").html("Edit Konversi");
        $("#idxkonversi").val(index);
        $("#formkonversi").attr("act","edit");
    })   

    $("body").on("click", "#addkonversi", function(){
        $("#produk").val("");
        $("#tglterima").val("");
        $("#rate").val("");
        $("#dpp").val("");
        $("#modalkonversi .modal-title").html("Tambah Konversi");
        $("#formkonversi").attr("act","add");
    })
{% endblock %}