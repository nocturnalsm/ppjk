{% extends "base.html.twig" %}
{% block body %}
<div class="card">
    <div class="card-header">
        Kartu Hutang
    </div>
    <div class="card-body">
        <div class="row">            
            <div class="col-md-10">
                <form id="form" method="POST" action="/transaksi/kartuhutang?filter=1&export=1">
                    <div class="row">
                        <label class="col-md-2">Kantor</label>
                        <div class="col-md-3 col-sm-6">
                            <select class="form-control form-control-sm" id="kantor" name="kantor">
                                <option value="">Semua</option>
                                {% for ktr in datakantor %}
                                <option value="{{ ktr.KANTOR_ID }}">{{ ktr.URAIAN }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>                    
                    <div class="row">
                        <label class="col-md-2">Customer</label>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="customer" name="customer">
                                <option value="">Semua</option>
                                {% for cust in datacustomer %}
                                <option value="{{ cust.id_customer }}">{{ cust.nama_customer }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2">Importir</label>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="importir" name="importir">
                                <option value="">Semua</option>
                                {% for imp in dataimportir %}
                                <option value="{{ imp.IMPORTIR_ID }}">{{ imp.NAMA }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2">Shipper</label>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="shipper" name="shipper">
                                <option value="">Semua</option>
                                {% for ship in datashipper %}
                                <option value="{{ ship.id_pemasok }}">{{ ship.nama_pemasok }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                    <!--
                    <div class="row">
                        <div class="col-md-2">
                            Kategori
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="kategori1" name="kategori1">
                                <option value=""></option>
                                {% for kat in datakategori1 %}
                                <option {% if kategori1 == kat %}selected{% endif %} value="{{ kat }}">{{ kat }}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <label class="px-sm-3 col-2 col-sm-1">Nilai</label>
                        <div class="col-md-4">
                            <input type="text" id="isikategori1_text" name="isikategori1" class="form-control form-control-sm" style="display:inline;width: 120px">
                            <select disabled id="isikategori1_select" name="isikategori1" class="form-control form-control-sm" style="display:none;width:180px">
                                <option value=""></option>
                                {% for term in top %}
                                <option value="{{ term.TOP_ID }}">{{ term.TOP }}</option>
                                {% endfor %}
                            </select>
                            <select disabled id="isikategori2_select" name="isikategori1" class="form-control form-control-sm" style="display:none;width:180px">
                                <option value=""></option>
                                <option value="Y">TT</option>
                                <option value="T">Non TT</option>
                            </select>
                        </div>
                    </div>
                    -->
                    <div class="row">
                        <div class="col-md-2">
                            Kategori
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="kategori2" name="kategori2" value="{{ kategori2 }}">
                                <option value=""></option>
                                {% for kat in datakategori2 %}
                                <option {% if kategori2 == kat %}selected{% endif %} value="{{ kat }}">{{ kat }}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <label class="px-sm-3 col-sm-1">Periode</label>
                        <div class="col-md-5">
                            <input autocomplete="off" type="text" id="dari2" name="dari2" value="{{ dari2 }}" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
                            &nbsp;&nbsp;sampai&nbsp;&nbsp;
                            <input autocomplete="off" type="text" id="sampai2" value="{{ sampai2 }}" name="sampai2" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
                        </div>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 px-sm-2 pt-2">
                <a id="preview" class="btn btn-primary">Filter</a>
                <a id="export" class="btn btn-primary disabled">Export</a>
            </div>
        </div>  
        </form>
        <div class="row mt-4 pt-4">
            <div class="col" id="divtable">
                <table width="100%" id="grid" class="table">
                    <thead>
                        <th></th>
                        <th>Kantor</th>
                        <th>Importir</th>                        
                        <th>Customer</th>
                        <th>Shipper</th>
                        <th>Jns Dok</th>
                        <th>Nopen</th>
                        <th>Tgl Nopen</th>       
                        <th>No Inv</th>
                        <th>Tgl Inv</th>
                        <th>Jth Tempo</th>
                        <th>Curr</th>
                        <th>CIF</th>
                        <th>Penarikan</th>
                        <th>Saldo</th>           
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>      
    </div>
</div>  
{% endblock %}
{% block scripts %}

$(function(){

$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});

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
var columns = [{target:0, data: null, orderable: false, classname: "show-control"}, {target: 1, data: "KANTOR"}, 
{target: 2, data: "IMPORTIR"}, {target: 3, data: "CUSTOMER"}, 
{target: 4, data: "SHIPPER"}, {target: 5, data: "JENISDOKUMEN"},
{target: 6, data: "NOPEN"}, {target: 7, data: "TGLNOPEN"}, 
{target: 8, data: "NO_INV"}, {target: 9, data: "TGLINV"},
{target: 10, data: "TGLJTHTEMPO"},{target: 11, data: "MATAUANG"}, 
{target: 12, data: "CIF"}, {target: 13, data: "TOT_PAYMENT"}, 
{target: 14, data: "SALDO"}];

var grid = $("#grid").DataTable({responsive: false,
    dom: "rtip",
    "language": 
    {
        "lengthMenu": "Menampilkan _MENU_ record per halaman",
        "info": "",
        "infoEmpty": "Data tidak ada",
        "infoFiltered": "",
        "search":         "Cari:",
        "zeroRecords":    "Tidak ada data yang sesuai pencarian",
        "paginate": {
            "next":       ">>",
            "previous":   "<<"
        }
    },
    order: [[0, 'asc']],
    columns: columns,
    rowCallback: function(row, data)
    {                
        $(row).attr("id-transaksi", data[0]);        
        $('td:eq(0)', row).html('<a title="Detail" class="showdetail"><i class="fa fa-plus-circle"></i></a>');
        $('td:eq(14)', row).html(parseFloat(data.SALDO).formatMoney(0,"",",","."));
        $('td:eq(12)', row).html(parseFloat(data.CIF).formatMoney(3,"",",","."));
        $('td:eq(13)', row).html(parseFloat(data.TOT_PAYMENT).formatMoney(0,"",",","."));
    }
}); 
$("#kategori1").on("change", function(){
    var value = $(this).val();
    if (value == "TOP"){
        $("#isikategori1_text").css("display","none");
        $("#isikategori1_text").prop("disabled", true);
        $("#isikategori2_select").css("display","none");
        $("#isikategori2_select").prop("disabled", true);
        $("#isikategori1_select").css("display","inline");
        $("#isikategori1_select").prop("disabled", false);
    }
    else if (value == "No Inv"){
        $("#isikategori1_text").css("display","inline");
        $("#isikategori1_text").prop("disabled", false);
        $("#isikategori1_select").css("display","none");
        $("#isikategori1_select").prop("disabled", true);
        $("#isikategori2_select").css("display","none");
        $("#isikategori2_select").prop("disabled", true);
    }
    else {
        $("#isikategori1_text").css("display","none");
        $("#isikategori1_select").css("display","none");
        $("#isikategori1_select").prop("disabled", true);
        $("#isikategori1_text").prop("disabled", true);
        $("#isikategori2_select").css("display","inline");
        $("#isikategori2_select").prop("disabled", false);
    }
})
$("#preview").on("click", function(){
    $.ajax({
       method: "POST",
       url: "/transaksi/kartuhutang?filter=1",
       data: $("#form").serialize(),
       success: function(msg){           
            grid.clear().rows.add(msg);
            grid.columns.adjust().draw();  
            if (msg.length == 0){
                $("#export").addClass("disabled");
            }
            else {
                $("#export").removeClass("disabled");
            }                             
       }
    });
})
$("#form input, select").on("change", function(){
    $("#export").addClass("disabled");
})
$("#export").on("click", function(){
    $("#form").submit();
})
$('body').on('click', 'a.showdetail', function () {
    var tr = $(this).closest('tr');
    var row = grid.row( tr );

    if ( row.child.isShown() ) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
        $(this).find("i").attr("class","fa fa-plus-circle");
    }
    else {
        var child = row.child();
        if (typeof child == 'undefined'){
            var data = Array();
            $.ajax({
                url: '/transaksi/detailbayar',
                data: {id: row.data().ID},
                method: "POST",
                success: function(response){
                    //var  response = JSON.parse(msg);
                    if (typeof response.error != 'undefined'){
                        return false;
                    }            
                    var data = response.data;
                    var detail =  '<table class="table" width="100%">'+
                            '<thead>'+
                                '<tr>' +
                                    '<th>No PPU</th>' +
                                    '<th>Curr</th>' +
                                    '<th>Kurs</th>' +
                                    '<th>Nominal</th>' +
                                    '<th>Rupiah</th>' +
                                    '<th>Tgl Bayar</th>' +
                                    '<th>Opsi</th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>';
                    if (data.length > 0){
                        $(data).each(function(index, elem){
                            detail += '<tr>' +
                                        '<td>' + elem.NO_PPU + '</td>' +
                                        '<td>' + elem.MATAUANG + '</td>' +
                                        '<td>' + parseFloat(elem.KURS).formatMoney(0,"",",",".") + '</td>' +
                                        '<td>' + parseFloat(elem.NOMINAL).formatMoney(0,"",",",".") + '</td>' +
                                        '<td>' + parseFloat(elem.RUPIAH).formatMoney(0,"",",",".") + '</td>' +
                                        '<td>' + elem.TGLBAYAR + '</td>' +
                                        '<td><a href="/transaksi/transaksibayar/' + elem.ID_HEADER + '"><i class="fa fa-edit"></i></a></td>' +
                                    '</tr>';
                        })
                    }
                    else {
                        detail += '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
                    }
                    detail += '</tbody></table>';
                    row.child(detail).show();           
                    tr.addClass('shown');
                    $(tr).find("a.showdetail i").attr("class","fa fa-minus-circle");
                }        
            })              
        }
        else {        
            row.child.show();
            tr.addClass('shown');
            $(this).find("i").attr("class","fa fa-minus-circle");
        }        
    }
});

})

{% endblock %}