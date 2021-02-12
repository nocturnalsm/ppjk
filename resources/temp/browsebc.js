$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});


var columns = [{target: 0, data: null}, {target: 1, data: "IMPORTIR"}, {target: 2, data: "CUSTOMER"}, 
{target: 3, data: "TGLTIBA"}, 
{target: 4, data: "NOPEN"}, {target: 5, data: "TGLNOPEN"},
{target: 6, data: "NO_BL"},
{target: 7, data: "NO_INV"},
{target: 8, data: "JUMLAH_KEMASAN"},
{target: 9, data: "TGLPERIKSA"},
{target: 10, data: "HASIL_PERIKSA"}, 
{target: 11, data: "TGLSPPB"},
{target: 12, data: "TGLKELUAR"},
{target: 13, data: "LEVEL_DOK", render: function(data){
    if (data == ""){
        return "";
    }
    else if (data == "Hijau"){
         return '<h4><span class="badge badge-success">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></h4>';         
    } 
    else if (data == "Kuning"){
        return '<h4><span class="badge badge-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></h4>';         
    }
    else if (data == "Merah"){
        return '<h4><span class="badge badge-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></h4>';         
    } 
 }
},{target: 14, data: "TGLDOKTRM"}, 
{target: 15, data: "JALURDOK", render: function(data){
    if (data == ""){
        return "";
    }
    else if (data == "Hijau"){
         return '<h4><span class="badge badge-success">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></h4>';         
    } 
    else if (data == "Kuning"){
        return '<h4><span class="badge badge-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></h4>';         
    }
    else if (data == "Merah"){
        return '<h4><span class="badge badge-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></h4>';         
    } 
 }
} 
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
        $('td:eq(0)', row).html('<a title="Edit" href="/transaksi/userbc/' + data.ID + '"><i class="fa fa-edit"></i></a>');
    },
    columnDefs: [
        { "orderable": false, "targets": 0 }
    ]
}); 
/*
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
*/
$("#preview").on("click", function(){
    $.ajax({
       method: "POST",
       url: "/transaksi/perekamanbc?filter=1",
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