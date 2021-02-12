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
                    Form Data Bongkar {{ notransaksi }}
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
            <div class="row px-2">
                <div class="col-md-12 col-lg-6 pt-0">                    
                    <div class="row"> 
                        <div class="card col-md-12 p-0 mb-2">
                            <div class="card-body p-3">
                                <h5 class="card-title">Hasil Bongkar</h5>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">No. BL</label>
                                    <div class="col-md-5">
                                        <input type="text" maxlength="24" class="form-control form-control-sm" name="nobl" id="nobl" value="{{ header.NO_BL }}">
                                        <p class="error nobl">No. BL harus diisi</p>
                                    </div>
                                </div>     
                                {% if userlevel < 1 %}
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Customer</label>
                                    <div class="col-md-9">
                                        <select class="form-control form-control-sm" id="customer" name="customer" value="{{ header.CUSTOMER }}">
                                            <option value=""></option>
                                            {% for cust in customer %}
                                            <option {% if header.CUSTOMER == cust.id_customer %}selected{% endif %} value="{{ cust.id_customer }}">{{ cust.nama_customer }}</option>
                                            {% endfor %}
                                        </select>
                                        <p class="error customer">Customer harus dipilih</p>
                                    </div>         
                                </div>
                                {% endif %}                   
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Jumlah Kemasan</label>
                                    <div class="col-md-2">
                                        <input type="text" class="number form-control form-control-sm" name="jmlkemasan" value="{{ header.JUMLAH_KEMASAN }}" id="jmlkemasan">   
                                    </div>
                                    <label class="col-md-3 col-form-label form-control-sm ">Jenis Kemasan</label>
                                    <div class="col-md-4">
                                        <select class="form-control form-control-sm" id="jeniskemasan" name="jeniskemasan" value="{{ header.JENIS_KEMASAN }}">
                                            <option value=""></option>
                                            {% for jenis in jeniskemasan %}
                                            <option {% if header.JENIS_KEMASAN == jenis.JENISKEMASAN_ID %}selected{% endif %} value="{{ jenis.JENISKEMASAN_ID }}">{{ jenis.URAIAN }}</option>
                                            {% endfor %}
                                        </select>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9">
                                        <p class="error jmlkemasan">Jumlah Kemasan harus diisi</p>
                                    </div>
                                </div>                                                                
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Dokumen In</label>
                                    <div class="col-md-2">
                                        <select class="form-control form-control-sm" id="jenisdokumen1" name="jenisdokumen1" value="{{ header.JENIS_DOKUMEN1 }}">
                                            <option value=""></option>
                                            {% for jenis in jenisdokumen %}
                                            <option {% if header.JENIS_DOKUMEN1 == jenis.JENISDOKUMEN_ID %}selected{% endif %} value="{{ jenis.JENISDOKUMEN_ID }}">{{ jenis.URAIAN }}</option>
                                            {% endfor %}
                                        </select>            
                                    </div>   
                                    <label class="col-md-2 col-form-label form-control-sm">Aju</label>
                                    <div class="col-md-2">
                                        <input maxlength="6" type="text" class="form-control form-control-sm" name="aju1" value="{{ header.AJU1 }}" id="aju1">
                                    </div> 
                                    <div class="col-md-5"></div>
                                    <div class="col-md-7"><p class="error aju1">Masukkan Aju 6 digit</p></div>
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Nopen</label>
                                    <div class="col-md-2">
                                        <input maxlength="6" type="text" class="form-control form-control-sm" name="nopen1" value="{{ header.NOPEN1 }}" id="nopen1">
                                    </div>   
                                    <label class="col-md-2 col-form-label form-control-sm">Tgl Nopen</label>
                                    <div class="col-md-2">
                                        <input type="text" class="datepicker form-control form-control-sm" name="tglnopen1" value="{{ header.TGL_NOPEN1 }}" id="tglnopen1">
                                    </div>         
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9"><p class="error nopen1">Masukkan Nopen 6 digit</p></div>
                                </div>     
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Tanggal Bongkar</label>
                                    <div class="col-md-3">
                                        <input type="text" class="datepicker form-control form-control-sm" name="tglbongkar" value="{{ header.TGL_BONGKAR }}" id="tglbongkar">
                                    </div>         
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Tanggal Keluar</label>
                                    <div class="col-md-3">
                                        <input type="text" class="datepicker form-control form-control-sm" name="tglkeluar" value="{{ header.TGL_KELUAR }}" id="tglkeluar">
                                    </div>         
                                </div>
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Hasil Bongkar</label>
                                    <div class="col-md-3">
                                        <select class="form-control form-control-sm" id="hasilbongkar" name="hasilbongkar" value="{{ header.HASIL_BONGKAR }}">
                                            <option {% if header.HASIL_BONGKAR == "" %}selected{% endif %} value=""></option>
                                            <option {% if header.HASIL_BONGKAR == "Y" %}selected{% endif %} value="Y">Sesuai</option>
                                            <option {% if header.HASIL_BONGKAR == "T" %}selected{% endif %} value="T">Tidak Sesuai</option>
                                        </select>            
                                    </div>                                               
                                </div>                        
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Catatan</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control form-control-sm" name="catatan" id="catatan">{{ header.CATATAN }}</textarea>                            
                                    </div>           
                                </div>   
                                <div class="form-row px-2">
                                    <label class="col-md-3 col-form-label form-control-sm">Status Revisi</label>
                                    <div class="col-md-2">
                                        <select class="form-control form-control-sm" id="statusrevisi" name="statusrevisi" value="{{ header.STATUS_REVISI }}">
                                            <option value=""></option>
                                            {% for rev in statusrevisi %}
                                            <option {% if header.STATUS_REVISI == rev.STATUSREVISI_ID %}selected{% endif %} value="{{ rev.STATUSREVISI_ID }}">{{ rev.STATUS_REVISI }}</option>
                                            {% endfor %}
                                        </select>           
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
{% endblock %}