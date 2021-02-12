@extends('layouts.base')
@section('main')
    <form id="formsearch">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="input-group">
                <input type="text" class="form-control" id="hscode" name="hscode" placeholder="Cari HS Code">
                <input type="text" class="number form-control" id="rangefrom" name="rangefrom" placeholder="Harga Dari">
                <input type="text" class="number form-control" id="rangeto" name="rangeto" placeholder="Sampai">
                <button id="btnsearch" class="btn btn-primary m-0 px-3" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>     
    </div>
    </form>
    <div class="row mt-4">
        <div class="col-md-12">
            <table width="100%" id="gridtransaksi" class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>                        
                        <th>HS Code</th>
                        <th>Satuan</th>   
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts_end')
<script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
<script>
    $(function(){
        $("#btnsearch").on("click", function(){
            $("#formsearch").submit();
        })
        $("#formsearch").on("submit", function(e){
            e.preventDefault();
            $(".loader").show()
            $.ajax({
                url: "/transaksi/findproduk",
                data: $("#formsearch").serialize(),
                type: "GET",
                success: function(msg) {
                    if (msg.length == 0){
                        $("#modal .modal-body").html("Data tidak ditemukan");
                        $("#modal").modal("show");
                        setTimeout(function(){
                            $("#modal").modal("hide");
                        }, 1000);  
                    }
                    else {
                        grid.columns(0).search(JSON.stringify(msg)).draw();
                    }
                },
                complete: function(){
                    $(".loader").hide();
                }
            });
        })
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
        $(".number").inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 0,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
        });
        var grid = $("#gridtransaksi").DataTable({
            processing: false,
            serverSide: true,
            ajax: {
                "url": "/transaksi/daftarproduk",
                "type": "GET"
            },
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
            columns:[{
                        "target": 0,
                        "data": "kode"
                    }, {
                        "target": 1,
                        "data": "nama"
                    },{
                        "target": 2,
                        "data": "hscode"
                    },{
                        "target": 3,
                        "data": "satuan"
                    },{
                        "target": 4,
                        "data": "harga"
            }],
            rowCallback: function(row, data){        
                $('td:eq(4)', row).html(parseFloat(data.harga).formatMoney(0,"",",","."));
            },
        });
    })
</script>
@endpush