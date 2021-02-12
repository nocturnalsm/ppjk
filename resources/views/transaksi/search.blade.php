@extends('layouts.base')
@section('main')
    <form id="formsearch">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <input type="text" class="form-control" id="term" name="term" placeholder="Cari berdasarkan BL, Jumlah Kemasan, Nopen, Nama Customer, No Inv, No. Aju">
                    <input type="hidden" name="searchtype" value="all">
                    <div class="input-group-append">
                        <button id="btnsearch" class="btn btn-primary m-0 px-3" type="button">
                        <i class="fa fa-search"></i>
                        </button>
                    </div>                
                </div>
            </div>     
        </div>
    </form>
    <div class="row mt-4">
        <div class="col-md-12">
            <table width="100%" id="gridtransaksi" class="table">
                <thead>
                    <tr>
                        <th>Opsi</th>
                        <th>No. Inv</th>                        
                        <th>Tgl Tiba</th>
                        <th>Jml. Kemasan</th>   
                        <th>No.Aju</th>
                        <th>Nopen</th>
                        <th>Tgl Nopen</th>
                        <th>Customer</th>
                        <th>Tgl Keluar</th>
                        <th>No. BL</th>
                        <th>No. Form</th>
                        <th>No. PO</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@include('transaksi.daftartransaksi')