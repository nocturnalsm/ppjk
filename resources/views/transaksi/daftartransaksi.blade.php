@push('stylesheets_end')
    <link href="{{ asset('jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
@endpush
@push('scripts_end')
<script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<script>
$(function(){
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
                        grid.columns(0).search(JSON.stringify(msg)).draw();
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
        columns: [{target: 0, data: "id", searchable: true}, {target: 1, data: "no_inv", searchable: false},
                {target: 2, data: "tgl_tiba", searchable: false},
                {target: 3, data: "jumlah_kemasan", searchable: false},
                {target: 4, data: "noaju", searchable: false},
                {target: 5, data: "nopen", searchable: false},
                {target: 6, data: "tgl_nopen", searchable: false},
                {target: 7, data: "nama_customer", searchable: false},
                {target: 8, data: "tgl_keluar", searchable: false},
                {target: 9, data: "no_bl", searchable: false}, 
                {target: 10, data: "no_form", searchable: false},
                {target: 11, data: "no_po", searchable: false}
                ],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ]
    })
})
</script>
@endpush