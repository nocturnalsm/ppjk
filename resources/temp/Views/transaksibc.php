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
                    Form Perekaman BC 2.0 {{ notransaksi }}
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
                    <label class="col-md-2 col-form-label form-control-sm">Level Dok</label>
                    <div class="form-check-inline d-inline mt-1">
                        <label class="form-check-label pr-2">
                            <input {% if header.LEVEL_DOK == 'K' %}checked{% endif %} type="radio" class="form-check-input" name="leveldok" value="K">
                            <span class="bg-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </label>
                        <label class="form-check-label pr-2">
                            <input {% if header.LEVEL_DOK == 'H' %}checked{% endif %} type="radio" class="form-check-input" name="leveldok" value="H">
                            <span class="bg-success">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </label>
                        <label class="form-check-label pr-2">
                            <input {% if header.LEVEL_DOK == 'M' %}checked{% endif %} type="radio" class="form-check-input" name="leveldok" value="M">
                            <span class="bg-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </label>
                    </div>
                    <label class="col-md-1 col-form-label form-control-sm">Jalur</label>
                    <div class="form-check-inline d-inline mt-1">
                        <label class="form-check-label pr-2">
                            <input {% if header.JALUR == 'K' %}checked{% endif %} type="radio" class="form-check-input" name="jalur" value="K">
                            <span class="bg-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </label>
                        <label class="form-check-label pr-2">
                            <input {% if header.JALUR == 'H' %}checked{% endif %} type="radio" class="form-check-input" name="jalur" value="H">
                            <span class="bg-success">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </label>
                        <label class="form-check-label pr-2">
                            <input {% if header.JALUR == 'M' %}checked{% endif %} type="radio" class="form-check-input" name="jalur" value="M">
                            <span class="bg-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </label>
                    </div>
                </div>
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">No.Inv</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control form-control-sm" name="noinv" value="{{ header.NO_INV }}" id="noinv">        
                    </div>
                    <label class="col-md-1 col-form-label form-control-sm">No.BL</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" name="nobl" value="{{ header.NO_BL }}" id="nobl">        
                    </div>
                </div>   
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Jns Dokumen</label>
                    <div class="col-md-2">
                        <select class="form-control form-control-sm" id="jenisdokumen" name="jenisdokumen" value="{{ header.JENIS_DOKUMEN }}">
                            <option value=""></option>
                            {% for jenis in jenisdokumen %}
                            <option {% if header.JENIS_DOKUMEN == jenis.JENISDOKUMEN_ID %}selected{% endif %} value="{{ jenis.JENISDOKUMEN_ID }}">{{ jenis.URAIAN }}</option>
                            {% endfor %}
                        </select>            
                    </div>
                </div>
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">No Aju</label>
                    <div class="col-md-2">
                        <input maxlength="24" type="text" class="form-control form-control-sm" name="noaju" value="{{ header.NOAJU }}" id="noaju">
                        <p class="error nopen">No. Aju harus diisi</p>
                    </div>   
                </div>
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Nopen</label>
                    <div class="col-md-2">
                        <input maxlength="24" type="text" class="form-control form-control-sm" name="nopen" value="{{ header.NOPEN }}" id="nopen">
                        <p class="error nopen">Nopen harus diisi</p>
                    </div>   
                    <label class="col-form-label form-control-sm">Tgl Nopen</label>
                    <div class="col-md-2">
                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" name="tglnopen" value="{{ header.TGL_NOPEN }}" id="tglnopen">
                    </div>         
                </div>   
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Hasil Periksa</label>
                    <div class="col-md-10">
                        <textarea class="form-control form-control-sm" name="hasilperiksa" id="hasilperiksa">{{ header.HASIL_PERIKSA }}</textarea>                            
                    </div>           
                </div>
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Catatan</label>
                    <div class="col-md-10">
                        <textarea class="form-control form-control-sm" name="catatan" id="catatan">{{ header.CATATAN }}</textarea>                            
                    </div>           
                </div>
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Tanggal Periksa</label>
                    <div class="col-md-2">
                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ header.TGL_PERIKSA }}" name="tglperiksa" id="tglperiksa">
                    </div>
                    <label class="col-md-3 col-form-label form-control-sm">Tanggal SPPB</label>
                    <div class="col-md-2">
                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ header.TGL_SPPB }}" name="tglsppb" id="tglsppb">
                    </div>                                                                                           
                </div>        
                <div class="form-row px-2">
                    <label class="col-md-2 col-form-label form-control-sm">Tanggal Keluar</label>
                    <div class="col-md-2">
                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ header.TGL_KELUAR }}" name="tglkeluar" id="tglkeluar">
                    </div>
                    <label class="col-md-3 col-form-label form-control-sm">Tanggal Masuk Gudang</label>
                    <div class="col-md-2">
                        <input autocomplete="off" type="text" class="datepicker form-control form-control-sm" value="{{ header.TGL_MASUK_GUDANG }}" name="tglmasuk" id="tglmasuk">
                    </div>                                                                                           
                </div>                              
            </div>
        </div>
        </form>
    </div>
</div>

{% endblock %}