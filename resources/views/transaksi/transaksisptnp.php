{% extends 'base.html.twig' %}
{% block body %}
<style>
    .error {display:none;font-size: 0.75rem;color: red};
</style>
<div class="row">
    <div class="card col-md-12 p-0">
        <div class="card-header font-weight-bold">
            <div class="row">
                <div class="col-md-4 py-0 pl-4 mt-1">
                    Form Perekaman SPTNP {{ notransaksi }}
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                </div>
            </div>
        </div>
        <form id="transaksi" autocomplete="off">
        <div class="card-body">
            <input type="hidden" value="{{ header.ID }}" id="idtransaksi" name="idtransaksi">
            <div class="col-md-7 co-sm-12 px-auto pt-0">   
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Nopen</label>
                    <div class="col-md-3">
                        <input type="text" maxlength="24" class="form-control form-control-sm" name="nopen" id="nopen" value="{{ header.NOPEN }}">
                        <p class="error nopen">Nopen harus diisi</p>
                    </div>
                    <label class="col-md-2 col-form-label form-control-sm">Tgl Nopen</label>
                    <div class="col-md-3">                                       
                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglnopen" value="{{ header.TGL_NOPEN }}" id="tglnopen">
                    </div>
                </div>
                <div class="form-row px-2 pb-0">
                    <label class="col-md-2 col-form-label form-control-sm">BBM</label>
                    <div class="col-md-3">
                        <input type="text" class=" number form-control form-control-sm" name="bbm" value="{{ header.BBM }}" id="bbm">
                    </div> 
                    <label class="col-md-2 col-form-label form-control-sm">PPN</label>                   
                    <div class="col-md-3">                                                            
                        <input type="text" class=" number form-control form-control-sm" name="ppn" value="{{ header.PPN }}" id="ppn">
                    </div>
                </div>
                <div class="form-row px-2 pb-0">                    
                    <label class="col-md-2 col-form-label form-control-sm">PPH</label>                   
                    <div class="col-md-3">                                                            
                        <input type="text" class=" number form-control form-control-sm" name="pph" value="{{ header.PPH }}" id="pph">
                    </div>                                    
                    <label class="col-md-2 col-form-label form-control-sm">Denda</label>
                    <div class="col-md-3">
                        <input type="text" class=" number form-control form-control-sm" name="denda" value="{{ header.DENDA }}" id="denda">
                    </div> 
                </div>
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">BMT</label>                   
                    <div class="col-md-3">                                                            
                        <input type="text" class=" number form-control form-control-sm" name="bmt" value="{{ header.BMT }}" id="bmt">
                    </div>                                    
                    <label class="col-md-2 col-form-label form-control-sm">Total</label>
                    <div class="col-md-3">
                        <input type="text" class=" number form-control form-control-sm" name="total" value="{{ header.TOTAL_SPTNP }}" id="total">
                    </div>
                </div>
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Jenis Kesalahan</label>
                    <div class="col-md-3">
                        <select class="form-control form-control-sm" id="jeniskesalahan" name="jeniskesalahan" value="{{ header.JENIS_KESALAHAN }}">
                            <option {% if header.JENIS_KESALAHAN == "" %}selected{% endif %} value=""></option>
                            <option {% if header.JENIS_KESALAHAN == "N" %}selected{% endif %} value="N">NP</option>
                            <option {% if header.JENIS_KESALAHAN == "H" %}selected{% endif %} value="H">HS</option>
                            <option {% if header.JENIS_KESALAHAN == "G" %}selected{% endif %} value="G">Gugur Form</option>
                        </select>
                    </div>
                </div>                                              
            </div>
        </div>
        </form>
    </div>
</div>

{% endblock %}