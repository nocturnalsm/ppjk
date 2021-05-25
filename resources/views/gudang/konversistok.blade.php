@extends('layouts.base')
@section('main')
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <form id="formdetail">
                    @csrf
                    <input type="hidden" name="iddetail" id="iddetail">
                    <input type="hidden" name="idxdetail" id="idxdetail">
                    <input type="hidden" name="type" value="konversistok">
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="kodebarang">Kode Barang</label>
                        <div class="col-md-9 mt-2">
                            <span id="editkodebarang"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="produk">Kode Produk</label>
                        <div class="col-md-9">
                          <select class="form-control" id="produk" name="produk">
                              <option value=""></option>
                              @foreach($dataproduk as $produk)
                              <option harga = "{{ $produk->harga }}" kodeproduk="{{ $produk->kode }}" value="{{ $produk->id }}">{{ $produk->nama }}</option>
                              @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Jml Sat Harga</label>
                        <div class="col-md-4 mt-2">
                            <span id="editjmlsatharga"></span>
                        </div>
                        <label class="col-md-auto col-form-label">Satuan</label>
                        <div class="col-md-auto mt-2">
                            <span id="editsatuan"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Jml Sat Konversi</label>
                        <div class="col-md-4">
                            <input type="text" class="number form-control" name="jmlsatkonversi" id="jmlsatkonversi">
                        </div>
                        <label class="col-md-auto col-form-label">Satuan</label>
                        <div class="col-md-auto">
                            <select class="form-control" id="satkonversi" name="satkonversi">
                                <option value=""></option>
                                @foreach($datasatuan as $satuan)
                                <option value="{{ $satuan->id }}">{{ $satuan->satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Rupiah</label>
                        <div class="col-md-9 mt-2">
                            <span id="editrupiah"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Tax</label>
                        <div class="col-md-9">
                            <input type="text" class="number percent form-control" name="tax" id="tax">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">HPP</label>
                        <div class="col-md-9 mt-2">
                            <span id="edithpp"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                      <label class="col-md-3 col-form-label">Tanggal Bongkar</label>
                      <div class="col-md-4 mt-2">
                          <span id="edittglbongkar"></span>
                      </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Tanggal Konversi</label>
                        <div class="col-md-9">
                            <input type="text" class="datepicker form-control" name="tglkonversi" id="tglkonversi">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <a id="savedetail" class="btn btn-primary">Simpan</a>
                <a class="btn btn-danger" data-dismiss="modal">Batal</a>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Konversi Stok
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10">
                <form id="form" method="POST" action="/gudang/konversistok?filter=1&export=1">
                    @csrf
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
                            Kode Barang
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" id="kodebarang" name="kodebarang">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            Kategori
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="kategori2" name="kategori2">
                                <option value=""></option>
                                @foreach($datakategori as $kat)
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
                                @foreach($datakategori as $kat)
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
                <table width="100%" id="grid" class="table">
                    <thead>
                        <th>Opsi</th>
                        <th>Kode Barang</th>
                        <th>Kode Produk</th>
                        <th>Jml Sat Konv</th>
                        <th>Sat Konv</th>
                        <th>Rupiah</th>
                        <th>Tax</th>
                        <th>HPP</th>
                        <th>Tgl Bongkar</th>
                        <th>Tgl Konversi</th>
                        <th>Customer</th>
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
<script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
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
        var columns = [{target: 0, data: null}, {target: 1, data: "KODEBARANG"}, {target: 2, data: "KODEPRODUK"},
                      {target: 3, data: "JMLSATKONVERSI"}, {target: 4, data: "NAMASATKONVERSI"},
                      {target: 5, data: "RUPIAH"}, {target: 6, data: "TAX"},
                      {target: 7, data: null},
                      {target: 8, data: "TGLBONGKAR"}, {target: 9, data: "TGLKONVERSI"},
                      {target: 10, data: "NAMACUSTOMER"}
        ];
        $(".number").inputmask("decimal", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
            oncleared: function () { self.setValue(''); }
        });

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
                $('td:eq(0)', row).html('<a title="Edit" data-toggle="modal" class="editkonversi" href="#modaldetail"><i class="fa fa-edit"></i></a>');
                $('td:eq(3)', row).html(parseFloat(data.JMLSATKONVERSI).formatMoney(2,"",",","."));
                $('td:eq(5)', row).html(parseFloat(data.RUPIAH).formatMoney(2,"",",","."));
                $('td:eq(6)', row).html(parseFloat(data.TAX).formatMoney(2,"",",","."));
                $('td:eq(7)', row).html((data.RUPIAH*(1+data.TAX/100)).formatMoney(2,"",",","."));
            },
            columnDefs: [
                { "orderable": false, "targets": 0 }
            ]
        });
        $("#preview").on("click", function(){
            $.ajax({
            method: "POST",
            url: "/gudang/konversistok?filter=1",
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
        $("body").on("click",".editkonversi", function(){
            var row = $(this).closest("tr");
            var index = grid.row(row).index();
            var row = grid.rows(index).data();
            var produk_id = row[0].PRODUK_ID;
            var tglkonversi = row[0].TGLKONVERSI;
            var tax = row[0].TAX;
            var jmlsatkonversi = row[0].JMLSATKONVERSI;
            var satkonversi = row[0].SATKONVERSI;
            if (produk_id == null && tglkonversi == null && tax == null && jmlsatkonversi == null && satkonversi == null){
                jmlsatkonversi = row[0].JMLSATHARGA;
            }
            $("#editkodebarang").html(row[0].KODEBARANG);
            $("#edittglbongkar").html(row[0].TGLBONGKAR);
            $("#editsatuan").html(row[0].NAMASATHARGA);
            $("#editjmlsatharga").html(parseFloat(row[0].JMLSATHARGA).formatMoney(2,"",",","."));
            $("#editrupiah").html(parseFloat(row[0].RUPIAH).formatMoney(2,"",",","."));
            $("#edithpp").html((parseFloat(row[0].RUPIAH)*(1+parseFloat(row[0].TAX)/100)).formatMoney(2,"",",","."));
            $("#produk").val(produk_id);
            $("#tglkonversi").val(tglkonversi);
            $("#jmlsatkonversi").val(jmlsatkonversi);
            $("#satkonversi").val(satkonversi);
            $("#tax").val(tax);
            $("#iddetail").val(row[0].ID);
            $("#idxdetail").val(index);
            $("#modaldetail .modal-title").html("Edit ");
            $("#formdetail").attr("act","edit");
        })
        $("#savedetail").on("click", function(){
            if ($("#produk").val() == ""){
                $("#modal .modal-body").html("Produk harus diisi");
                $("#modal").modal("show");
                setTimeout(function(){
                    $("#modal").modal("hide");
                }, 5000);
                return false;
            }

            $("#modaldetail btn").addClass("disabled");
            $.ajax({
                type: "POST",
                url: "/gudang/crud/konversistok",
                data: $("#formdetail").serialize(),
                success: function(msg) {
                    if (typeof msg.error != 'undefined'){
                        $("#modal .modal-body").html(msg.error);
                        $("#modal").modal("show");
                        setTimeout(function(){
                            $("#modal").modal("hide");
                        }, 5000);
                    }
                    else {
                        var idx = $("#idxdetail").val();
                        var data = grid.row(idx).data();
                        data.TGLKONVERSI = $("#tglkonversi").val();
                        data.PRODUK_ID = $("#produk").val();
                        data.KODEPRODUK = $("#produk option:selected").attr("kodeproduk");
                        data.SATKONVERSI = $("#satkonversi option:selected").val();
                        data.JMLSATKONVERSI = $("#jmlsatkonversi").inputmask('unmaskedvalue');
                        data.NAMASATKONVERSI = $("#satkonversi option:selected").html();
                        data.TAX = $("#tax").inputmask("unmaskedvalue");
                        grid.row(idx).data(data).draw();
                        $("#modaldetail").modal("hide");
                        $("#modal .modal-body").html("Penyimpanan berhasil");
                        $("#modal").modal("show");
                        setTimeout(function(){
                            $("#modal").modal("hide");
                        }, 5000);
                    }
                },
                complete: function(){
                    $("#modaldetail btn").removeClass("disabled");
                }
            })
        })
        $("#tax").on("change", function(){
            var tax  = $("#tax").val() != "" ? $("#tax").inputmask("unmaskedvalue") : 0;
            var rupiah = $("#editrupiah").html().trim() == "" ? 0 : parseFloat($("#editrupiah").html().replace(/,/g,""));
            $("#edithpp").html((rupiah*(1+tax/100)).formatMoney(2,"",",","."));
        })
    })
</script>
@endpush
