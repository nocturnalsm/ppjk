@extends('layouts.master')
@push('formbody')
<form id="form">                    
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="mb-1">                        
        <label for="input-kode">Kode</label>
        <input type="text" maxlength="10" id="input-kode" name="input-kode" class="form-control">                        
    </div>
    <div class="mb-1">                        
        <label for="input-uraian">Nama Pelabuhan Muat</label>
        <input type="text" maxlength="99" id="input-uraian" name="input-uraian" class="form-control">                        
    </div>
</form>
@endpush
@push('scripts_end')
    <script>
        $(function(){
            var table = $("#grid").DataTable({
                "processing": false,
                "serverSide": true,
                "ajax": "/master/getdata_pelmuat",
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
                        "data": "uraian"
                    }],
                rowCallback: function(row, data)
                {
                    $(row).attr("row-id", data.pelmuat_id);
                },
                buttons: [{
                text: 'Tambah',
                name: 'add',        // DO NOT change name,
                action: function () {
                    $("#modalform .modal-title").html("Tambah Data");
                    $("#input-kode").val("");
                    $("#input-uraian").val("");
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
                    $("#input-uraian").val(row[0].uraian);
                    $("#input-action").val("edit");
                    $("#input-id").val(row[0].pelmuat_id);
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
                            data: {_token: "{{ csrf_token() }}", action: "pelmuat", input: $.param({"input-action": "delete", "id": row[0].pelmuat_id})},
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
                    data: {_token: "{{ csrf_token() }}", action: "pelmuat", input: $("#form").serialize()},
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
                                $("#input-uraian").val("");
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
        })
    </script>
@endpush