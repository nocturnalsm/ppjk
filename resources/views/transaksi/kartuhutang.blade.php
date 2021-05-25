@extends('layouts.base')
@section('main')
<div class="card">
    <div class="card-header">
        Kartu Hutang
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10">
                <form id="form" method="POST" action="/transaksi/kartuhutang?filter=1&export=1">
                    @csrf
                    <div class="row">
                        <label class="col-md-2">Kantor</label>
                        <div class="col-md-3 col-sm-6">
                            <select class="form-control form-control-sm" id="kantor" name="kantor">
                                <option value="">Semua</option>
                                @foreach($datakantor as $ktr)
                                <option value="{{ $ktr->KANTOR_ID }}">{{ $ktr->URAIAN }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @can('customer.view')
                    <div class="row">
                        <label class="col-md-2">Customer</label>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="customer" name="customer">
                                <option value="">Semua</option>
                                @foreach($datacustomer as $cust)
                                <option value="{{ $cust->id_customer }}">{{ $cust->nama_customer }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endcan
                    <div class="row">
                        <label class="col-md-2">Importir</label>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="importir" name="importir">                                
                                @if(count($dataimportir) != 1)
                                <option value="">Semua</option>
                                @endif
                                @foreach($dataimportir as $imp)
                                <option value="{{ $imp->IMPORTIR_ID }}">{{ $imp->NAMA }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2">Shipper</label>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="shipper" name="shipper">
                                <option value="">Semua</option>
                                @foreach($datashipper as $ship)
                                <option value="{{ $ship->id_pemasok }}">{{ $ship->nama_pemasok }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            Kategori
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="kategori2" name="kategori2">
                                <option value=""></option>
                                @foreach($datakategori2 as $kat)
                                <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="px-sm-3 col-sm-1">Periode</label>
                        <div class="col-md-5">
                            <input autocomplete="off" type="text" id="dari2" name="dari2" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
                            &nbsp;&nbsp;sampai&nbsp;&nbsp;
                            <input autocomplete="off" type="text" id="sampai2" name="sampai2" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
                        </div>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 px-sm-2 pt-2">
                <a id="preview" class="btn btn-primary">Filter</a>
                <a id="export" class="btn btn-primary disabled">Export</a>
            </div>
        </div>
        </form>
        <div class="row mt-4 pt-4">
            <div class="col" id="divtable">
                <table width="100%" id="grid" class="table">
                    <thead>
                        <th></th>
                        <th>Kantor</th>
                        <th>Importir</th>
                        <th>Customer</th>
                        <th>Shipper</th>
                        <th>Jns Dok</th>
                        <th>Nopen</th>
                        <th>Tgl Nopen</th>
                        <th>No Inv</th>
                        <th>Tgl Inv</th>
                        <th>Jth Tempo</th>
                        <th>Curr</th>
                        <th>CIF</th>
                        <th>Penarikan</th>
                        <th>Saldo</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('stylesheets_end')
    <link href="{{ asset('jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
@endpush
@push('scripts_end')
<script type="text/javascript" src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<script>
    $(function(){

        $(".datepicker").datepicker({dateFormat: "dd-mm-yy"});

        Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
            places = !isNaN(places = Math.abs(places)) ? places : 2;
            symbol = symbol !== undefined ? symbol : "";
            thousand = thousand || ",";
            decimal = decimal || ".";
            var number = this,
                    negative = number < 0 ? "-" : "",
                    i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
                    j = (j = i.length) > 3 ? j % 3 : 0;
            return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
        };
        var columns = [{target:0, data: null, orderable: false, classname: "show-control"}, {target: 1, data: "KANTOR"},
        {target: 2, data: "IMPORTIR"}, {target: 3, data: "CUSTOMER" @cannot('customer.view') , visible: false @endcannot},
        {target: 4, data: "SHIPPER"}, {target: 5, data: "JENISDOKUMEN"},
        {target: 6, data: "NOPEN"}, {target: 7, data: "TGLNOPEN"},
        {target: 8, data: "NO_INV"}, {target: 9, data: "TGLINV"},
        {target: 10, data: "TGLJTHTEMPO"},{target: 11, data: "MATAUANG"},
        {target: 12, data: "CIF"}, {target: 13, data: "TOT_PAYMENT"},
        {target: 14, data: "SALDO"}];

        var grid = $("#grid").DataTable({responsive: false,
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
            order: [[0, 'asc']],
            columns: columns,
            rowCallback: function(row, data)
            {
                $(row).attr("id-transaksi", data[0]);
                $('td:eq(0)', row).html('<a title="Detail" class="showdetail"><i class="fa fa-plus-circle"></i></a>');
                $('td:eq(14)', row).html(parseFloat(data.SALDO).formatMoney(0,"",",","."));
                $('td:eq(12)', row).html(parseFloat(data.CIF).formatMoney(3,"",",","."));
                $('td:eq(13)', row).html(parseFloat(data.TOT_PAYMENT).formatMoney(0,"",",","."));
            }
        });
        $("#kategori1").on("change", function(){
            var value = $(this).val();
            if (value == "TOP"){
                $("#isikategori1_text").css("display","none");
                $("#isikategori1_text").prop("disabled", true);
                $("#isikategori2_select").css("display","none");
                $("#isikategori2_select").prop("disabled", true);
                $("#isikategori1_select").css("display","inline");
                $("#isikategori1_select").prop("disabled", false);
            }
            else if (value == "No Inv"){
                $("#isikategori1_text").css("display","inline");
                $("#isikategori1_text").prop("disabled", false);
                $("#isikategori1_select").css("display","none");
                $("#isikategori1_select").prop("disabled", true);
                $("#isikategori2_select").css("display","none");
                $("#isikategori2_select").prop("disabled", true);
            }
            else {
                $("#isikategori1_text").css("display","none");
                $("#isikategori1_select").css("display","none");
                $("#isikategori1_select").prop("disabled", true);
                $("#isikategori1_text").prop("disabled", true);
                $("#isikategori2_select").css("display","inline");
                $("#isikategori2_select").prop("disabled", false);
            }
        })
        $("#preview").on("click", function(){
            $.ajax({
            method: "POST",
            url: "/transaksi/kartuhutang?filter=1",
            data: $("#form").serialize(),
            success: function(msg){
                    grid.clear().rows.add(msg);
                    grid.columns.adjust().draw();
                    if (msg.length == 0){
                        $("#export").addClass("disabled");
                    }
                    else {
                        $("#export").removeClass("disabled");
                    }
            }
            });
        })
        $("#form input, select").on("change", function(){
            $("#export").addClass("disabled");
        })
        $("#export").on("click", function(){
            $("#form").submit();
        })
        $('body').on('click', 'a.showdetail', function () {
            var tr = $(this).closest('tr');
            var row = grid.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                $(this).find("i").attr("class","fa fa-plus-circle");
            }
            else {
                var child = row.child();
                if (typeof child == 'undefined'){
                    var data = Array();
                    $.ajax({
                        url: '/transaksi/detailbayar',
                        data: {_token: "{{ csrf_token() }}", id: row.data().ID},
                        method: "POST",
                        success: function(response){
                            //var  response = JSON.parse(msg);
                            if (typeof response.error != 'undefined'){
                                return false;
                            }
                            var data = response.data;
                            var detail =  '<table class="table" width="100%">'+
                                    '<thead>'+
                                        '<tr>' +
                                            '<th>No PPU</th>' +
                                            '<th>Curr</th>' +
                                            '<th>Kurs</th>' +
                                            '<th>Nominal</th>' +
                                            '<th>Rupiah</th>' +
                                            '<th>Tgl Bayar</th>' +
                                            '<th>Opsi</th>' +
                                        '</tr>' +
                                    '</thead>' +
                                    '<tbody>';
                            if (data.length > 0){
                                $(data).each(function(index, elem){
                                    detail += '<tr>' +
                                                '<td>' + elem.NO_PPU + '</td>' +
                                                '<td>' + elem.MATAUANG + '</td>' +
                                                '<td>' + parseFloat(elem.KURS).formatMoney(0,"",",",".") + '</td>' +
                                                '<td>' + parseFloat(elem.NOMINAL).formatMoney(0,"",",",".") + '</td>' +
                                                '<td>' + parseFloat(elem.RUPIAH).formatMoney(0,"",",",".") + '</td>' +
                                                '<td>' + elem.TGLBAYAR + '</td>' +
                                                '<td><a href="/transaksi/transaksibayar/' + elem.ID_HEADER + '"><i class="fa fa-edit"></i></a></td>' +
                                            '</tr>';
                                })
                            }
                            else {
                                detail += '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
                            }
                            detail += '</tbody></table>';
                            row.child(detail).show();
                            tr.addClass('shown');
                            $(tr).find("a.showdetail i").attr("class","fa fa-minus-circle");
                        }
                    })
                }
                else {
                    row.child.show();
                    tr.addClass('shown');
                    $(this).find("i").attr("class","fa fa-minus-circle");
                }
            }
        });
    })
</script>
@endpush
