$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
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
        columns: [{target: 0, data: "NO_INV"}, 
              {target: 1, data: "NO_BL"},
              {target: 2, data: "JUMLAH_KEMASAN"},
              {target: 3, data: "NAMA"}, {target: 4, data: "IMPORTIR"}, 
              {target: 4, data: "TGLTIBA"},{target: 5, data: "TGLKELUAR"},
              {target: 6, data: "NOAJU"},
              {target: 7, data: "NOPEN"},{target: 8, data: "TGLNOPEN"},
              {target: 9, data: "NO_PO"},
              {target: 10, data: "NO_SC"}, 
              {target: 11, data: "JUMLAH_KONTAINER"}
             ],
    }
); 
$("#preview").on("click", function(){
    $.ajax({
       method: "POST",
       url: "/transaksi/filter",
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