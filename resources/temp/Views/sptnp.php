{% extends "base.html.twig" %}
{% block body %}
    <form id="formsearch">
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="card card-sm">
                <div class="card-header">
                    Perekaman SPTNP
                </div>
                <div class="card-body">
                    <div class="form-row px-2">
                        <label class="col-md-3 col-form-label">Nopen</label>
                        <div class="col-md-3">
                            <input maxlength="6" type="text" class="form-control" name="nopen" id="nopen">
                        </div>   
                    </div>
                    <div class="form-row px-2">
                        <label class="col-md-3 col-form-label">Tgl Nopen</label>
                        <div class="col-md-3">
                            <input autocomplete="off" type="text" class="datepicker form-control" name="tglnopen" id="tglnopen">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="col-md-3 col-form-label"></label>
                        <div class="col-md-9">
                            <button type="button" id="btnsearch" name="btnsearch" class="btn btn-primary">
                                <i class="fa fa-search"></i>&nbsp;&nbsp;Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
    </div>
    </form>
{% endblock %}