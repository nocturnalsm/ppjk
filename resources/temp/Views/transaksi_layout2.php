{% extends 'base.html.twig' %}
{% block body %}
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
                            {% for ukur in ukurankontainer %}
                            <option value="{{ ukur.KODE }}">{{ ukur.URAIAN }}</option>
                            {% endfor %}
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
                    Transaksi{{ notransaksi }}
                </div>
                <div class="col-md-8 py-0 pr-4 text-right">
                    <button type="button" id="btnsimpan" class="btn btn-primary btn-sm m-0">Simpan</button>&nbsp;
                    <a href="/" class="btn btn-default btn-sm m-0">Batal</a>&nbsp;
                    {% if canDelete %}
                    <button type="button" id="deletetrans" class="btn btn-danger btn-sm m-0">Hapus</button>
                    <form id="formdelete">
                    <input type="hidden" name="iddelete" value="{{ header.ID }}">
                    </form>
                    {% endif %}
                </div>
            </div>
        </div>
        <form id="transaksi">
        <div class="card-body p-4">
            <div class="row pb-4">
                <input type="hidden" value="{{ header.ID }}" id="idtransaksi" name="idtransaksi">
                <div class="col-md-6">
                    <div class="form-row">
                        <div class="col-md-2 form-control-sm">Kantor</div>
                        <div class="col-md-6 px-1">
                            <select class="form-control form-control-sm" id="kantor" name="kantor" value="{{ header.KANTOR_ID }}">
                                <option value=""></option>
                                {% for kantor in kodekantor %}
                                <option {% if header.KANTOR_ID == kantor.KANTOR_ID %}selected{% endif %} value="{{ kantor.KANTOR_ID }}">{{ kantor.URAIAN }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">No. BL</label>
                            <input type="text" maxlength="24" class="form-control form-control-sm" name="nobl" id="nobl" value="{{ header.NO_BL }}">
                        </div>                        
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Shipper</label>
                            <select class="form-control form-control-sm" id="shipper" name="shipper" value="{{ header.SHIPPER }}">
                                <option value=""></option>
                                {% for ship in shipper %}
                                <option {% if header.SHIPPER == ship.id_pemasok %}selected{% endif %} value="{{ ship.id_pemasok }}">{{ ship.nama_pemasok }}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Pelabuhan Muat</label>
                            <select class="form-control form-control-sm" id="pelmuat" name="pelmuat" value="{{ header.PEL_MUAT }}">
                                <option value=""></option>
                                {% for pel in pelmuat %}
                                <option {% if header.PEL_MUAT == pel.PELMUAT_ID %}selected{% endif %} value="{{ pel.PELMUAT_ID }}">{{ pel.URAIAN }}</option>
                                {% endfor %}
                            </select>                        </div>
                    </div>                  
                    <div class="form-row pb-1">
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Tgl Berangkat</label>
                            <input type="text" class="datepicker form-control form-control-sm" name="tglberangkat" id="tglberangkat" value="{{ header.TGL_BERANGKAT }}">
                        </div>                        
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Tgl Tiba</label>
                            <input type="text" class="datepicker form-control form-control-sm" value="{{ header.TGL_TIBA }}" name="tgltiba" id="tgltiba">                        
                        </div>
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Kapal</label>
                            <input type="text" class="form-control form-control-sm" value="{{ header.KAPAL }}" name="kapal" id="kapal">
                        </div>                    
                    </div>
                    <div class="form-row mb-1">
                        <div class="col-md-4 form-control-sm">Consignee</div>
                        <div class="col-md-8">
                            <select class="form-control form-control-sm" id="consignee" name="consignee" value="{{ header.CONSIGNEE }}">
                                <option value=""></option>
                                {% for imp in importir %}
                                <option {% if header.CONSIGNEE == imp.IMPORTIR_ID %}selected{% endif %} value="{{ imp.IMPORTIR_ID }}">{{ imp.NAMA }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Jumlah Kontainer</label>
                            <select class="form-control form-control-sm" id="jmlkontainer" name="jmlkontainer" value="{{ header.JUMLAH_KONTAINER }}">
                                <option value=""></option>
                                {% for jml in jumlahkontainer %}
                                <option {% if header.JUMLAH_KONTAINER == jml.JUMLAH %}selected{% endif %} value="{{ jml.JUMLAH }}">{{ jml.JUMLAH }}</option>
                                {% endfor %}
                            </select>                        
                        </div>                        
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Jumlah Kemasan</label>
                            <input type="text" class="number form-control form-control-sm" name="jmlkemasan" value="{{ header.JUMLAH_KEMASAN }}" id="jmlkemasan">                        
                        </div>
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Jenis Kemasan</label>
                            <select class="form-control form-control-sm" id="jeniskemasan" name="jeniskemasan" value="{{ header.JENIS_KEMASAN }}">
                                <option value=""></option>
                                {% for jenis in jeniskemasan %}
                                <option {% if header.JENIS_KEMASAN == jenis.JENISKEMASAN_ID %}selected{% endif %} value="{{ jenis.JENISKEMASAN_ID }}">{{ jenis.URAIAN }}</option>
                                {% endfor %}
                            </select>                        
                        </div>        
                    </div>
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Jenis Barang</label>
                            <select class="form-control form-control-sm" id="jenisbarang" name="jenisbarang" value="{{ header.JENIS_BARANG }}">
                                <option value=""></option>
                                {% for jenis in jenisbarang %}
                                <option {% if header.JENIS_BARANG == jenis.JENISBARANG_ID %}selected{% endif %} value="{{ jenis.JENISBARANG_ID }}">{{ jenis.URAIAN }}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Jumlah SRBRG</label>
                            <input type="text" class="number form-control form-control-sm" name="jmlsrbrg" value="{{ header.JML_SRBRG }}" id="jmlsrbrg">
                        </div>
                    </div>    
                    <div class="form-row mb-1">
                        <div class="col-md-2 form-control-sm">Kode HS</div>
                        <div class="col-md-10"><textarea class="form-control form-control-sm" name="kodehs" id="kodehs">{{ header.KODE_HS }}</textarea></div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Tgl Bongkar</label>
                            <input type="text" class="datepicker form-control form-control-sm" value="{{ header.TGL_BONGKAR }}" name="tglbongkar" id="tglbongkar">
                        </div>
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Tgl Keluar</label>
                            <input type="text" class="datepicker form-control form-control-sm" value="{{ header.TGL_KELUAR }}" name="tglkeluar" id="tglkeluar">
                        </div>
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Hasil Bongkar</label>
                            <select class="form-control form-control-sm" id="hasilbongkar" name="hasilbongkar" value="{{ header.HASIL_BONGKAR }}">
                                <option {% if header.HASIL_BONGKAR == "Y" %}selected{% endif %} value="Y">Sesuai</option>
                                <option {% if header.HASIL_BONGKAR == "T" %}selected{% endif %} value="T">Tidak Sesuai</option>
                            </select>
                        </div>
                    </div>                  
                </div>
                <div class="col-md-6">   
                    <div class="form-row mb-1">
                        <div class="col-md-2 form-control-sm">Catatan</div>
                        <div class="col-md-10"><textarea class="form-control form-control-sm" name="catatan" id="catatan">{{ header.CATATAN }}</textarea></div>
                    </div>                     
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">No. INPL</label>
                            <input type="text" class="form-control form-control-sm" name="noinpl" value="{{ header.NO_INPL }}" id="noinpl">
                        </div>
                        <div class="col-md-3">
                            <label class="form-control-sm p-0 m-0">Dokumen Lain</label>                            
                            <select class="form-control form-control-sm" id="doklain" name="doklain" value="{{ header.DOK_LAIN }}">
                                <option {% if header.DOK_LAIN == "Y" %}selected{% endif %} value="Y">Pakai Form</option>
                                <option {% if header.DOK_LAIN == "T" %}selected{% endif %} value="T">Tanpa Form</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-control-sm p-0 m-0">Jenis Dokumen</label>
                            <select class="form-control form-control-sm" id="jenisdokumen" name="jenisdokumen" value="{{ header.JENIS_DOKUMEN }}">
                                <option value=""></option>
                                {% for jenis in jenisdokumen %}
                                <option {% if header.JENIS_DOKUMEN == jenis.JENISDOKUMEN_ID %}selected{% endif %} value="{{ jenis.JENISDOKUMEN_ID }}">{{ jenis.URAIAN }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col-md-2 form-control-sm">Importir</div>
                        <div class="col-md-10">
                            <select class="form-control form-control-sm" id="importir" name="importir" value="{{ header.IMPORTIR }}">
                                <option value=""></option>
                                {% for imp in importir %}
                                <option {% if header.IMPORTIR == imp.IMPORTIR_ID %}selected{% endif %} value="{{ imp.IMPORTIR_ID }}">{{ imp.NAMA }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2 form-control-sm">Customer</div>
                        <div class="col-md-10">
                            <select class="form-control form-control-sm" id="customer" name="customer" value="{{ header.CUSTOMER }}">
                                <option value=""></option>
                                {% for cust in customer %}
                                <option {% if header.CUSTOMER == cust.id_customer %}selected{% endif %} value="{{ cust.id_customer }}">{{ cust.nama_customer }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3 form-control-sm">
                            <label class="form-control-sm p-0 m-0">Aju</label>
                            <input maxlength="6" type="text" class="form-control form-control-sm" name="aju" value="{{ header.AJU }}" id="aju">
                        </div>
                        <div class="col-md-3 form-control-sm px-1">
                            <label class="form-control-sm p-0 m-0">Nopen</label>
                            <input maxlength="6" type="text" class="form-control form-control-sm" name="nopen" value="{{ header.NOPEN }}" id="nopen">
                        </div>
                        <div class="col-md-3 form-control-sm px-0">
                            <label class="form-control-sm p-0 m-0">Tgl Nopen</label>
                            <input type="text" class="datepicker form-control form-control-sm" name="tglnopen" value="{{ header.TGL_NOPEN }}" id="tglnopen">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3">
                            <label class="form-control-sm p-0 m-0">No. PO</label>
                            <input type="text" class="form-control form-control-sm" name="nopo" value="{{ header.NO_PO }}" id="nopo">
                        </div>                    
                        <div class="col-md-3">
                            <label class="form-control-sm p-0 m-0">Tgl. PO</label>                        
                            <input type="text" class="datepicker form-control form-control-sm" name="tglpo" value="{{ header.TGL_PO }}" id="tglpo">
                        </div>
                    </div>
                    <div class="form-row">                    
                        <div class="col-md-3">
                            <label class="form-control-sm p-0 m-0">No. S/C</label>
                            <input  type="text" class="form-control form-control-sm" name="nosc" value="{{ header.NO_SC }}" id="nosc">
                        </div>
                        <div class="col-md-3">
                            <label class="form-control-sm p-0 m-0">Tgl S/C</label>
                            <input type="text" class="datepicker form-control form-control-sm" name="tglsc" value="{{ header.TGL_SC }}" id="tglsc">
                        </div>
                        <div class="col-md-3">
                            <label class="form-control-sm p-0 m-0">Tgl TT</label>
                            <input type="text" class="datepicker form-control form-control-sm" name="tgltt" value="{{ header.TGL_TT }}" id="tgltt">
                        </div>
                    </div>
                    <input type="hidden" name="deletekontainer">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col primary-color text-white py-2 px-4">
                            Detail Kontainer
                        </div>
                        <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                            <a href="#modalkontainer" data-toggle="modal" class="text-white" id="addkontainer">Tambah Detail</a>
                        </div>                            
                    </div>                    
                    <div class="row">
                        <div class="col mt-3">
                            <table id="gridkontainer" class="table">
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
        </form>
    </div>                        
</div>
<script>
    var detailkontainer = "{{ kontainer|escape('js') }}";
    datadetailkontainer = JSON.parse(detailkontainer);
</script>
{% endblock %}