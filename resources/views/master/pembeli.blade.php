@extends('layouts.master')
@php
    $modalsize = 'modal-lg';
@endphp
@push('formbody')
<form id="form">                    
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-kode">Kode</label>
        <div class="col-md-9 mb-1">                                    
            <input readonly type="text" id="input-kode" name="input-kode" class="form-control">                        
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-nama">Customer</label>
        <div class="col-md-9 mb-1">                                    
            <select class="form-control" id="customer" name="customer">
                @foreach($customer as $cust)
                <option value="{{ $cust->id_customer }}">{{ $cust->nama_customer }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-nama">Nama Pembeli</label>
        <div class="col-md-9 mb-1">                                    
            <input type="text" id="input-nama" name="input-nama" class="form-control">                        
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-ktpnpwp">No. KTP / NPWP</label>
        <div class="col-md-9 mb-1">                                    
            <input type="text" id="input-ktpnpwp" name="input-ktpnpwp" class="form-control">                        
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-alamat">Alamat</label>
        <div class="col-md-9 mb-1">                                    
            <textarea id="input-alamat" name="input-alamat" class="form-control"></textarea>                        
        </div>
    </div>
    <div class="form-row mb-1">
        <label class="col-form-label col-md-3" for="input-keterangan">Keterangan</label>
        <div class="col-md-9 mb-1">                                    
            <textarea id="input-keterangan" name="input-keterangan" class="form-control"></textarea>                        
        </div>
    </div>
</form>
@endpush
@push('scripts_end')
    <script>
        $(function(){
                var table = $("#grid").DataTable({
                "processing": false,
                "serverSide": true,
                "ajax": "/master/getdata_pembeli",
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
                        "data": "KODE"
                    }, {
                        "target": 1,
                        "data": "NAMA"
                    },{
                        "target": 2,
                        "data": "NAMACUSTOMER"
                    },{
                        "target": 3,
                        "data": "ALAMAT"
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
                    $("#input-kode").val("OTOMATIS");
                    $("#input-nama").val("");
                    $("#input-alamat").val("");
                    $("#input-keterangan").val("");
                    $("#input-ktpnpwp").val("");
                    $("#customer").val("");
                    $("#customer").prop("disabled", false);
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
                    $("#input-kode").val(row[0].KODE);
                    $("#input-nama").val(row[0].NAMA);
                    $("#customer").val(row[0].CUSTOMER);
                    $("#customer").prop("disabled", true);
                    $("#input-keterangan").val(row[0].KETERANGAN);
                    $("#input-alamat").val(row[0].ALAMAT);
                    $("#input-ktpnpwp").val(row[0].KTPNPWP);
                    $("#input-action").val("edit");
                    $("#input-id").val(row[0].ID);
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
                            data: {_token: "{{ csrf_token() }}", action: "pembeli", input: $.param({"input-action": "delete", "id": row[0].ID})},
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
                    data: {_token: "{{ csrf_token() }}", action: "pembeli", input: $("#form").serialize()},
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
                                $("#input-alamat").val("");
                                $("#input-keterangan").val("");
                                $("#customer").val("");
                                $("#input-ktpnpwp").val("");
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