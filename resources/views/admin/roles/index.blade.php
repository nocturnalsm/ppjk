@extends('layouts.base')
@section('main')
@include('layouts.inputmodal',["modalsize" => "modal-lg"])
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Group User</h1>
      </div>
      <div class="col-sm-6 text-right">
        <button id="add" class="btn btn-primary">Tambah Group User</button>
      </div>
    </div>
</section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-body">
              <table id="table" class="table table-striped table-sm table-hover">
                <thead>
                <tr>
                  <th class="border-top-0">Nama Group</th>
                  <th class="border-top-0">Aksi</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
      <!-- /.row -->
</section>
    <!-- /.content -->
<div id="flash"></div>
@endsection
@push('styles')
#modalform .permissions {
  max-height: 300px;
  overflow: scroll;
}
@endpush
@push('scripts_end')
<script>
  $(function () {
    window.table = $('#table').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "dom": "rtip",
      "autoWidth": false,
      "processing": true,
      "serverSide": true,
      "ajax": {
          url: "{{ route('roles.index') }}"
      },
      "columns": [
            { "data"  : "name" },
            {
                "data": null,
                "sortable": false,
                "render": function(data, type, row){
                    return '<button class="btn btn-sm btn-primary btn-edit" data-id="' +
                            row.id +'"> ' +
                           '<i class="fa fa-edit"></i>&nbsp;Edit' +
                           '</button>&nbsp;&nbsp;' +
                           '<button class="btn btn-sm btn-danger btn-delete" data-id="' +
                            row.id +'"> ' +
                           '<i class="fa fa-trash"></i>&nbsp;Hapus' +
                           '</button>';
                }
            }
        ]
    });
    function flashMessage(response){
        $("#flash").html("");
        $("#flash").html(response);
    }
    $("#add").on("click", function(){
        $.ajax({
            url: "{{ route('roles.create') }}",
            method: "GET",
            success: function(response){
                $("#modalform .modal-body").html("");
                $("#modalform .modal-title").html("Tambah Group User");
                $("#modalform .modal-body").html(response);
                $("#modalform").modal("show");
                $("#name").focus();
            },
            error: function(jqXHR, textStatus, errorThrown){
                //flashMessage(jqXHR.responseText);
            }
        })
    })
    $("body").on("submit", "#form", function(e){
        e.preventDefault();
        $.ajax({
            url: $("#form").attr("action"),
            method: $("#form input[name=action]").val() == "edit" ? "PUT" : "POST",
            data: $("#form").serialize(),
            success: function(response){
                if (response.errors){
                    var errors = Object.keys(response.errors);
                    $(errors).each(function(index, elem){
                        $("#" +elem).addClass("is-invalid");
                        $("#" +elem).next(".invalid-feedback").html(response.errors[elem][0]);
                    })
                    $("#form .is-invalid").first().focus();
                }
                else {
                    if ($("#form input[name=action]").val() == "add"){
                        $("#add").trigger("click");
                    }
                    else {
                        $("#modalform").modal("hide");
                    }
                    table.draw();
                }
            }
        })
    });
    $("body").on("input", "form input,select", function(){
        $(this).removeClass("is-invalid");
        $(this).next(".invalid-feedback").html("");
    });
    $("body").on("click",".btn-edit", function(){
        $.ajax({
            url: "{{ route('roles.index') }}/" + $(this).attr("data-id") +"/edit",
            method: "GET",
            success: function(response){
                $("#modalform .modal-body").html("");
                $("#modalform .modal-title").html("Edit Group User");
                $("#modalform .modal-body").html(response);
                $("#modalform").modal("show");
                $("#name").focus();
            },
            error: function(jqXHR, textStatus, errorThrown){
                //flashMessage(jqXHR.responseText);
            }
        })
    })
    $("body").on("click",".btn-delete", function(){
      var id = $(this).attr("data-id");
      $.ajax({
          method: "DELETE",
          data: {"_token": "{{ csrf_token() }}","id": id},
          url: "{{ url('admin/roles/delete') }}",
          success: function(response){
              table.draw();
          },
          error: function(jqXHR, textStatus, errorThrown){
              //flashMessage(jqXHR.responseText);
          }
      })
    })
    $("body").on("change", "#checkall", function(){
        if($(this).prop("checked")){
            $(".check-permission").prop("checked", true);
        }
    })
    $("body").on("change", ".check-permission", function(){
        if(!$(this).prop("checked")){
            $("#checkall").prop("checked", false);
        }
    });
    $('input[name=searchinput]').on( 'search', function () {
        window.table.search($(this).val()).draw();
    });
  });
</script>
@endpush
