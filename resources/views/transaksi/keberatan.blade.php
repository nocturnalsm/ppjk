@extends('layouts.base')
@section('main')
<div class="card">
    <div class="card-header">
        Browse Keberatan
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10">
                <form id="form" method="POST" action="/transaksi/keberatan?filter=1&export=1">
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
                        <label class="px-sm-3 col-sm-1">Nilai</label>
                        <div class="col-md-5">
                            <input type="text" id="isikategori1_text" name="isikategori1" class="form-control form-control-sm" style="display:inline;width: 120px">
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
                        <th>Opsi</th>
                        <th>Ktr</th>
                        <th>Importir</th>
                        <th>No SPTNP</th>
                        <th>Tgl SPTNP</th>
                        <th>Nopen</th>
                        <th>Total</th>
                        <th>Tgl Lunas</th>
                        <th>Tgl BRT</th>
                        <th>Hasil BRT</th>
                        <th>No Kep</th>
                        <th>Tgl Kep</th>
                        <th>Jth Tmp Bdg</th>
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

    var columns = [{target: 0, data: null}, {target: 1, data: "KODEKANTOR"}, {target: 2, data: "NAMAIMPORTIR"}, {target: 3, data: "NO_SPTNP"},
    {target: 4, data: "TGLSPTNP"},{target: 5, data: "NOPEN"}, {target: 6, data: "TOTAL_TB"}, {target: 7, data: "TGLLUNAS"},
    {target: 8, data: "TGLBRT"}, {target: 9, data: "HSL_BRT"}, {target: 10, data: "NO_KEPBRT"}, {target: 11, data: "TGLKEPBRT"},
    {target: 12, data: "TGLJTHTMPBDG"}
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
            $('td:eq(0)', row).html('<a title="Edit" href="/transaksi/usersptnp/' + data.ID + '"><i class="fa fa-edit"></i></a>');
            $('td:eq(6)', row).html(parseFloat(data.TOTAL_TB).formatMoney(2,"",",","."));
        },
        columnDefs: [
            { "orderable": false, "targets": 0 }
        ]
    });
    $("#preview").on("click", function(){
        $.ajax({
        method: "POST",
        url: "/transaksi/keberatan?filter=1",
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
