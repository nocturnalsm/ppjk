@extends('layouts.base')
@section('main')
<div class="card">
    <div class="card-header">
        Browse Stok per Produk
    </div>
    <div class="card-body">
        <form id="form" method="POST" action="/transaksi/browsestokproduk?filter=1&export=1">
        @csrf
        <div class="row">
            <div class="col-md-10">
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
                <div class="row">
                    <div class="col-md-2">
                        Kode Produk
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="isikategori1_text" name="isikategori1" class="form-control form-control-sm" style="display:inline;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        HPP Range
                    </div>
                    <div class="col-md-5">
                        <input autocomplete="off" type="text" id="dari" name="dari" class="number form-control d-inline form-control-sm" style="width: 120px">
                        &nbsp;&nbsp;sampai&nbsp;&nbsp;
                        <input autocomplete="off" type="text" id="sampai" name="sampai" class="number form-control d-inline form-control-sm" style="width: 120px">
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
                        <th>Opsi</th>
                        <th>Kode Produk</th>
                        <th>Stok</th>
                        <th>Avg HPP</th>
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
<script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<script>
    $(function(){

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

        var columns = [{target: 0, data: null}, {target: 1, data: "KODEPRODUK"}, {target: 2, data: "STOK"},
                       {target: 3, data: "AVGHPP"}
        ];
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
            },
            columnDefs: [
                { "orderable": false, "targets": 0 }
            ]
        });
        $("#preview").on("click", function(){
            $.ajax({
            method: "POST",
            url: "/transaksi/browsestokproduk?filter=1",
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
        });
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
                    console.log(row.data());
                    var data = Array();
                    $.ajax({
                        url: '/transaksi/detailstokproduk',
                        data: {_token: "{{ csrf_token() }}", id: row.data().id, form: $("#form").serialize()},
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
                                            '<th>Kode Barang</th>' +
                                            '<th>Importir</th>' +
                                            '<th>Tanggal</th>' +
                                            '<th>Masuk</th>' +
                                            '<th>Keluar</th>' +
                                        '</tr>' +
                                    '</thead>' +
                                    '<tbody>';
                            if (data.length > 0){
                                $(data).each(function(index, elem){
                                    detail += '<tr>' +
                                                '<td>' + elem.KODEBARANG + '</td>' +
                                                '<td>' + elem.NAMA + '</td>' +
                                                '<td>' + elem.TANGGAL + '</td>' +
                                                '<td>' + printvalues(elem.kemasanmasuk, elem.satuanmasuk, elem.satuankemasan, elem.satuan) + '</td>' +
                                                '<td>' + printvalues(elem.kemasankeluar, elem.satuankeluar, elem.satuankemasan, elem.satuan) + '</td>' +
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
        } );
        $(".number").inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
        });
    })
</script>
@endpush
