{% extends 'master.php' %}
{% set modalsize = 'modal-lg' %}
{% block formbody %}
<form id="form">                    
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-rate">Rate</label>
        <div class="col-md-9 mb-1">                                    
            <input type="text" id="input-rate" name="input-rate" class="form-control">                        
        </div>
    </div>
</form>
{% endblock %}
{% block scripts %}
    $(function(){
        var table = $("#grid").DataTable({
        "processing": false,
        "serverSide": true,
        "ajax": "/master/getdata_ratedpp",
        "paging": false,
        dom: 'Bfrtip',        // element order: NEEDS BUTTON CONTAINER (B) ****
        select: 'single',     // enable single row selection
        responsive: true,     // enable responsiveness,
        rowId: 0,
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
        columns: [
            {
                "target": 0,
                "data": "RATE"
            }],
        rowCallback: function(row, data)
        {
            $(row).attr("row-id", data.ID);
        },
        buttons: [{
        text: 'Tambah',
        name: 'add',        // DO NOT change name,
        action: function () {
            $("#modalform .modal-title").html("Tambah Data");
            $("#input-rate").val("");
            $("#input-action").val("add");
            $("#modalform").modal("show");
            $("#input-rate").focus();
        }
        },
        {
        extend: 'selected', // Bind to Selected row
        text: 'Edit',
        name: 'edit',        // DO NOT change name
        action: function (e, dt) {
            var row = dt.rows( { selected: true } ).data();           
            $("#modalform .modal-title").html("Edit Data");
            $("#input-rate").val(row[0].RATE);
            $("#input-action").val("edit");
            $("#input-id").val(row[0].RATE_ID);
            $("#modalform").modal("show");
        }
        },
        {
        extend: 'selected', // Bind to Selected row
        text: 'Hapus',
        name: 'delete',      // DO NOT change name
        action: function (e, dt){
            $("#modal .btn-ok").removeClass("d-none");
            $("#modal .btn-close").html("Batal");
            $("#modal .modal-body").html("Apakah Anda ingin menghapus data ini?");        
            $("#modal .btn-ok").html("Ya").on("click", function(){
                var row = dt.rows( { selected: true } ).data();
                $.ajax({
                    url: "/master/crud",
                    data: {action: "ratedpp", input: $.param({"input-action": "delete", "id": row[0].RATE})},
                    type: "POST",
                    success: function(msg) {
                        $("#modal .btn-ok").addClass("d-none");
                        if (typeof msg.error != 'undefined'){
                            $("#modal .modal-body").html(msg.error);
                        }
                        else {
                            table.ajax.reload();
                            $("#modal .modal-body").html("Penghapusan berhasil");
                        }
                        setTimeout(function(){
                            $("#modal").modal("hide");
                        }, 5000);
                    }
                })
            });                        
            $("#modal").modal("show").on("hidden.bs.modal", function(){
                $("#modal .btn-ok").off("click");            
                $("#modal .btn-close").html("Tutup");
            })
        }
    }]
    })
    $('#modalform').on('shown.bs.modal', function () {
        $('#input-rate').focus();
    })
    $("#saveform").on("click", function(){
        $(this).addClass("disabled");
        $(".loader").show()
        $.ajax({
            url: "/master/crud",
            data: {action: "ratedpp", input: $("#form").serialize()},
            type: "POST",
            success: function(msg) {
                if (typeof msg.error != 'undefined'){
                    $("#modal .modal-body").html(msg.error);
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 1000);
                    return false;
                }
                else {
                    if ($("#input-action").val() != "add"){
                        $("#modalform").modal("hide");
                    }            
                    else {
                        $("#input-rate").val("");
                        $("#input-rate").focus();
                    }
                    table.ajax.reload();
                    $("#modal .modal-body").html("Data tersimpan");
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 1000);  
                }
            },
            complete: function(){
                $("#saveform").removeClass("disabled");
                $(".loader").hide();
            }
        });
    });
    $("#input-rate").inputmask({
        alias: "percentage",
        integerDigits:3,
        digits: 0,
        max:1000,
        allowMinus:false    
    });

})
{% endblock %}