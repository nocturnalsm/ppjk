@extends('layouts.master')
@push('formbody')
<form id="form">
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="mb-1">                        
        <label for="orangeForm-name">Bank</label>
        <select class="form-control validate" id="input-bank" name="input-bank">
            @foreach($databank as $bank)
            <option value="{{ $bank->BANK_ID }}">{{ $bank->BANK }}</option>
            @endforeach
        </select>                        
    </div>
    <div class="mb-1">                        
        <label for="orangeForm-name">No. Rekening</label>
        <input type="text" id="input-norekening" name="input-norekening" class="form-control validate">                        
    </div>
    <div class="mb-1">                        
        <label for="orangeForm-email">Nama Rekening</label>
        <input type="text" id="input-nama" name="input-nama" class="form-control validate">                        
    </div>
</form>
@endpush
@push('scripts_end')
    <script src="{{ asset('js/jquery.inputmask.bundle.js') }}" type="text/javascript"></script>
    <script>
        $(function(){
            var table = $("#grid").DataTable({
                    "processing": false,
                    "serverSide": true,
                    "ajax": "/master/getdata_rekening",
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
                            "data": "BANK"
                        },
                        {
                            "target": 1,
                            "data": "NO_REKENING"
                        },
                        {
                            "target": 2,
                            "data": "NAMA"
                        }],
                    rowCallback: function(row, data)
                    {
                        $(row).attr("row-id", data.REKENING_ID);
                    },
                    buttons: [{
                    text: 'Tambah',
                    name: 'add',        // DO NOT change name,
                    action: function () {
                        $("#modalform .modal-title").html("Tambah Data");
                        $("#input-id").val("");
                        $("#input-norekening").val("");
                        $("#input-bank").val("");
                        $("#input-nama").val("");
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
                        $("#input-nama").val(row[0].NAMA);
                        $("#input-bank").val(row[0].BANK_ID);
                        $("#input-norekening").val(row[0].NO_REKENING);
                        $("#input-action").val("edit");
                        $("#input-id").val(row[0].REKENING_ID);
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
                            console.log(row);
                            $.ajax({
                                url: "/master/crud",
                                data: {_token: "{{ csrf_token() }}", action: "rekening", input: $.param({"input-action": "delete", "id": row[0].REKENING_ID})},
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
                        data: {_token: "{{ csrf_token() }}", action: "rekening", input: $("#form").serialize()},
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
                                    $("#input-bank").val("");
                                    $("#input-norekening").val("");
                                    $("#input-nama").val("");
                                    $("#input-bank").focus();
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