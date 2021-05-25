@extends('layouts.master')
@push('formbody')
<form id="form">
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="mb-1">
        <label for="input-jenis">Jenis Truk</label>
        <input type="text" id="input-jenis" name="input-jenis" class="form-control">
    </div>
</form>
@endpush
@push('scripts_end')
    <script>
        $(function(){
            var table = $("#grid").DataTable({
                "processing": false,
                "serverSide": true,
                "ajax": "/master/getdata_jenistruk",
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
                        "data": "JENIS_TRUK"
                    }],
                rowCallback: function(row, data)
                {
                    $(row).attr("row-id", data.JENISTRUK_ID);
                },
                buttons: [{
                text: 'Tambah',
                name: 'add',        // DO NOT change name,
                action: function () {
                    $("#modalform .modal-title").html("Tambah Data");
                    $("#input-jenis").val("");
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
                    $("#input-jenis").val(row[0].JENIS_TRUK);
                    $("#input-action").val("edit");
                    $("#input-id").val(row[0].JENISTRUK_ID);
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
                            data: {_token: "{{ csrf_token() }}", action: "jenistruk", input: $.param({"input-action": "delete", "id": row[0].JENISTRUK_ID})},
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
                    data: {_token: "{{ csrf_token() }}", action: "jenistruk", input: $("#form").serialize()},
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
                                $("#input-jenis").val("");
                                $("#input-jenis").focus();
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
