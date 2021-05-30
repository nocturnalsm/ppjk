@extends('layouts.base')
@section('main')
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
                    <input {{ $readonly }} type="hidden" name="idxdetail" id="idxdetail">
                    <input {{ $readonly }} type="hidden" name="iddetail" id="iddetail">
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="nopol">Nopol</label>
                        <div class="col-md-9">
                            <input {{ $readonly }} type="text" id="nopol" name="nopol" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="nosj">No SJ</label>
                        <div class="col-md-9">
                            <input {{ $readonly }} type="text" id="nosj" name="nosj" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="nopol">Sopir</label>
                        <div class="col-md-9">
                            <input {{ $readonly }} type="text" id="sopir" name="sopir" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    @hasanyrole('superadmin|admin|gudang')
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="jenistruk">Jenis Truk</label>
                        <div class="col-md-9">
                            <select {{ $readonly == '' ? "" : "disabled" }} id="jenistruk" name="jenistruk" class="form-control form-control-sm validate">
                              <option value=""></option>
                              @foreach($jenistruk as $truk)
                              <option value="{{ $truk->JENISTRUK_ID }}">{{ $truk->JENIS_TRUK }}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="jenistruk">Ekspedisi</label>
                        <div class="col-md-9">
                            <select {{ $readonly == '' ? "" : "disabled" }} id="ekspedisi" name="ekspedisi" class="form-control form-control-sm validate">
                              <option value=""></option>
                              @foreach($ekspedisi as $eksp)
                              <option value="{{ $eksp->EKSPEDISI_ID }}">{{ $eksp->NAMA }}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                    @endhasanyrole
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Jumlah Roll</label>
                        <div class="col-md-9">
                            <input {{ $readonly }} type="text" class="number form-control form-control-sm" name="jmlroll" id="jmlroll">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-md-3 col-form-label">Tgl Keluar</label>
                        <div class="col-md-9">
                            <input {{ $readonly }} type="text" class="datepicker form-control form-control-sm" name="tglkeluar" id="tglkeluar">
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
<div class="row">
    <div class="card col-md-12 p-0">
        <div class="card-header font-weight-bold">
            <div class="row">
                <div class="col-md-4 py-0 pl-4 mt-1">
                    Form Perekaman Pengeluaran
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    @if($readonly == '')
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                    @endif
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input {{ $readonly }} type="hidden" value="{{ $header->ID }}" id="idtransaksi" name="idtransaksi">
            <div class="row px-2">
                <div class="col-md-12 pt-0 col-sm-12">
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-2 col-form-label form-control-sm">Importir</label>
                                    <label class="col-md-4 col-form-label form-control-sm">{{ $header->NAMAIMPORTIR }}</label>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-2 col-form-label form-control-sm">No. Aju</label>
                                    <label class="col-md-2 col-form-label form-control-sm">{{ $header->NOAJU}}</label>
                                    <label class="col-md-auto col-form-label form-control-sm">Nopen</label>
                                    <label class="col-md-2 col-form-label form-control-sm">{{ $header->NOPEN }}</label>
                                    <label class="col-md-auto col-form-label text-right form-control-sm">Tgl Nopen</label>
                                    <label class="col-md-2 col-form-label form-control-sm">{{ $header->TGLNOPEN }}</label>
                                </div>
                                <div class="form-row px-2 pb-0">
                                  <label class="col-md-2 col-form-label form-control-sm">Jumlah Kemasan</label>
                                  <div class="col-md-3">
                                      <label class="col-form-label form-control-sm px-0">{{ $header->JUMLAH_KEMASAN }} {{ $header->JENISKEMASAN }}</label>
                                  </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Kirim</label>
                                    <div class="col-md-1">
                                        <input {{ $readonly }} autocomplete="off" type="text" class="datepicker{{ $readonly == 'readonly' ? '-readonly' : '' }} form-control form-control-sm" name="tglkirim" value="{{ $header->TGLKIRIM }}" id="tglkirim">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm ">Catatan</label>
                                    <div class="col-md-4">
                                        <textarea {{ $readonly }} rows="4" class="form-control form-control-sm" id="catatan" name="catatan">{{ $header->CATATAN }}</textarea>
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
                                <div class="form-row">
                                    <div class="col primary-color text-white py-2 px-4">
                                        Detail Pengeluaran
                                    </div>
                                    <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                                        @if($readonly == '')
                                        <a href="#modaldetail" data-toggle="modal" class="text-white" id="adddetail">Tambah Detail</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mt-2">
                                        <table width="100%" id="griddetail" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nopol</th>
                                                    <th>No SJ</th>
                                                    <th>Sopir</th>
                                                    <th>Jenis Truk</th>
                                                    <th>Ekspedisi</th>
                                                    <th>Jml Roll</th>
                                                    <th>Tgl Keluar</th>
                                                    @if($readonly == '')
                                                    <th>Opsi</th>
                                                    @endif
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
        $(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
        $(".number").inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
        });
        $(".money").inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ",",
            digits: 0,
            autoGroup: true,
            rightAlign: false,
            removeMaskOnSubmit: true,
        });
        var tabel = $("#griddetail").DataTable({
            processing: false,
            serverSide: false,
            data: datadetail,
            dom: "t",
            pageLength: 1000,
            rowCallback: function(row, data)
            {

                $('td:eq(@hasanyrole('superadmin|admin|gudang') 5 @else 3 @endhasanyrole)', row).html(parseFloat(data.JMLROLL).formatMoney(2,"",",","."));
                @if($readonly == '')
                $('td:last-child', row).html('<a href="#modaldetail" class="edit" data-toggle="modal" id="' + data.ID +
                                        '"><i class="fa fa-edit"></i></a>' +
                                        '&nbsp;&nbsp;<a class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                        );
                @endif
            },
            select: 'single',     // enable single row selection
            responsive: false,     // enable responsiveness,
            rowId: 0,
            columns: [
              { target: 0,
                  data: "NOPOL"
              },
              { target: 1,
                  data: "NOSJ"
              },
              { target: 2,
                  data: "SOPIR"
              },
              { target: 3,
                  data: "JENIS_TRUK"@unlessrole('superadmin|admin|gudang') ,visible: false @endunlessrole
              },
              { target: 4,
                  data: "NAMAEKSPEDISI"@unlessrole('superadmin|admin|gudang') ,visible: false @endunlessrole
              },
              { target: 5,
                  data: "JMLROLL"
              },
              { target: 6,
                  data: "TGL_KELUAR"
              },
              @if($readonly == '')
              { target: 7,
                  data: null
              }
              @endif
             ],
        })
        @if($readonly == '')
        $("#btnsimpan").on("click", function(){
                var detail = [];
                var rows = tabel.rows().data();
                $(rows).each(function(index,elem){
                    detail.push(elem);
                })

                $(this).prop("disabled", true);
                $(".loader").show()
                $.ajax({
                    url: "/gudang/crud",
                    data: {header: $("#transaksi").serialize(), _token: "{{ csrf_token() }}", type: 'pengeluaran', detail: detail},
                    type: "POST",
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
                            $('#modal').on('hidden.bs.modal', function (e) {
                                document.location.reload();
                            })
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

        $("#savedetail").on("click", function(){
            $(this).prop("disabled", true);
            if ($("#nopol").val().trim() == ""){
                $("#modal .modal-body").html("Nopol Harus Diisi");
                $("#modal").modal("show");
                setTimeout(function(){
                    $("#modal").modal("hide");
                }, 5000);
                $("#nopol").focus();
                return false;
            }
            var nopol = $("#nopol").val();
            var sopir = $("#sopir").val();
            var nosj = $("#nosj").val();
            var tglkeluar = $("#tglkeluar").val();
            var jmlroll = $("#jmlroll").inputmask('unmaskedvalue');
            var act = $("#form").attr("act");
            if (act == "add"){
                var data = {NOPOL: nopol, NOSJ: nosj, SOPIR: sopir, JMLROLL: jmlroll, TGL_KELUAR: tglkeluar};
                @hasanyrole('superadmin|admin|gudang')
                data.JENISTRUK = $("#jenistruk option:selected").val();
                data.EKSPEDISI = $("#ekspedisi option:selected").val();
                data.JENIS_TRUK = $("#jenistruk option:selected").html();
                data.NAMAEKSPEDISI = $("#ekspedisi option:selected").html();
                @endhasanyrole
                tabel.row.add(data).draw();
                $("#nopol").val("");
                $("#nosj").val("");
                $("#sopir").val("");
                $("#jmlroll").val("");
                $("#tglkeluar").val("");
                @hasanyrole('superadmin|admin|gudang')
                $("#jenistruk").val("");
                $("#ekspedisi").val("");
                @endhasanyrole
                $("#nopol").focus();
            }
            else if (act == "edit"){
                var id = $("#iddetail").val();
                var idx = $("#idxdetail").val();
                var data = tabel.row(idx).data();
                data.NOPOL = nopol;
                data.NOSJ = nosj;
                data.SOPIR = sopir;
                data.JMLROLL = jmlroll;
                data.TGL_KELUAR = tglkeluar;
                @hasanyrole('superadmin|admin|gudang')
                data.JENISTRUK = $("#jenistruk option:selected").val();
                data.EKSPEDISI = $("#ekspedisi option:selected").val();
                data.JENIS_TRUK = $("#jenistruk option:selected").html();
                data.NAMAEKSPEDISI = $("#ekspedisi option:selected").html();
                @endhasanyrole
                tabel.row(idx).data(data).draw();
                $("#modaldetail").modal("hide");
            }
            $(this).prop("disabled", false);
        });
        $("#adddetail").on("click", function(){
            $("#nopol").val("");
            $("#sopir").val("");
            $("#jmlroll").val("");
            $("#nosj").val("");
            $("#tglkeluar").val("");
            @hasanyrole('superadmin|admin|gudang')
            $("#jenistruk").val("");
            $("#ekspedisi").val("");
            @endhasanyrole
            $("#nopol").focus();
            $("#modaldetail .modal-title").html("Tambah ");
            $("#form").attr("act","add");
        })
        $("body").on("click", ".edit", function(){
            var row = $(this).closest("tr");
            var index = tabel.row(row).index();
            var row = tabel.rows(index).data();
            $("#sopir").val(row[0].SOPIR);
            $("#nopol").val(row[0].NOPOL);
            $("#nosj").val(row[0].NOSJ);
            $("#jmlroll").val(row[0].JMLROLL);
            $("#tglkeluar").val(row[0].TGL_KELUAR);
            $("#idxdetail").val(index);
            $("#iddetail").val(row[0].ID);
            @hasanyrole('superadmin|admin|gudang')
            $("#jenistruk").val(row[0].JENISTRUK);
            $("#ekspedisi").val(row[0].EKSPEDISI);
            @endhasanyrole
            $("#modaldetail .modal-title").html("Edit ");
            $("#form").attr("act","edit");
        })
        $("body").on("click", ".del", function(){
            var row = $(this).closest("tr");
            var index = tabel.row(row).remove().draw();
        })
        $('#modaldetail').on('shown.bs.modal', function (e) {
            $("#savedetail").removeClass("disabled");
            $('#kodebarang').focus();
        })
        @endif
    })
</script>
@endpush
