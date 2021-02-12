{% extends "base.html.twig" %}
{% block body %}
<div class="card">
    <div class="card-header">
        {{ judul }}
    </div>
    <div class="card-body">
        <div class="row">            
            <div class="col-lg-7 col-md-12">
                <form id="form" method="get" action="/laporan/generate">
                {% block filter %}
                {% endblock %}
                </form>
            </div>
            <div class="col-lg-5 col-md-12">
                <a id="print" class="btn btn-primary">Cetak</a>&nbsp;
            </div>
        </div>
    </div>
</div>
{% endblock %}