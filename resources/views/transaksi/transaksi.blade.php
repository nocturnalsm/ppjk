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
                    <input type="hidden" name="idxdetail" id="idxdetail">
                    <input type="hidden" name="iddetail" id="iddetail">
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="kodehs">Kode HS</label>
                        <div class="col-md-9">
                            <input type="text" id="kodehs" name="kodehs" class="form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="booking">Booking</label>
                        <div class="col-md-9">
                            <input type="text" id="booking" name="booking" class="number form-control form-control-sm validate">
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <label class="col-form-label col-md-3" for="satuan">Satuan</label>
                        <div class="col-md-9 pt-2">
                            <select class="form-control form-control-sm" id="satuan" name="satuan">
                                <option value=""></option>
                                @foreach($datasatuan as $satuan)
                                <option value="{{ $satuan->id }}">{{ $satuan->satuan }}</option>
                                @endforeach
                            </select>
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
<div class="modal fade" id="modalkontainer" tabindex="-1" role="dialog" aria-labelledby="modalkontainer" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <form id="formkontainer" act="">
                    <input type="hidden" name="idxdetailkontainer" id="idxdetailkontainer">
                    <input type="hidden" name="iddetailkontainer" id="iddetailkontainer">
                    <div class="mb-1">
                        <label for="nokontainer">No. Kontainer</label>
                        <input type="text" maxlength="15" id="nokontainer" name="nokontainer" class="form-control form-control-sm validate">
                    </div>
                    <div class="mb-1">
                        <label for="ukuran">Ukuran Kontainer</label>
                        <select class="form-control form-control-sm" id="ukuran" name="ukuran">
                            @foreach($ukurankontainer as $ukur)
                            <option value="{{ $ukur->KODE }}">{{ $ukur->URAIAN }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <a id="savekontainer" class="btn btn-primary">Simpan</a>
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
                    Form Perekaman Schedule {{ $notransaksi }}
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                    @if($canDelete)
                    <button type="button" id="deletetrans" class="btn btn-danger btn-sm m-0">Hapus</button>
                    <form id="formdelete">
                    @csrf
                    <input type="hidden" name="iddelete" value="{{ $header->ID }}">
                    </form>
                    @endif
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input type="hidden" value="{{ $header->ID }}" id="idtransaksi" name="idtransaksi">
            <div class="row mt-0 mb-2">
                <label class="form-control-sm">Kantor</label>
                <div class="col-md-2 px-1">
                    <select class="form-control form-control-sm" id="kantor" name="kantor" value="{{ $header->KANTOR_ID }}">
                        <option value=""></option>
                        @foreach($kodekantor as $kantor)
                        <option @if($header->KANTOR_ID == $kantor->KANTOR_ID)selected @endif value="{{ $kantor->KANTOR_ID }}">{{ $kantor->URAIAN }}</option>
                        @endforeach
                    </select>
                    <p class="error kantor">Kode Kantor harus dipilih</p>
                </div>
                @can('customer.view')
                <label class="col-md-auto col-form-label form-control-sm">Customer</label>
                <div class="col-md-3">
                    <select class="form-control form-control-sm" id="customer" name="customer" value="{{ $header->CUSTOMER }}">
                        <option value=""></option>
                        @foreach($customer as $cust)
                        <option @if($header->CUSTOMER == $cust->id_customer)selected @endif value="{{ $cust->id_customer }}">{{ $cust->nama_customer }}</option>
                        @endforeach
                    </select>
                    <p class="error customer">Customer harus dipilih</p>
                </div>
                @endcan
                <label class="col-md-auto col-form-label form-control-sm">Level Dok</label>
                <div class="form-check-inline d-inline mt-1">
                    <label class="form-check-label pr-2">
                        <input @if($header->LEVEL_DOK == 'K')checked @endif type="radio" class="form-check-input" name="leveldok" value="K">
                        <span class="bg-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </label>
                    <label class="form-check-label pr-2">
                        <input @if($header->LEVEL_DOK == 'H')checked @endif type="radio" class="form-check-input" name="leveldok" value="H">
                        <span class="bg-success">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </label>
                    <label class="form-check-label pr-2">
                        <input @if($header->LEVEL_DOK == 'M')checked @endif type="radio" class="form-check-input" name="leveldok" value="M">
                        <span class="bg-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </label>
                </div>
            </div>
            <div class="row px-2">
                <div class="col-md-5 pt-0">
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <h5 class="card-title">Data Pengirim</h5>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm ">Shipper</label>
                                    <div class="col-md-9">
                                        <select class="form-control form-control-sm" id="shipper" name="shipper" value="{{ $header->SHIPPER }}">
                                            <option value=""></option>
                                            @foreach($shipper as $ship)
                                            <option @if($header->SHIPPER == $ship->id_pemasok) selected @endif value="{{ $ship->id_pemasok }}">{{ $ship->nama_pemasok }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Pelabuhan Muat</label>
                                    <div class="col-md-9">
                                        <select class="form-control form-control-sm" id="pelmuat" name="pelmuat" value="{{ $header->PEL_MUAT }}">
                                            <option value=""></option>
                                            @foreach($pelmuat as $pel)
                                            <option @if($header->PEL_MUAT == $pel->PELMUAT_ID)selected @endif value="{{ $pel->PELMUAT_ID }}">{{ $pel->URAIAN }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Tanggal Berangkat</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglberangkat" id="tglberangkat" value="{{ $header->TGL_BERANGKAT }}">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm px-0">Tanggal Tiba</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ $header->TGL_TIBA }}" name="tgltiba" id="tgltiba">
                                        <p class="error tgltiba">Tgl Tiba harus diisi</p>
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Kapal</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control form-control-sm" value="{{ $header->KAPAL }}" name="kapal" id="kapal">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 form-control-sm">Consignee</label>
                                    <div class="col-md-9">
                                        <select class="form-control form-control-sm" id="consignee" name="consignee" value="{{ $header->CONSIGNEE }}">
                                            <option value=""></option>
                                            @foreach($importir as $imp)
                                            <option @if($header->CONSIGNEE == $imp->IMPORTIR_ID)selected @endif value="{{ $imp->IMPORTIR_ID }}">{{ $imp->NAMA }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Importir</label>
                                    <div class="col-md-9">
                                        <select class="form-control form-control-sm" id="importir" name="importir" value="{{ $header->IMPORTIR }}">
                                            <option value=""></option>
                                            @foreach($importir as $imp)
                                            <option @if($header->IMPORTIR == $imp->IMPORTIR_ID)selected @endif value="{{ $imp->IMPORTIR_ID }}">{{ $imp->NAMA }}</option>
                                            @endforeach
                                        </select>
                                        <p class="error importir">Importir harus dipilih</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <h5 class="card-title">Data Dokumen</h5>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">No. Inv</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="24" class="form-control form-control-sm" name="noinv" id="noinv" value="{{ $header->NO_INV }}">
                                        <p class="error noinv">Nomor Inv harus diisi</p>
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Inv</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglinv" value="{{ $header->TGL_INV }}" id="tglinv">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-3 col-form-label form-control-sm">No. PO</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" name="nopo" value="{{ $header->NO_PO }}" id="nopo">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl PO</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglpo" value="{{ $header->TGL_PO }}" id="tglpo">
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-3 col-form-label form-control-sm">No. S/C</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" name="nosc" value="{{ $header->NO_SC }}" id="nosc">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl S/C</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglsc" value="{{ $header->TGL_SC }}" id="tglsc">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">No. BL</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="24" class="form-control form-control-sm" name="nobl" id="nobl" value="{{ $header->NO_BL }}">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl BL</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglbl" value="{{ $header->TGL_BL }}" id="tglbl">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm ">No. Form</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control form-control-sm" id="noform" name="noform" value="{{ $header->NO_FORM }}">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Form</label>
                                    <div class="col-md-3">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglform" value="{{ $header->TGL_FORM }}" id="tglform">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <h5 class="card-title">Data Barang</h5>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Jumlah Kemasan</label>
                                    <div class="col-md-2">
                                        <input type="text" class="number form-control form-control-sm" name="jmlkemasan" value="{{ $header->JUMLAH_KEMASAN }}" id="jmlkemasan">
                                    </div>
                                    <label class="col-md-3 col-form-label form-control-sm ">Jenis Kemasan</label>
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" id="jeniskemasan" name="jeniskemasan" value="{{ $header->JENIS_KEMASAN }}">
                                            <option value=""></option>
                                            @foreach($jeniskemasan as $jenis)
                                            <option @if($header->JENIS_KEMASAN == $jenis->JENISKEMASAN_ID)selected @endif value="{{ $jenis->JENISKEMASAN_ID }}">{{ $jenis->URAIAN }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9"><p class="error jmlkemasan">Jumlah Kemasan harus diisi</p></div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Jenis Barang</label>
                                    <div class="col-md-3">
                                        <select class="form-control form-control-sm" id="jenisbarang" name="jenisbarang" value="{{ $header->JENIS_BARANG }}">
                                            <option value=""></option>
                                            @foreach($jenisbarang as $jenis)
                                            <option @if($header->JENIS_BARANG == $jenis->JENISBARANG_ID)selected @endif value="{{ $jenis->JENISBARANG_ID }}">{{ $jenis->URAIAN }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="px-1 col-form-label form-control-sm">GW</label>
                                    <div class="col-md-2">
                                        <input type="text" class="number form-control form-control-sm" name="gw" value="{{ $header->GW }}" id="gw">
                                    </div>
                                    <label class="px-1 col-form-label form-control-sm">CBM</label>
                                    <div class="col-md-2">
                                        <input type="text" class="number form-control form-control-sm" name="cbm" value="{{ $header->CBM }}" id="cbm">
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9"><p class="error jenisbarang">Jenis Barang harus dipilih</p></div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-3 col-form-label form-control-sm">Kode HS</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control form-control-sm" name="kodehs" id="kodehs">{{ $header->KODE_HS }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card col-sm-12 col-md-12 p-0">
                            <div class="card-body p-3">
                                <h5 class="card-title">Data Kontainer</h5>
                                <div class="form-row px-2">
                                    <label class="col-md-3 form-control-sm">Jumlah Kontainer</label>
                                    <div class="col-md-2">
                                        <select class="form-control form-control-sm" id="jmlkontainer" name="jmlkontainer" value="{{ $header->JUMLAH_KONTAINER }}">
                                            <option value=""></option>
                                            @foreach($jumlahkontainer as $jml)
                                            <option @if($header->JUMLAH_KONTAINER == $jml->JUMLAH)selected @endif value="{{ $jml->JUMLAH }}">{{ $jml->JUMLAH }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9"><p class="error jmlkontainer">Jumlah Kontainer harus dipilih</p></div>
                                </div>
                                <div class="form-row">
                                    <div class="col primary-color text-white py-2 px-4">
                                        Detail Kontainer
                                    </div>
                                    <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                                        <a href="#modalkontainer" data-toggle="modal" class="text-white" id="addkontainer">Tambah Detail</a>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mt-2">
                                        <table width="100%" id="gridkontainer" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nomor</th>
                                                    <th>Ukuran</th>
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
                <div class="col-md-7 px-auto pt-0">
                    <div class="card col-md-12 p-0 mb-2">
                        <div class="card-body p-3">
                            <h5 class="card-title">Proses VO</h5>
                            <div class="form-row px-2">
                                <label class="col-md-3 col-form-label form-control-sm">No. PI</label>
                                <div class="col-md-4">
                                    <input type="text" readonly maxlength="24" class="form-control form-control-sm" name="nopi" id="nopi" value="{{ $pi->NO_PI }}">
                                    <input type="hidden" id="idpi" name="idpi" value="{{ $pi->ID }}">
                                </div>
                                <label class="col-md-1 col-form-label form-control-sm">Tgl. PI</label>
                                <div class="col-md-2">
                                    <input type="text" readonly maxlength="24" class="form-control form-control-sm" name="tglpi" id="tglpi" value="{{ $pi->TGLPI }}">
                                </div>
                            </div>
                            <div class="form-row px-2">
                                <label class="col-md-3 col-form-label form-control-sm">No. VO</label>
                                <div class="col-md-5">
                                    <input type="text" maxlength="24" class="form-control form-control-sm" name="novo" id="novo" value="{{ $header->NO_VO }}">
                                </div>
                                <label class="col-md-1 px-0 col-form-label form-control-sm">Tgl VO</label>
                                <div class="col-md-2">
                                    <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglvo" value="{{ $header->TGL_VO }}" id="tglvo">
                                </div>
                            </div>
                            <div class="form-row px-2 pb-0">
                                <label class="col-md-3 col-form-label form-control-sm">Kode HS</label>
                                <div class="col-md-9">
                                    <textarea class="form-control form-control-sm" name="kodehsvo" id="kodehsvo">{{ $header->KODE_HS_VO }}</textarea>
                                </div>
                            </div>
                            <div class="form-row px-2">
                                <label class="col-md-3 col-form-label form-control-sm">Tgl Periksa</label>
                                <div class="col-md-3">
                                    <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglperiksavo" value="{{ $header->TGL_PERIKSA_VO }}" id="tglperiksavo">
                                </div>
                                <label class="col-md-auto col-form-label form-control-sm">Tgl LS</label>
                                <div class="col-md-3">
                                    <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglls" value="{{ $header->TGL_LS }}" id="tglls">
                                </div>
                            </div>
                            <div class="form-row px-2">
                                <label class="col-md-3 col-form-label form-control-sm">Status</label>
                                <div class="col-md-4">
                                    <select class="form-control form-control-sm" id="status" name="status" value="{{ $header->STATUS }}">
                                        <option @if($header->STATUS == "")selected @endif value=""></option>
                                        <option @if($header->STATUS == "K")selected @endif value="K">Konfirmasi</option>
                                        <option @if($header->STATUS == "B")selected @endif value="B">Belum Inspect</option>
                                        <option @if($header->STATUS == "S")selected @endif value="S">Sudah Inspect</option>
                                        <option @if($header->STATUS == "R")selected @endif value="R">Revisi FD</option>
                                        <option @if($header->STATUS == "F")selected @endif value="F">FD</option>
                                        <option @if($header->STATUS == "L")selected @endif value="L">LS Terbit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row pt-2 px-2">
                                <div class="card col-md-12 p-0">
                                    <div class="card-body p-3">
                                        <div class="form-row">
                                            <div class="col primary-color text-white py-2 px-4">
                                                Detail Quota
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
                                                            <th>Kode HS</th>
                                                            <th>Booking</th>
                                                            <th>Satuan</th>
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
                    <div class="form-row">
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <h5 class="card-title">Proses Dokumen</h5>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">Jns Dokumen</label>
                                    <div class="col-md-2">
                                        <select class="form-control form-control-sm" id="jenisdokumen" name="jenisdokumen" value="{{ $header->JENIS_DOKUMEN }}">
                                            <option value=""></option>
                                            @foreach($jenisdokumen as $jenis)
                                            <option @if($header->JENIS_DOKUMEN == $jenis->JENISDOKUMEN_ID)selected @endif value="{{ $jenis->JENISDOKUMEN_ID }}">{{ $jenis->URAIAN }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Nopen</label>
                                    <div class="col-md-2">
                                        <input maxlength="6" type="text" class="form-control form-control-sm" name="nopen" value="{{ $header->NOPEN }}" id="nopen">
                                        <p class="error nopen">Nopen 6 digit</p>
                                    </div>
                                    <label class="col-md-auto col-form-label form-control-sm">Tgl Nopen</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglnopen" value="{{ $header->TGL_NOPEN }}" id="tglnopen">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">No.Aju</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control form-control-sm" name="noaju" value="{{ $header->NOAJU }}" id="noaju">
                                    </div>
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Periksa</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglperiksa" value="{{ $header->TGL_PERIKSA }}" id="tglperiksa">
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">Hasil Periksa</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control form-control-sm" name="hasilperiksa" id="hasilperiksa">{{ $header->HASIL_PERIKSA }}</textarea>
                                    </div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-2 col-form-label form-control-sm">Catatan</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control form-control-sm" name="catatan" id="catatan">{{ $header->CATATAN }}</textarea>
                                    </div>
                                </div>
                                <div class="form-row px-2 pb-0">
                                    <label class="col-md-2 col-form-label form-control-sm ">Tanggal SPPB</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ $header->TGL_SPPB }}" name="tglsppb" id="tglsppb">
                                    </div>
                                    <label class="col-form-label form-control-sm ">Tanggal Keluar</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ $header->TGL_KELUAR }}" name="tglkeluar" id="tglkeluar">
                                    </div>
                                    <label class="col-md-auto col-form-label form-control-sm">Tgl Masuk Gudang</label>
                                    <div class="col-md-2">
                                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglmasuk" value="{{ $header->TGL_MASUK_GUDANG }}" id="tglmasuk">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="deletekontainer">
        <input type="hidden" name="deletedetail">
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

var detail = @json($quota);
datadetail = JSON.parse(detail);
var detailkontainer = @json($kontainer);
datadetailkontainer = JSON.parse(detailkontainer);

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
$('#modalkontainer').on('shown.bs.modal', function () {
    $('#nokontainer').focus();
})
$("#savekontainer").on("click", function(){
    var nomor = $("#nokontainer").val();
    var ukuran = $("#ukuran").val();
    var namaukuran = $("#ukuran option:selected").html();
    var act = $("#formkontainer").attr("act");
    if (act == "add"){
        tabelkontainer.row.add({NOMOR_KONTAINER: nomor, UKURAN_KONTAINER: ukuran, URAIAN: namaukuran}).draw();
        $("#nokontainer").val("");
        $("#ukuran").val("");
        $("#nokontainer").focus();
    }
    else if (act == "edit"){
        var id = $("#iddetailkontainer").val();
        var idx = $("#idxdetailkontainer").val();
        tabelkontainer.row(idx).data({ID: id, NOMOR_KONTAINER: nomor, UKURAN_KONTAINER: ukuran, URAIAN: namaukuran}).draw();
        $("#modalkontainer").modal("hide");
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
    oncleared: function () { self.setValue(''); }
});
var tabelkontainer = $("#gridkontainer").DataTable({
    processing: false,
    serverSide: false,
    data: datadetailkontainer,
    dom: "t",
    pageLength: 50,
    rowCallback: function(row, data)
    {
        $(row).attr("id-transaksi", data.id);
        $('td:eq(1)', row).html('<input type="hidden" class="ukurankontainer" value="' + data.UKURAN_KONTAINER + '">' + data.URAIAN);
        $('td:eq(2)', row).html('<a href="#modalkontainer" class="editkontainer" data-toggle="modal" idkontainer="' + data.ID +
                                '"><i class="fa fa-edit"></i></a>' +
                                '&nbsp;&nbsp;<a class="delkontainer" idkontainer="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                );
    },
    select: 'single',     // enable single row selection
    responsive: false,     // enable responsiveness,
    rowId: 0,
    columns: [{
                target: 0,
                data: "NOMOR_KONTAINER"
              },
              { target: 1,
                data: "URAIAN"
              },
              { target: 2,
                data: null
              }
             ]
})
$("#addkontainer").on("click", function(){
    $("#nokontainer").val("");
    $("#ukuran").val("");
    $("#modalkontainer .modal-title").html("Tambah Kontainer");
    $("#formkontainer").attr("act","add");
})
$("body").on("click", ".editkontainer", function(){
    var row = $(this).closest("tr");
    var index = tabelkontainer.row(row).index();
    var row = tabelkontainer.rows(index).data();
    $("#nokontainer").val(row[0].NOMOR_KONTAINER);
    $("#ukuran").val(row[0].UKURAN_KONTAINER);
    $("#idxdetailkontainer").val(index);
    $("#iddetailkontainer").val(row[0].ID);
    $("#modalkontainer .modal-title").html("Edit Kontainer");
    $("#formkontainer").attr("act","edit");
})
$("body").on("click", ".delkontainer", function(){
    var row = $(this).closest("tr");
    var id = tabelkontainer.row(row).data().ID;
    if (typeof id != 'undefined'){
        $("input[name='deletekontainer'").val($("input[name='deletekontainer'").val() + id + ";");
    }
    var index = tabelkontainer.row(row).remove().draw();
})
$("#nopen").inputmask({"mask": "999999","removeMaskOnSubmit": true});
function checkEmpty(elem){
    if ($("#" + elem).val().trim() === ""){
        $(".error." + elem).show();
        var empty = true;
    }
    else {
        $(".error." + elem).hide();
        var empty = false;
    }
    return empty;
}
function checkComplete(elem){
    if ($("#" + elem).inputmask("unmaskedvalue").trim() != "" && !$("#" + elem).inputmask("isComplete")){
        $(".error." + elem).show();
        var complete = false;
    }
    else {
        $(".error." +elem).hide();
        var complete = true;
    }
    return complete;
}
function validate(){
    var valid = true;
    var check1 = ["noinv","jmlkemasan","customer","importir","tgltiba",
                  "jmlkontainer","jenisbarang","kantor"];
    var check2 = ["nopen"];
    $(check1).each(function(index, elem){
        if (checkEmpty(elem)){
            valid = false;
        }
    });
    $(check2).each(function(index, elem){
        if (!checkComplete(elem)){
            valid = false;
            console.log(elem);
        }
    });
    console.log(valid);
    return valid;
}
$("#noinv, #jmlkemasan, #tgltiba, #customer, #importir, #jmlkontainer, #kantor, #jenisbarang, #nopen").on("input", function(){
    $(".error." + $(this).attr("id")).hide();
})
$("#btnsimpan").on("click", function(){
        var detail = [];
        var rows = tabel.rows().data();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          ;
        $(rows).each(function(index,elem){
            detail.push(elem);
        })

        $(this).prop("disabled", true);
        var detailkontainer = [];
        var rowskontainer = tabelkontainer.rows().data();
        $(rowskontainer).each(function(index,elem){
            detailkontainer.push(elem);
        })
        $(".loader").show()
        $.ajax({
            url: "/transaksi/crud",
            data: {header: $("#transaksi").serialize(), _token: "{{ csrf_token() }}", kontainer: detailkontainer, detail: detail},
            type: "POST",
            success: function(msg) {
                console.log(msg);
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
                        if ($("#idtransaksi").val().trim() == ""){
                            document.location.href = "/transaksi";
                        }
                        else {
                            document.location.reload();
                        }
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
$("#deletetrans").on("click", function(){
    $("#modal .btn-ok").removeClass("d-none");
    $("#modal .btn-close").html("Batal");
    $("#modal .modal-body").html("Apakah Anda ingin menghapus data ini?");
    $("#modal .btn-ok").html("Ya").on("click", function(){
        $.ajax({
            url: "/transaksi/delete",
            data: $("#formdelete").serialize(),
            type: "POST",
            success: function(msg) {
                $("#modal").modal("hide");
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
                    window.location.href = "/transaksi/search";
                }
            }
        })
    })
    $("#modal").modal("show");
})
$("#savedetail").on("click", function(){
    $(this).prop("disabled", true);
    var booking = $("#booking").inputmask('unmaskedvalue');
    var kodehs = $("#kodehs").val();
    var satuan = $("#satuan").val();
    var namasatuan = $("#satuan option:selected").html();
    var act = $("#form").attr("act");

    if (act == "add"){
        tabel.row.add({BOOKING: booking, KODE_HS: kodehs, SATUAN_ID: satuan, satuan: namasatuan}).draw();
        $("#kodehs").val("");
        $("#booking").val("");
        $("#satuan").val("");
        $("#kodehs").focus();
    }
    else if (act == "edit"){
        var id = $("#iddetail").val();
        var idx = $("#idxdetail").val();
        tabel.row(idx).data({ID: id, BOOKING: booking, KODE_HS: kodehs, SATUAN_ID: satuan, satuan: namasatuan}).draw();
        $("#modaldetail").modal("hide");
    }
    $(this).prop("disabled", false);
});
var tabel = $("#griddetail").DataTable({
    processing: false,
    serverSide: false,
    data: datadetail,
    dom: "t",
    pageLength: 1000,
    rowCallback: function(row, data)
    {
        $('td:eq(1)', row).html(parseFloat(data.BOOKING).formatMoney(2,"",",","."));
        $('td:eq(3)', row).html('<a href="#modaldetail" class="edit" data-toggle="modal" id="' + data.ID +
                                '"><i class="fa fa-edit"></i></a>' +
                                '&nbsp;&nbsp;<a class="del" id="' + data.ID + '"><i class="fa fa-trash"></i></a>'
                                );
    },
    select: 'single',     // enable single row selection
    responsive: false,     // enable responsiveness,
    rowId: 0,
    columns: [{
        target: 0,
        data: "KODE_HS"
      },
      { target: 1,
        data: "BOOKING"
      },
      { target: 2,
        data: "satuan"
      },
      { target: 3,
        data: null
      }
     ],
})
$("#adddetail").on("click", function(){
    $("#kodehs").val("");
    $("#booking").val("");
    $("#satuan").val("");
    $("#modaldetail .modal-title").html("Tambah ");
    $("#form").attr("act","add");
})
$("body").on("click", ".edit", function(){
    var row = $(this).closest("tr");
    var index = tabel.row(row).index();
    var row = tabel.rows(index).data();
    $("#booking").val(row[0].BOOKING);
    $("#kodehs").val(row[0].KODE_HS);
    $("#satuan").val(row[0].SATUAN_ID);
    $("#idxdetail").val(index);
    $("#iddetail").val(row[0].ID);
    $("#modaldetail .modal-title").html("Edit ");
    $("#form").attr("act","edit");
})
$("body").on("click", ".del", function(){
    var row = $(this).closest("tr");
    var id = tabel.row(row).data().ID;
    if (typeof id != 'undefined'){
        $("input[name='deletedetail'").val($("input[name='deletedetail'").val() + id + ";");
    }
    var index = tabel.row(row).remove().draw();
})
$("#kodehs").inputmask("9999.99.99");
$("#consignee").on("change", function(){
    var id = $(this).find("option:selected").val();
    $.ajax({
        url: "/transaksi/getpi",
        data: {id: id, _token: "{{ csrf_token() }}"},
        method: "POST",
        success: function(response){
            $("#nopi").val(response.NO_PI);
            $("#idpi").val(response.ID);
            $("#tglpi").val(response.TGL_PI);
        }
    });
});

})
</script>
@endpush
