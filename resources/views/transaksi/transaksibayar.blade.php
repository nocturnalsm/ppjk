@extends('layouts.base')
@section('main')
<style>
    .error {display:none;font-size: 0.75rem;color: red};
</style>
<div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">                    
                <form id="form" act="">            
                    @csrf
                    <input type="hidden" name="idxdetail" id="idxdetail">
                    <input type="hidden" name="iddetail" id="iddetail">                    
                    <div class="form-row mb-1">               
                        <label class="col-form-label col-md-3" for="noinv">No Inv</label>
                        <div class="col-md-9">
                            <input type="text" id="noinv" name="noinv" class="form-control form-control-sm validate">                        
                            <input type="hidden" id="noinv_id" name="noinv_id">
                        </div>
                    </div>
                    <div class="form-row mb-1">               
                        <label class="col-form-label col-md-3">Customer</label>
                        <div class="col-md-9 pt-2">
                            <span id="formcustomer"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">               
                        <label class="col-form-label col-md-3">Importir</label>
                        <div class="col-md-9 pt-2">
                            <span id="formimportir"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">               
                        <label class="col-form-label col-md-3">Shipper</label>
                        <div class="col-md-9 pt-2">
                            <span id="formshipper"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">               
                        <label class="col-form-label col-md-3">No.Aju</label>
                        <div class="col-md-9 pt-2">
                            <span id="formnoaju"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">               
                        <label class="col-form-label col-md-3">Nopen</label>
                        <div class="col-md-3 pt-2">
                            <span id="formnopen"></span>
                        </div>
                        <div class="col-md-3 pt-2">
                            <span id="formtglnopen"></span>
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="noppu">No PPU</label>
                        <div class="col-md-9">
                            <input type="text" id="noppu" name="noppu" class="form-control form-control-sm validate">                        
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="ukuran">Curr</label>
                        <div class="col-md-9">
                        <select class="form-control form-control-sm" id="curr" name="curr">
                            @foreach($matauang as $curr)
                            <option value="{{ $curr->MATAUANG_ID }}">{{ $curr->MATAUANG }}</option>
                            @endforeach
                        </select>                           
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="kurs">Kurs</label>
                        <div class="col-md-9">
                            <input type="text" id="kurs" name="kurs" class="number form-control form-control-sm validate">                        
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="nominal">Nominal</label>
                        <div class="col-md-9">
                            <input type="text" id="dnominal" name="dnominal" class="number form-control form-control-sm validate">                        
                        </div>
                    </div>
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="rupiah">Rupiah</label>
                        <div class="col-md-9">
                            <input type="text" readonly id="rupiah" name="rupiah" class="number form-control form-control-sm validate">
                        </div>                        
                    </div>
                    <!--
                    <div class="form-row mb-1">                        
                        <label class="col-form-label col-md-3" for="catatan">Catatan</label>
                        <div class="col-md-9">
                            <textarea id="catatan" name="catatan" class="form-control form-control-sm validate"></textarea>
                        </div>                        
                    </div>
                    -->
                </form>               
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <a id="savedetail" class="btn btn-primary">Simpan</a>
                <a class="btn btn-danger" data-dismiss="modal">Batal</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="card col-md-12 p-0">
        <div class="card-header font-weight-bold">
            <div class="row">
                <div class="col-md-4 py-0 pl-4 mt-1">
                    Form Perekaman Pembayaran
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                    @if(isset($header->ID) && $header->ID != null)
                    <a id="deletetransaksi" class="btn btn-warning btn-sm m-0" data-dismiss="modal">Hapus</a>
                    @endif
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input type="hidden" value="{{ $header->ID }}" id="idtransaksi" name="idtransaksi">                
            <div class="row px-2">
                <div class="col-md-6 pt-0 col-sm-12">                    
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <div class="form-row px-2 pb-0">                                     
                                    <label class="col-md-3 col-form-label form-control-sm">Tgl Penarikan</label>                   
                                    <div class="col-md-3">                                                            
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglpenarikan" value="{{ $header->TGL_PENARIKAN }}" id="tglpenarikan">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Rekening</label>
                                    <div class="col-md-6">
                                        <select class="form-control form-control-sm" id="rekening" name="rekening" value="{{ $header->REKENING_ID }}">
                                            <option value=""></option>
                                            @foreach($rekening as $rek)
                                            <option @if($header->REKENING_ID == $rek->REKENING_ID) selected @endif value="{{ $rek->REKENING_ID }}">{{ $rek->BANK }} / {{ $rek->NO_REKENING }} / {{ $rek->NAMA }}</option>
                                            @endforeach
                                        </select>             
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">                    
                                    <label class="col-md-3 col-form-label form-control-sm">No. Cek</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control form-control-sm" name="nocek" value="{{ $header->NO_CEK }}" id="nocek">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-3 col-form-label form-control-sm">Nominal Penarikan</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="number form-control form-control-sm" name="totpayment" id="totpayment" value="{{ $header->NOMINAL }}">             
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>                     
                </div>
            </div>
            <div class="row px-2">
                <div class="col-md-12">
                    <div class="row mb-2">
                        <div class="card col-md-12 p-0">
                            <div class="card-body p-3">
                                <h5 class="card-title">Detail Pembayaran</h5>
                                <div class="form-row">
                                    <div class="col primary-color text-white py-2 px-4">
                                        Detail Pembayaran
                                    </div>
                                    <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                                        <a href="#modaldetail" data-toggle="modal" class="text-white" id="adddetail">Tambah Detail</a>
                                    </div>                            
                                </div>                    
                                <div class="form-row">
                                    <div class="col mt-2">
                                        <table width="100%" id="griddetail" class="table">
                                            <thead>
                                                <tr>
                                                    <th>No.Inv</th>
                                                    <th>Customer</th>
                                                    <th>Importir</th>
                                                    <th>Shipper</th>
                                                    <th>No Aju</th>
                                                    <th>Nopen</th>
                                                    <th>Tgl Nopen</th>
                                                    <th>No.PPU</th>
                                                    <th>Curr</th>
                                                    <th>Kurs</th>
                                                    <th>Nominal</th>
                                                    <th>Rupiah</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>     
                                </div>                        
                            </div>
                        </div>
                    </div>                                                        
                </div>
            </div>
        </div>
        </form>
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
    var detail = @json($detail);
    datadetail = JSON.parse(detail);
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
        $("body").on("change","#noinv", function(){
            var noinv = $(this).val();
            $.ajax({
                method: "GET",
                url: "/transaksi/searchinv",
                data: {inv: noinv},
                success: function(response){
                    if (typeof response.error == 'undefined'){                
                        $("#formcustomer").html(response.nama_customer);
                        $("#formshipper").html(response.NAMASHIPPER);
                        $("#formimportir").html(response.NAMAIMPORTIR);
                        $("#formnoaju").html(response.NOAJU);
                        $("#formnopen").html(response.NOPEN);
                        $("#formtglnopen").html(response.TGL_NOPEN);
                        $("#noinv_id").val(response.ID);
                    }
                    else {
                        $("#modal .modal-body").html(response.error);
                        $("#formcustomer").html("");
                        $("#formshipper").html("");
                        $("#formimportir").html("");
                        $("#formnoaju").html("");
                        $("#formnopen").html("");
                        $("#formtglnopen").html("");
                        $("#noinv_id").val("");
                        $("#modal").modal("show");
                        setTimeout(function(){
                            $("#modal").modal("hide");
                        }, 5000);
                    }
                }
            })
        })
        $('#modaldetail').on('shown.bs.modal', function () {
            $('#penerima').focus();
        })
        $("#dnominal,#kurs").on("change", function(){
            var nominal = parseFloat($("#dnominal").inputmask("unmaskedvalue"));
            var kurs = parseFloat($("#kurs").inputmask("unmaskedvalue"));
            var rupiah = (nominal*kurs).toFixed(0);
            $("#rupiah").val(rupiah);
        })
        $("#savedetail").on("click", function(){ 
            var noppu = $("#noppu").val();
            var noinv = $("#noinv").val();
            var noinv_id = $("#noinv_id").val();
            var customer = $("#formcustomer").html();
            var importir = $("#formimportir").html();
            var shipper = $("#formshipper").html();
            var noaju = $("#formnoaju").html();
            var nopen = $("#formnopen").html();
            var tglnopen = $("#formtglnopen").html();    
            var curr_id = $("#curr option:selected").val();
            var curr = $("#curr option:selected").html();
            if (!curr){
                curr = "";
                curr_id = "";
            }
            var kurs = $("#kurs").inputmask('unmaskedvalue');
            var nominal = $("#dnominal").inputmask('unmaskedvalue');    
            var rupiah = parseFloat(nominal)*parseFloat(kurs);
            var act = $("#form").attr("act");
            
            if (act == "add"){
                tabel.row.add({CUSTOMER: customer, NO_INV: noinv_id, IMPORTIR: importir, NO_PPU: noppu, NOINV: noinv, CURR: curr_id, MATAUANG: curr, KURS: kurs, RUPIAH: rupiah, NOMINAL: nominal, SHIPPER: shipper, NOAJU: noaju, NOPEN: nopen, TGL_NOPEN: tglnopen}).draw();
                $("#formcustomer").html("");
                $("#formimportir").html("");
                $("#formshipper").html("");
                $("#formnoaju").html("");
                $("#formnopen").html("");
                $("#formtglnopen").html("");
                $("#noppu").val("");
                $("#noinv").val("");
                $("#curr").val("");
                $("#kurs").val("");
                $("#dnominal").val("");
                $("#rupiah").val("");
                $("#catatan").val("");        
                $("#noinv").focus();        
            }
            else if (act == "edit"){        
                var id = $("#iddetail").val();
                var idx = $("#idxdetail").val();
                tabel.row(idx).data({ID: id, NO_INV: noinv_id, CUSTOMER: customer, IMPORTIR: importir, NO_PPU: noppu, NOINV: noinv, CURR: curr_id, MATAUANG: curr, KURS: kurs, RUPIAH: rupiah, NOMINAL: nominal, SHIPPER: shipper, NOAJU: noaju, NOPEN: nopen, TGL_NOPEN: tglnopen}).draw();   
                $("#modaldetail").modal("hide");
            }    
        });
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
        $(".number").inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
        });
        var tabel = $("#griddetail").DataTable({
            processing: false,
            serverSide: false,
            data: datadetail,
            dom: "t",
            rowCallback: function(row, data)
            {        
                $(row).attr("id-transaksi", data.id);
                $('td:eq(9)', row).html(parseFloat(data.KURS).formatMoney(2,"",",","."));
                $('td:eq(10)', row).html(parseFloat(data.NOMINAL).formatMoney(2,"",",","."));
                $('td:eq(11)', row).html(parseFloat(data.RUPIAH).formatMoney(0,"",",","."));
                $('td:eq(12)', row).html('<a href="#modaldetail" class="edit" data-toggle="modal" id="' + data.ID + 
                                        '"><i class="fa fa-edit"></i></a>' +
                                        '&nbsp;&nbsp;<a class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                        );
            },
            select: 'single',     // enable single row selection
            responsive: false,     // enable responsiveness,
            rowId: 0,
            columns: [{
                target: 0,
                data: "NOINV"            
            },
            { target: 1,
                data: "CUSTOMER"
            },              
            { target: 2,
                data: "IMPORTIR"
            },
            { target: 3,
                data: "SHIPPER"
            },
            { target: 4,
                data: "NOAJU"
            },
            { target: 5,
                data: "NOPEN"
            },
            { target: 6,
                data: "TGL_NOPEN"
            },
            { target: 7,
                data: "NO_PPU"
            },
            { target: 8,
                data: "MATAUANG"
            },
            { target: 9,
                data: "KURS"
            },
            { target: 10,
                data: "NOMINAL"
            },
            { target: 11,
                data: "RUPIAH"
            },
            { target: 12,
                data: null
            }
            ],
        })
        $("#adddetail").on("click", function(){
            $("#formcustomer").html("");
            $("#formimportir").html("");
            $("#formshipper").html("");
            $("#formnoaju").html("");
            $("#formnopen").html("");
            $("#formtglnopen").html("");
            $("#noppu").val("");
            $("#noinv").val("");
            $("#noinv_id").val("");
            $("#curr").val("");
            $("#kurs").val("");
            $("#dnominal").val("");
            $("#rupiah").val("");
            $("#modaldetail .modal-title").html("Tambah ");
            $("#form").attr("act","add");
        })
        $("body").on("click", ".edit", function(){
            var row = $(this).closest("tr");
            var index = tabel.row(row).index();
            var row = tabel.rows(index).data();
            $("#formcustomer").html(row[0].CUSTOMER);
            $("#formimportir").html(row[0].IMPORTIR);
            $("#formshipper").html(row[0].SHIPPER);
            $("#formnoaju").html(row[0].NOAJU);
            $("#formnopen").html(row[0].NOPEN);
            $("#formtglnopen").html(row[0].TGLNOPEN);
            $("#noppu").val(row[0].NO_PPU);
            $("#noinv").val(row[0].NOINV);
            $("#noinv_id").val(row[0].NO_INV);
            $("#curr").val(row[0].CURR);
            $("#kurs").val(row[0].KURS);
            $("#dnominal").val(row[0].NOMINAL);
            $("#rupiah").val(row[0].RUPIAH);
            $("#idxdetail").val(index);
            $("#iddetail").val(row[0].ID);
            $("#modaldetail .modal-title").html("Edit ");
            $("#form").attr("act","edit");
        })
        $("#cif, #nominal").on("change", function(){
            calculate();
        })
        $("body").on("click", ".del", function(){
            var row = $(this).closest("tr");
            var id = tabel.row(row).data().ID;
            if (typeof id != 'undefined'){
                $("input[name='deletedetail'").val($("input[name='deletedetail'").val() + id + ";");
            }
            var index = tabel.row(row).remove().draw();
        })
        $("#nopen").inputmask({"mask": "999999","removeMaskOnSubmit": true});

        $("#btnsimpan").on("click", function(){   
        
                //if (validate()){
                var detail = [];
                var rows = tabel.rows().data();
                var total = 0;
                $(rows).each(function(index,elem){
                    total = total + parseInt(elem.RUPIAH.toFixed(0));
                    detail.push(elem);
                })    
                var totalpayment = parseInt($("#totpayment").inputmask('unmaskedvalue'));
                var balance = totalpayment - total;
                if (balance != 0){
                    $("#modal .modal-body").html("Nominal penarikan dan jumlah detail harus sama");
                    $("#modal").modal("show");
                    setTimeout(function(){
                        $("#modal").modal("hide");
                    }, 5000);
                    return false;
                }
                $(this).prop("disabled", true);
                $(".loader").show()
                $.ajax({
                    url: "/transaksi/crud",
                    data: {_token: "{{ csrf_token() }}", type: "bayar", header: $("#transaksi").serialize(), detail: detail},
                    type: "POST",
                    cache: false,
                    success: function(msg) { 
                        if (typeof msg.error != 'undefined'){
                            $("#modal .modal-body").html(msg.error);
                            $("#modal").modal("show");
                            setTimeout(function(){
                                $("#modal").modal("hide");
                            }, 5000);
                        }
                        else {
                            $("#modal .modal-body").html("Penyimpanan berhasil");
                            $("#modal").on("hidden.bs.modal", function(){
                                window.location.reload();
                            });
                            $("#modal").modal("show");
                            setTimeout(function(){
                                $("#modal").modal("hide");                    
                            }, 5000); 
                        }      
                    },
                    complete: function(){
                        $("#btnsimpan").prop("disabled", false);
                        $(".loader").hide();
                    }
                }) 
            /*}
            else {
                return false;
            }*/
        })
        @if(isset($header) && $header->ID != null)
        $("a#deletetransaksi").on("click", function(){
            $("#modal .btn-ok").removeClass("d-none");
            $("#modal .btn-close").html("Batal");
            $("#modal .modal-body").html("Apakah Anda ingin menghapus data ini?");        
            $("#modal .btn-ok").html("Ya").on("click", function(){
                $.ajax({
                    url: "/transaksi/crud",
                    data: {_token: "{{ csrf_token() }}", type: "bayar", delete: "{{ $header->ID}}"},
                    type: "POST",
                    success: function(msg){
                        $("#modal .btn-ok").addClass("d-none");
                        if (typeof msg.error != 'undefined'){
                            $("#modal .modal-body").html(msg.error);
                            $("#modal").modal("show");
                            setTimeout(function(){
                                $("#modal").modal("hide");
                            }, 5000);
                        }
                        else {
                            $("#modal .modal-body").html("Data berhasil dihapus");
                            $("#modal").modal("show");
                            setTimeout(function(){
                                $("#modal").modal("hide");
                            }, 10000);                    
                            window.location.href = "/transaksi/transaksibayar";
                        }
                    }
                })
            });
            $("#modal").modal("show");
        });
        @endif

    })    
</script>
@endpush