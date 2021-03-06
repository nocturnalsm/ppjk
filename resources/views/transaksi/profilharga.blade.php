@extends('layouts.base')
@section('main')
<div class="card">
    <div class="card-header">
        Browse Profil Harga
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10">
                <form id="form" method="POST" action="/transaksi/profilharga?filter=1&export=1">
                    @csrf
                    <div class="row">
                        <label class="col-md-2">Supplier</label>
                        <div class="col-md-3 col-sm-6">
                            <input placeholder="Supplier" type="text" name="supplier" class="form-control form-control-sm" id="supplier">
                        </div>
                    </div>
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
                        <label class="col-md-2">Kode Barang</label>
                        <div class="col-md-3 col-sm-6">
                            <input placeholder="Kode Barang" type="text" name="kodebarang" class="form-control form-control-sm" id="kodebarang">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2">Uraian</label>
                        <div class="col-md-3 col-sm-6">
                            <input placeholder="Uraian" type="text" name="uraian" class="form-control form-control-sm" id="uraian">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            Kategori
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="kategori1" name="kategori1">
                                <option value=""></option>
                                @foreach($datakategori1 as $kat)
                                <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="px-sm-3 col-sm-1">Periode</label>
                        <div class="col-md-5">
                            <input autocomplete="off" type="text" id="dari1" name="dari1" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
                            &nbsp;&nbsp;sampai&nbsp;&nbsp;
                            <input autocomplete="off" type="text" id="sampai1" name="sampai1" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
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
                        <th>Ktr</th>
                        <th>Supplier</th>
                        <th>Importir</th>
                        <th>Customer</th>
                        <th>Tgl BL</th>
                        <th>Nopen<br>Tgl Nopen</th>
                        <th>Kode Brg<br>Uraian</th>
                        <th>Harga</th>
                        <th>No.SPTNP</th>
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

    var columns = [{target: 0, data: "KODEKANTOR"}, {target: 1, data: "NAMASUPPLIER"}, {target: 2, data: "NAMAIMPORTIR"},
    {target: 3, data: "NAMACUSTOMER"},{target: 4, data: "TGLBL"}, {target: 5, data: "NOPEN"}, {target: 6, data: "KODEBARANG"},
    {target: 7, data: "HARGA"}, {target: 8, data: "NOSPTNP"}
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
            $('td:eq(7)', row).html(parseFloat(data.HARGA).formatMoney(2,"",",","."));
            $('td:eq(5)', row).html('<div class="nopen">' + data.NOPEN + '</div><div class="tglnopen">' + (data.TGLNOPEN || '') + '</div>');
            $('td:eq(6)', row).html('<div class="kodebarang">' + data.KODEBARANG + '</div><div class="uraian">' + (data.URAIAN || '') + '</div>');
        },
        columnDefs: [
            { "orderable": false, "targets": 0 }
        ]
    });
    $("#preview").on("click", function(){
        $.ajax({
        method: "POST",
        url: "/transaksi/profilharga?filter=1",
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
})
</script>
@endpush
