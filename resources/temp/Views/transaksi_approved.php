{% extends 'base.html.twig' %}
{% block body %}
<div class="row">
    <div class="card col-md-12 p-0">
        <div class="card-header font-weight-bold">
            <div class="row">
                <div class="col-md-4 py-0 pl-4 mt-1">
                    Transaksi{{ notransaksi }}
                </div>
            </div>
        </div>
        <form id="transaksi">
        <div class="card-body p-4">
            <div class="row pb-4">
                <div class="col-md-6">
                    <div class="row mb-1">
                        <div class="col-md-4">Kantor</div>
                        <div class="col-md-8">
                            {{ header.NAMAKANTOR }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Tgl Perekaman</div>
                        <div class="col-md-8">{{ header.TGL_PEREKAMAN|date("d-m-Y") }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">No. BL</div>
                        <div class="col-md-8">{{ header.NO_BL }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Jumlah Kemasan</div>
                        <div class="col-md-8">{{ header.JUMLAH_KEMASAN }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Jenis Kemasan</div>
                        <div class="col-md-8">
                            {{ header.NAMAKEMASAN }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Jumlah Kontainer</div>            
                        <div class="col-md-8">
                            {{ header.JUMLAH_KONTAINER }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Importir</div>
                        <div class="col-md-8">
                            {{ header.NAMAIMPORTIR }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Kloter</div>                
                        <div class="col-md-8">
                            {{ header.KLOTER }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Tgl Laporan</div>
                        <div class="col-md-8">{{ header.TGL_LAPORAN|date("d-m-Y") }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">                    
                    <div class="row mb-1">
                        <div class="col-md-4">Importir BC_28</div>
                        <div class="col-md-8">
                            {{ header.NAMAIMPORTIR_BC_28 }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Jenis Dokumen</div>
                        <div class="col-md-8">
                            {{ header.NAMADOKUMEN }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Nopen BC_28</div>
                        <div class="col-md-8">
                            {{ header.NOPEN_BC_28 }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Tgl BC_28</div>
                        <div class="col-md-8">{{ header.TGL_BC_16|date("d-m-Y") }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Tgl Transfer</div>
                        <div class="col-md-8">{{ header.TGL_TRANSFER|date("d-m-Y") }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Nilai Transfer</div>
                        <div class="col-md-8">{{ header.NILAI_TRANSFER|number_format(0,".",",") }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Catatan</div>
                        <div class="col-md-8">{{ header.CATATAN }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-4">Status</div>
                        <div class="col-md-8" id="status">   
                            {% if header.TGL_REALISASI is empty %} 
                                Belum Terealisasi
                            {% else %}
                                Sudah Terealisasi Tgl {{ header.TGL_REALISASI }}
                            {% endif %}
                        </div>
                    </div>                
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col primary-color text-white py-2 px-4">
                            Detail Kontainer
                        </div>
                        <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
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
                <div class="col-md-6">
                    <div class="row">
                    <div class="col primary-color text-white py-2 px-4">
                            Detail Biaya
                        </div>
                        <div class="col primary-color text-white text-right p-2" style="text-decoration:underline">
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col mt-3">
                            <table id="gridbiaya" class="table">
                                <thead>
                                    <tr>
                                        <th>Jenis Biaya</th>
                                        <th>Nominal</th>
                                        <th>Keterangan</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <div class="card-footer p-4">
            <div class="row">
                <div class="col">
                    <p class="font-weight-bold">Approved Tgl {{ header.TGL_APPROVED|date("d-m-Y") }}</p>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col-md-8 text-right">
                            Total Biaya
                        </div>
                        <div class="col-md-4">
                            <input class="text-right form-control" type="text" readonly id="totalbiaya" value="{{ totalbiaya }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 text-right">
                            Balance
                        </div>
                        <div class="col-md-4">
                            <input class="text-right form-control" type="text" readonly id="balance" value="{{ balance }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                        
</div>
<script>
    var detailkontainer = "{{ kontainer|escape('js') }}";
    datadetailkontainer = JSON.parse(detailkontainer);
    var detailbiaya = "{{ biaya|escape('js') }}";
    datadetailbiaya = JSON.parse(detailbiaya);
</script>
{% endblock %}