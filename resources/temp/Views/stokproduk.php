{% extends "base.html.twig" %}
{% block body %}
<div class="card">
    <div class="card-header">
        Browse Stok per Produk
    </div>
    <div class="card-body">
        <form id="form" method="POST" action="/transaksi/browsestokproduk?filter=1&export=1">
        <div class="row">            
            <div class="col-md-10">
                <!--
                <div class="row">
                    <label class="col-md-2">Kantor</label>
                    <div class="col-md-3 col-sm-6">
                        <select class="form-control form-control-sm" id="kantor" name="kantor">
                            {% for ktr in datakantor %}
                            <option value="{{ ktr.KANTOR_ID }}">{{ ktr.URAIAN }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div> 
                -->
                {% if userlevel != 6 %}                   
                <!--
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
                -->
                {% endif %}
                <div class="row">
                    <label class="col-md-2">Importir</label>
                    <div class="col-md-3">
                        <select class="form-control form-control-sm" id="importir" name="importir">
                            {% if dataimportir|length != 1 %}
                            <option value="">Semua</option>
                            {% endif %}
                            {% for imp in dataimportir %}
                            <option value="{{ imp.IMPORTIR_ID }}">{{ imp.NAMA }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
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
                    <label class="px-md-3 col-md-1">Nilai</label>
                    <div class="col-md-3">
                        <input type="text" id="isikategori1_text" name="isikategori1" class="form-control form-control-sm" style="display:inline;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        Kategori
                    </div>
                    <div class="col-md-3">
                        <select class="form-control form-control-sm" id="kategori2" name="kategori2" value="{{ kategori2 }}">
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
                        <th>Opsi</th>
                        <th>Kode Produk</th>                        
                        <th>Saldo Awal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Saldo Akhir</th>                  
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>      
    </div>
</div>  
{% endblock %}

{% block scripts %}
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

var columns = [{target: 0, data: null}, {target: 1, data: "kode"}, {target: 2, data: "kemasansawal"}, 
    {target: 3, data: "kemasanmasuk"}, {target: 4, data: "kemasankeluar"},
    {target: 5, data: "kemasansakhir"} 
];
var printvalues  = function(jmlkemasan, jmlsatuan, kemasan, satuan){
    jmlkemasan = parseFloat(jmlkemasan).formatMoney(2,"",",",".");
    jmlsatuan = parseFloat(jmlsatuan).formatMoney(2,"",",",".");
    
    if (jmlkemasan){
        var html = jmlkemasan + (kemasan ? " " + kemasan : "");
        if (jmlsatuan){
            html += "<br>" + jmlsatuan + (satuan ? " " + satuan : ""); 
        }
    }
    return html
}
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
        $('td:eq(2)', row).html(printvalues(data.kemasansawal, data.satuansawal, data.satuankemasan, data.satuan));        
        $('td:eq(3)', row).html(printvalues(data.kemasanmasuk, data.satuanmasuk, data.satuankemasan, data.satuan));        
        $('td:eq(4)', row).html(printvalues(data.kemasankeluar, data.satuankeluar, data.satuankemasan, data.satuan));        
        $('td:eq(5)', row).html(printvalues(data.kemasansakhir, data.satuansakhir, data.satuankemasan, data.satuan));        
    },
    columnDefs: [
        { "orderable": false, "targets": 0 }
    ]
}); 
$("#preview").on("click", function(){
    $.ajax({
       method: "POST",
       url: "/transaksi/browsestokproduk?filter=1",
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
});
$("#kategori1").on("change", function(){
    var value = $(this).val().trim() == "";
    $("#isikategori1 option[value='']").prop("selected", value);
})
$("#isikategori1").on("change", function(){
    var value = $(this).find("option:selected").val().trim() != "";
    $("#kategori1 option").eq(1).prop("selected", value);
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
            console.log(row.data());
            var data = Array();
            $.ajax({
                url: '/transaksi/detailstokproduk',
                data: {id: row.data().id, form: $("#form").serialize()},
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
                                    '<th>Kode Barang</th>' +
                                    '<th>Importir</th>' +
                                    '<th>Tanggal</th>' +
                                    '<th>Masuk</th>' +
                                    '<th>Keluar</th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody>';
                    if (data.length > 0){
                        $(data).each(function(index, elem){
                            detail += '<tr>' +
                                        '<td>' + elem.KODEBARANG + '</td>' +
                                        '<td>' + elem.NAMA + '</td>' +
                                        '<td>' + elem.TANGGAL + '</td>' +
                                        '<td>' + printvalues(elem.kemasanmasuk, elem.satuanmasuk, elem.satuankemasan, elem.satuan) + '</td>' +
                                        '<td>' + printvalues(elem.kemasankeluar, elem.satuankeluar, elem.satuankemasan, elem.satuan) + '</td>' +
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
} );
{% endblock %}