$(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
$("#btnsearch").on("click", function(){
    $("#formsearch").submit();
})
$("#formsearch").on("submit", function(e){
    e.preventDefault();
    $(".loader").show()
    $.ajax({
        url: "/transaksi/find",
        data: $("#formsearch").serialize(),
        type: "GET",
        success: function(msg) {
            if (msg.length == 0){
                $("#modal .modal-body").html("Data tidak ditemukan");
                $("#modal").modal("show");
                setTimeout(function(){
                    $("#modal").modal("hide");
                }, 1000);  
            }
            else {
                if (msg.length == 1 && $("input[name='searchtype']").val() == "kontainer"){
                    window.location.href = "/transaksi/" +msg[0].id;
                }
                else {
                    grid.search(JSON.stringify(msg)).draw();
                }
            }
        },
        complete: function(){
            $(".loader").hide();
        }
    });
})
var grid = $("#gridtransaksi").DataTable({
    processing: false,
    serverSide: true,
    ajax: {
        "url": "/transaksi/get_daftar",
        "type": "POST"
      },
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
    rowCallback: function(row, data)
    {                
        $(row).attr("id-transaksi", data[0]);
        $('td:eq(0)', row).html('<a title="Edit" href="/transaksi/' + data.id + '"><i class="fa fa-edit"></i></a>');
    },    
    order: [[1, 'asc']],
    columns: [{target: 0, data: null}, {target: 1, data: "no_inv"},
              {target: 2, data: "tgl_tiba"},
              {target: 3, data: "jumlah_kemasan"},
              {target: 4, data: "noaju"},
              {target: 5, data: "nopen"},{target: 6, data: "tgl_nopen"},
              {target: 7, data: "nama_customer"},
              {target: 8, data: "tgl_keluar"},
              {target: 9, data: "no_bl"}, 
              {target: 10, data: "no_form"},{target: 11, data: "no_po"}
             ],
    "columnDefs": [
        { "orderable": false, "targets": 0 }
    ]
})