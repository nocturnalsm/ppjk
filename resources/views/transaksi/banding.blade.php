@extends('layouts.base')
@section('main')
<div class="card">
    <div class="card-header">
        Browse Banding
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10">
                <form id="form" method="POST" action="/transaksi/banding?filter=1&export=1">
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
                    <div class="row">
                        <div class="col-md-2">
                            Kategori
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="kategori3" name="kategori3">
                                <option value=""></option>
                                @foreach($datakategori2 as $kat)
                                <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="px-sm-3 col-sm-1">Periode</label>
                        <div class="col-md-5">
                            <input autocomplete="off" type="text" id="dari3" name="dari3" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
                            &nbsp;&nbsp;sampai&nbsp;&nbsp;
                            <input autocomplete="off" type="text" id="sampai3" name="sampai3" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
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
                <table style="width:100%" id="grid" class="table nowrap">
                    <thead>
                        <th>Opsi</th>
                        <th>Ktr</th>
                        <th>Importir</th>
                        <th>Nopen<br>Tgl Nopen</th>
                        <th>No Kep Brt<br>Tgl Kep Brt</th>
                        <th>No Sengk<br>Tgl Sengk</th>
                        <th>Mjls</th>
                        <th>SD01</th>
                        <th>SD02</th>
                        <th>SD03</th>
                        <th>SD04</th>
                        <th>SD05</th>
                        <th>SD06</th>
                        <th>SD07</th>
                        <th>Hasil</th>
                        <th>No Kep Bdg<br>Tgl Kep Bdg</th>
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

    var columns = [{target: 0, data: null}, {target: 1, data: "KODEKANTOR"}, {target: 2, data: "NAMAIMPORTIR"}, {target: 3, data: "NOPEN"},
    {target: 4, data: "NO_KEPBRT"}, {target: 5, data: "NO_BDG"},{target: 6, data: "MAJELIS"}, {target: 7, data: "SDG01"},
    {target: 8, data: "SDG02"}, {target: 9, data: "SDG03"}, {target: 10, data: "SDG04"}, {target: 11, data: "SDG05"},
    {target: 12, data: "SDG06"},{target: 13, data: "SDG07"},{target: 14, data: "HASIL_BDG"},{target: 15, data: "NO_KEP_BDG"}
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
        "scrollX": true,
        rowCallback: function(row, data)
        {
            $(row).attr("id-transaksi", data[0]);
            $('td:eq(0)', row).html('<a title="Edit" href="/transaksi/usersptnp/' + data.ID + '"><i class="fa fa-edit"></i></a>');
            $('td:eq(3)', row).html('<div class="nopen">' + data.NOPEN + '</div><div class="tglnopen">' + (data.TGLNOPEN || '') + '</div>');
            $('td:eq(4)', row).html('<div class="nokepbrt">' + data.NO_KEPBRT + '</div><div class="tglkepbrt">' + (data.TGLKEPBRT || '') + '</div>');
            $('td:eq(5)', row).html('<div class="nobdg">' + data.NO_BDG + '</div><div class="tglbdg">' + (data.TGLBDG || '') + '</div>');
            $('td:eq(15)', row).html('<div class="nokepbdg">' + data.NO_KEP_BDG + '</div><div class="tglkepbdg">' + (data.TGLKEPBDG || '') + '</div>');
        },
        columnDefs: [
            { "orderable": false, "targets": 0 }
        ]
    });
    $("#preview").on("click", function(){
        $.ajax({
        method: "POST",
        url: "/transaksi/banding?filter=1",
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
