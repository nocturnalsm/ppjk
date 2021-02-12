{% extends "base.html.twig" %}
{% block body %}
<div class="card">
    <div class="card-header">
        Perekaman VO
    </div>
    <div class="card-body">
        <div class="row">            
            <div class="col-md-10">
                <form id="form" method="POST" action="/transaksi/perekamanvo?filter=1&export=1">
                    <div class="row">
                        <label class="col-md-2">Kantor</label>
                        <div class="col-md-3 col-sm-6">
                            <select class="form-control form-control-sm" id="kantor" name="kantor">
                                <option value="">Semua</option>
                                {% for ktr in datakantor %}
                                <option {{ ktr.KANTOR_ID == filters.kantor ? 'selected' : '' }} value="{{ ktr.KANTOR_ID }}">{{ ktr.URAIAN }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>           
                    {% if userlevel != 6 %}         
                    <div class="row">                        
                        <label class="col-md-2">Customer</label>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="customer" name="customer">
                                <option value="">Semua</option>
                                {% for cust in datacustomer %}
                                <option {{ cust.id_customer == filters.customer ? 'selected' : '' }} value="{{ cust.id_customer }}">{{ cust.nama_customer }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
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
                        <label class="px-sm-3 col-sm-1">Nilai</label>
                        <div class="col-md-5">
                            <input type="text" id="isikategori1_text" name="isikategori1" class="form-control form-control-sm" style="display:inline;width: 120px">
                            <select disabled id="isikategori1_select" name="isikategori1" class="form-control form-control-sm" style="display:none;width:120px">
                                <option value=""></option>
                                <option value="K">Konfirmasi</option>
                                <option value="B">Belum Inspect</option>
                                <option value="S">Sudah Inspect</option>
                                <option value="R">Revisi FD</option>
                                <option value="F">FD</option>
                                <option value="L">LS Terbit</option>
                            </select>
                        </div>
                    </div>
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
                    <div class="row">
                        <div class="col-md-2">
                            Kategori
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="kategori3" name="kategori3" value="{{ kategori3 }}">
                                <option value=""></option>
                                {% for kat in datakategori3 %}
                                <option {% if kategori3 == kat %}selected{% endif %} value="{{ kat }}">{{ kat }}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <label class="px-sm-3 col-sm-1">Periode</label>
                        <div class="col-md-5">
                            <input autocomplete="off" type="text" id="dari3" name="dari3" value="{{ dari3 }}" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
                            &nbsp;&nbsp;sampai&nbsp;&nbsp;
                            <input autocomplete="off" type="text" id="sampai3" value="{{ sampai3 }}" name="sampai3" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
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
                        <th>Importir</th>                        
                        <th>Customer</th>                        
                        <th>No Inv</th>
                        <th>No VO</th>
                        <th>Tgl VO</th>
                        <th>Tgl Tiba</th>
                        <th>Nopen</th>
                        <th>Tgl Nopen</th>
                        <th>Kode HS</th>
                        <th>Tgl Periksa</th>
                        <th>Tgl LS</th>
                        <th>Status</th>
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


var columns = [{target: 0, data: null}, {target: 1, data: "IMPORTIR"}, {target: 2, data: "CUSTOMER"{% if userlevel == 6 %},visible: false{% endif %}},  
{target: 3, data: "NO_INV"}, {target: 4, data: "NO_VO"}, {target: 5, data: "TGLVO"},
{target: 6, data: "TGLTIBA"}, {target: 7, data: "NOPEN"}, {target: 8, data: "TGLNOPEN"},
{target: 9, data: "KODE_HS_VO"}, 
{target: 10, data: "TGLPERIKSAVO"},
{target: 11, data: "TGLLS"},
{target: 12, data: "STATUSVO"}
];

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
        $('td:eq(0)', row).html('<a title="Edit" href="/transaksi/uservo/' + data.ID + '"><i class="fa fa-edit"></i></a>');
    },
    columnDefs: [
        { "orderable": false, "targets": 0 }
    ]
}); 
$("#kategori1").on("change", function(){
    var value = $(this).val();
    if (value == "Status VO"){
        $("#isikategori1_text").css("display","none");
        $("#isikategori1_text").prop("disabled", true);
        $("#isikategori1_select").css("display","inline");
        $("#isikategori1_select").prop("disabled", false);
    }
    else {
        $("#isikategori1_text").css("display","inline");
        $("#isikategori1_select").css("display","none");
        $("#isikategori1_select").prop("disabled", true);
        $("#isikategori1_text").prop("disabled", false);
    }
})
$("#preview").on("click", function(){
    $.ajax({
       method: "POST",
       url: "/transaksi/perekamanvo?filter=1",
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
{% endblock %}