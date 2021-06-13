@extends('layouts.base')
@section('main')
<div class="card">
    <div class="card-header">
        Browse Stok per Kode Barang
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10">
                <form id="form" method="POST" action="/transaksi/browsestokbarang?filter=1&export=1">
                    @csrf
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
                        <label class="px-sm-3 col-2 col-md-1">Nilai</label>
                        <div class="col-md-2">
                            <input type="text" id="isikategori1_text" name="isikategori1" class="form-control form-control-sm" style="display:inline;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            Kategori
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="kategori2" name="kategori2">
                                @foreach($datakategori2 as $kat)
                                <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="px-sm-3 col-md-1">Periode</label>
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
                        <th>Kode Barang</th>
                        <th>Kode Produk</th>
                        <th>Customer</th>
                        <th>Faktur</th>
                        <th>No.Aju</th>
                        <th>Hrg Satuan</th>
                        <th>Tgl Terima</th>
                        <th>Saldo Awal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Saldo Akhir</th>
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
        var columns = [
        {target: 0, data: "KODEBARANG"}, {target: 1, data: "kode"},  {target: 2, data: "CUSTOMER"@cannot('customer.view') ,visible:  false @endif},
        {target: 3, data: "FAKTUR"}, {target: 4, data: "NOAJU"}, {target: 5, data: "HARGA"},
        {target: 6, data: "TGL_TERIMA"},
        {target: 7, data: "satuansawal"},
        {target: 8, data: "satuanmasuk"},
        {target: 9, data: "satuankeluar"},
        {target: 10, data: "satuansakhir"}
        ];

        var printvalues  = function(jmlkemasan, jmlsatuan, kemasan, satuan){
            jmlkemasan = parseFloat(jmlkemasan).formatMoney(2,"",",",".");
            jmlsatuan = parseFloat(jmlsatuan).formatMoney(2,"",",",".");

            if (jmlkemasan){
                var html = jmlkemasan + (kemasan ? " " + kemasan : "");
                if (jmlsatuan){
                    html += "<br>" + jmlsatuan + (satuan ? " " + satuan : "");
                }
            }
            return html;
        }

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
            order: [[1, 'asc']],
            columns: columns,
            rowCallback: function(row, data)
            {
                $(row).attr("id-transaksi", data[0]);
                var col = 0;
                @can('customer.view')
                col = 1;
                @endcan
                $('td:eq(' + (4+col) +')', row).html(parseFloat(data.HARGA).formatMoney(2,"",",","."));
                $('td:eq(' + (6+col) +')', row).html(parseFloat(data.satuansawal).formatMoney(2,"",",","."));
                $('td:eq(' + (7+col) +')', row).html(parseFloat(data.satuanmasuk).formatMoney(2,"",",","."));
                $('td:eq(' + (8+col) +')', row).html(parseFloat(data.satuankeluar).formatMoney(2,"",",","."));
                $('td:eq(' + (9+col) +')', row).html(parseFloat(data.satuansakhir).formatMoney(2,"",",","."));
            }
        });
        $("#preview").on("click", function(){
            $.ajax({
            method: "POST",
            url: "/transaksi/browsestokbarang?filter=1",
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
                        url: '/transaksi/detailstokbarang',
                        data: {_token: "{{ csrf_token() }}", id: row.data().ID, form: $("#form").serialize()},
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
                                            '<th>No.DO</th>' +
                                            '<th>Tgl.DO</th>' +
                                            '<th>No.Inv.Jual</th>' +
                                            '<th>Tanggal</th>' +
                                            '<th>Masuk</th>' +
                                            '<th>Keluar</th>' +
                                            '<th>Opsi</th>' +
                                        '</tr>' +
                                    '</thead>' +
                                    '<tbody>';
                            if (data.length > 0){
                                $(data).each(function(index, elem){
                                    detail += '<tr>' +
                                                '<td>' + elem.NO_DO + '</td>' +
                                                '<td>' + elem.TGL_DO + '</td>' +
                                                '<td>' + elem.NO_INV_JUAL + '</td>' +
                                                '<td>' + elem.TGL_KELUAR + '</td>' +
                                                '<td>' + printvalues(elem.kemasanmasuk, elem.satuanmasuk, elem.satuankemasan, elem.satuan) + '</td>' +
                                                '<td>' + printvalues(elem.kemasankeluar, elem.satuankeluar, elem.satuankemasan, elem.satuan) + '</td>' +
                                                '<td>';
                                    detail += '<a title="Edit DO" class="showdo" href="/transaksi/deliveryorder/' + elem.ID +'"><i class="fa fa-edit"></i></a>';
                                    detail += '</td></tr>';
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
    })
</script>
@endpush
