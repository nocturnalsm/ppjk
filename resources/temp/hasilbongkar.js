$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});

if ($("#customer").length){
    var columns = [{target: 0, data: null}, {target: 1, data: "NO_BL"}, {target: 2, data: "JUMLAH_KEMASAN"},
    {target: 3, data: "NAMA"}, {target: 4, data: "IMPORTIR"}, 
    {target: 5, data: "AJU1"}, {target: 6, data: "NOPEN1"},  
    {target: 7, data: "TGLNOPEN1"},
    {target: 8, data: "HASILBONGKAR"},
    {target: 9, data: "TGLBONGKAR"},
    {target: 10, data: "TGLKELUAR"},
    {target: 11, data: "STATUS_REVISI"}
    ];
}
else {
    var columns = [{target: 0, data: null}, {target: 1, data: "NO_BL"}, {target: 2, data: "JUMLAH_KEMASAN"},
    {target: 3, data: "IMPORTIR"}, 
    {target: 4, data: "AJU1"}, {target: 5, data: "NOPEN1"},  
    {target: 6, data: "TGLNOPEN1"},
    {target: 7, data: "HASILBONGKAR"},
    {target: 8, data: "TGLBONGKAR"},
    {target: 9, data: "TGLKELUAR"},
    {target: 10, data: "STATUS_REVISI"}
    ];
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
        $('td:eq(0)', row).html('<a title="Edit" href="/transaksi/edithasilbongkar/' + data.id + '"><i class="fa fa-edit"></i></a>');
    },
    columnDefs: [
        { "orderable": false, "targets": 0 }
    ]
}); 
$("#kategori1").on("change", function(){
    var value = $(this).val();
    if (value == "Hasil Bongkar"){
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
       method: "GET",
       url: "/transaksi/filterbongkar",
       data: $("#form").serialize(),
       success: function(msg){           
           grid.clear().rows.add(msg);
           grid.columns.adjust().draw();                               
       }
    });
})