{% extends 'master.php' %}
{% set modalsize = 'modal-lg' %}
{% block formbody %}
<form id="form">                    
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-kode">Kode</label>
        <div class="col-md-9 mb-1">                                    
            <input type="text" id="input-kode" name="input-kode" class="form-control">                        
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-nama">Nama Produk</label>
        <div class="col-md-9 mb-1">                                    
            <input type="text" id="input-nama" name="input-nama" class="form-control">                        
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-hscode">Spesifikasi</label>
        <div class="col-md-9 mb-1">                                    
            <textarea id="input-spesifikasi" name="input-spesifikasi" class="form-control"></textarea>                        
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-hscode">Kode HS</label>
        <div class="col-md-9 mb-1">                                
            <input type="text" id="input-hscode" name="input-hscode" class="form-control">                        
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-satuan">Satuan</label>
        <div class="col-md-9 mb-1">                                
            <select id="input-satuan" name="input-satuan" class="form-control">
                {% for sat in satuan %}
                <option value="{{ sat.id }}">{{ sat.satuan }}</option>
                {% endfor %}
            </select>                        
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-hscode">Harga</label>
        <div class="col-md-9 mb-1">                                
            <input type="text" id="input-harga" name="input-harga" class="form-control number text-right">                        
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-tglrekam">Tgl Rekam</label>
        <div class="col-md-9 mb-1">                                
            <input type="text" id="input-tglrekam" name="input-tglrekam" class="form-control datepicker">                        
        </div>
    </div>
</form>
{% endblock %}
{% block scripts %}
    $(function(){
        var table = $("#grid").DataTable({
        "processing": false,
        "serverSide": true,
        "ajax": "/master/getdata_produk",
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
                "data": "kode"
            }, {
                "target": 1,
                "data": "nama"
            },{
                "target": 2,
                "data": "hscode"
            },{
                "target": 3,
                "data": "satuan"
            },{
                "target": 4,
                "data": "harga"
            }],
        rowCallback: function(row, data)
        {
            $(row).attr("row-id", data.id);
        },
        buttons: [{
        text: 'Tambah',
        name: 'add',        // DO NOT change name,
        action: function () {
            $("#modalform .modal-title").html("Tambah Data");
            $("#input-kode").val("");
            $("#input-nama").val("");
            $("#input-hscode").val("");
            $("#input-satuan").val("");
            $("#input-spesifikasi").val("");
            $("#input-harga").val("");
            $("#input-tglrekam").val("");
            $("#input-action").val("add");
            $("#modalform").modal("show");
            $("#modalform input").eq(0).focus();
        }
        },
        {
        extend: 'selected', // Bind to Selected row
        text: 'Edit',
        name: 'edit',        // DO NOT change name
        action: function (e, dt) {
            var row = dt.rows( { selected: true } ).data();           
            $("#modalform .modal-title").html("Edit Data");
            $("#input-kode").val(row[0].kode);
            $("#input-nama").val(row[0].nama);
            $("#input-hscode").val(row[0].hscode);
            $("#input-spesifikasi").val(row[0].spesifikasi);
            $("#input-satuan").val(row[0].satuan_id);
            $("#input-harga").val(row[0].harga);
            $("#input-tglrekam").val(row[0].tgl_rekam);
            $("#input-action").val("edit");
            $("#input-id").val(row[0].id);
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
                    data: {action: "produk", input: $.param({"input-action": "delete", "id": row[0].id})},
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
        $('#input-kode').focus();
    })
    $("#saveform").on("click", function(){
        $(this).addClass("disabled");
        $(".loader").show()
        $.ajax({
            url: "/master/crud",
            data: {action: "produk", input: $("#form").serialize()},
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
                        $("#input-kode").val("");
                        $("#input-nama").val("");
                        $("#input-hscode").val("");
                        $("#input-satuan").val("");
                        $("#input-spesifikasi").val("");
                        $("#input-harga").val("");
                        $("#input-tglrekam").val("");
                        $("#input-kode").focus();
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
    $(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
    $(".number").inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
    });
    $("#input-hscode").inputmask("9999.99.99");
    $("#input-kode").inputmask("999-99-****");
    
})
{% endblock %}