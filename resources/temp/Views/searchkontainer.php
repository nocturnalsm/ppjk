{% extends "base.html.twig" %}
{% block body %}
    <form id="formsearch">
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="card card-sm">
                <div class="card-header">
                    Cari Transaksi Berdasarkan Nomor Kontainer
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" class="form-control" id="term" name="term" placeholder="Masukkan Nomor Kontainer (Harus Lengkap)">
                        <input type="hidden" name="searchtype" value="kontainer">
                        <div class="input-group-append">
                            <button id="btnsearch" class="btn btn-primary m-0 px-3" type="button">
                            <i class="fa fa-search"></i>
                            </button>
                        </div>                
                    </div>
                </div>
            </div>     
        </div>
    </div>
    </form>
{% endblock %}