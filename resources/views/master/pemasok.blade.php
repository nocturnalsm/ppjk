@extends('layouts.master')
@push('formbody')
<form id="form">
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="mb-1">
        <label for="input-nama">Nama</label>
        <input type="text" id="input-nama" name="input-nama" class="form-control validate">
    </div>
    <div class="mb-1">
        <label for="input-alamat">Alamat</label>
        <textarea id="input-alamat" name="input-alamat" class="form-control validate" rows="5"></textarea>
    </div>
    <div class="mb-1">
        <label for="input-alamat">Negara</label>
        <select name="input-negara" id="input-negara" class="form-control">
          <option value=""></option>
          @foreach($negara as $ngr)
          <option value="{{ $ngr->id_country }}">{{ $ngr->country_name }}</option>
          @endforeach
        </select>
    </div>
    <div class="mb-1">
        <label for="input-telepon">Telepon</label>
        <input type="text" id="input-telepon" name="input-telepon" class="form-control validate">
    </div>
    <div class="mb-1">
        <label for="input-email">Fax</label>
        <input type="text" id="input-fax" name="input-fax" class="form-control validate">
    </div>
    <div class="mb-1">
        <label for="input-link">Website</label>
        <input type="text" id="input-link" name="input-link" class="form-control validate">
    </div>
</form>
@endpush
@push('scripts_end')
    <script>
        $(function(){
            var table = $("#grid").DataTable({
            "processing": false,
            "serverSide": true,
            "ajax": "/master/getdata_pemasok",
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
                    "data": "nama_pemasok"
                }, {
                    "target": 1,
                    "data": "alamat_pemasok"
                }, {
                    "target": 2,
                    "data": "telp_pemasok"
                }, {
                    "target": 3,
                    "data": "negara"
                }, {
                    "target": 4,
                    "data": "fax_pemasok"
                },{
                    "target": 4,
                    "data": "link_pemasok"
                }],
            rowCallback: function(row, data)
            {
                $(row).attr("row-id", data.id_pemasok);
            },
            buttons: [{
            text: 'Tambah',
            name: 'add',        // DO NOT change name,
            action: function () {
                $("#modalform .modal-title").html("Tambah Data");
                $("#input-negara").val("");
                $("#input-nama").val("");
                $("#input-alamat").val("");
                $("#input-telepon").val("");
                $("#input-fax").val("");
                $("#input-link").val("");
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
                $("#input-negara").val(row[0].negara_pemasok);
                $("#input-nama").val(row[0].nama_pemasok);
                $("#input-alamat").val(row[0].alamat_pemasok);
                $("#input-telepon").val(row[0].telp_pemasok);
                $("#input-fax").val(row[0].fax_pemasok);
                $("#input-link").val(row[0].link_pemasok);
                $("#input-action").val("edit");
                $("#input-id").val(row[0].id_pemasok);
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
                        data: {_token: "{{ csrf_token() }}", action: "pemasok", input: $.param({"input-action": "delete", "id": row[0].id_pemasok})},
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
            $('#input-nama').focus();
        })
        $("#saveform").on("click", function(){
            $(this).addClass("disabled");
            $(".loader").show()
            $.ajax({
                url: "/master/crud",
                data: {_token: "{{ csrf_token() }}", action: "pemasok", input: $("#form").serialize()},
                type: "POST",
                success: function(msg) {
                    if (typeof msg.error != 'undefined'){
                        $("#modal .modal-body").html(msg.error);
                        $("#modal").modal("show");
                        setTimeout(function(){
                            $("#modal").modal("hide");
                        }, 1000);
                    }
                    else {
                        if ($("#input-action").val() != "add"){
                            $("#modalform").modal("hide");
                        }
                        else {
                            $("#input-negara").val("");
                            $("#input-nama").val("");
                            $("#input-alamat").val("");
                            $("#input-telepon").val("");
                            $("#input-fax").val("");
                            $("#input-email").val("");
                            $("#input-nama").focus();
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
