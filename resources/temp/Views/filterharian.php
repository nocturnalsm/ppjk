{% extends "filter.php" %}
{% block filter %}
    <div class="row mb-1">
        <div class="col-md-2">
            Kantor
        </div>
        <div class="col-md-4">
            <select class="form-control" id="kantor" name="kantor" value="">
                {% for kantor in kodekantor %}
                <option value="{{ kantor.KANTOR_ID }}">{{ kantor.URAIAN }}</option>
                {% endfor %}
            </select>
        </div>
        <div class="col-md-2">
            Gudang
        </div>
        <div class="col-md-3">
            <select class="form-control" id="gudang" name="gudang" value="">
                <option value="">Semua</option>
                {% for gdg in gudang %}
                <option value="{{ gdg.GUDANG_ID }}">{{ gdg.KODE }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <input type="hidden" name="namalaporan" value="harian">
    {% if userlevel < 1 %}
    <div class="row">
        <label class="col-md-2">Customer</label>
        <div class="col-md-3">
            <select class="form-control form-control-sm" id="customer" name="customer">
                <option value="">Semua</option>
                {% for cust in datacustomer %}
                <option value="{{ cust.id_customer }}">{{ cust.nama_customer }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    {% endif %}
    <div class="row">
        <div class="col-md-2">
            Kategori
        </div>
        <div class="col-md-3">
            <select class="form-control form-control-sm" id="kategori1" name="kategori1">
                <option value=""></option>
                {% for kat in datakategori %}
                <option {% if kategori1 == kat %}selected{% endif %} value="{{ kat }}">{{ kat }}</option>
                {% endfor %}
            </select>
        </div>
        <label class="px-sm-3">Periode</label>
        <div class="col-md-5">
            <input autocomplete="off" type="text" id="dari1" name="dari1" value="{{ dari1 }}" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
            &nbsp;&nbsp;sampai&nbsp;&nbsp;
            <input autocomplete="off" type="text" id="sampai1" value="{{ sampai1 }}" name="sampai1" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            Kategori
        </div>
        <div class="col-md-3">
            <select class="form-control form-control-sm" id="kategori2" name="kategori2" value="{{ kategori2 }}">
                <option value=""></option>
                {% for kat in datakategori %}
                <option {% if kategori2 == kat %}selected{% endif %} value="{{ kat }}">{{ kat }}</option>
                {% endfor %}
            </select>
        </div>
        <label class="px-sm-3">Periode</label>
        <div class="col-md-5">
            <input autocomplete="off" type="text" id="dari2" name="dari2" value="{{ dari2 }}" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
            &nbsp;&nbsp;sampai&nbsp;&nbsp;
            <input autocomplete="off" type="text" id="sampai2" value="{{ sampai2 }}" name="sampai2" class="datepicker form-control d-inline form-control-sm" style="width: 120px">
        </div>
    </div>
{% endblock %}