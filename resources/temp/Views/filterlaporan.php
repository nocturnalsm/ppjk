{% extends "filter.php" %}
{% block filter %}
    <div class="row mb-1">
        <div class="col-md-2">
            Kantor
        </div>
        <div class="col-md-4">
            <select class="form-control form-control-sm" id="kantor" name="kantor" value="">
                {% for kantor in kodekantor %}
                <option value="{{ kantor.KANTOR_ID }}">{{ kantor.URAIAN }}</option>
                {% endfor %}
            </select>
        </div>
        <div class="col-md-2">
            Gudang
        </div>
        <div class="col-md-3">
            <select class="form-control form-control-sm" id="gudang" name="gudang" value="">
                <option value="">Semua</option>
                {% for gdg in gudang %}
                <option value="{{ gdg.GUDANG_ID }}">{{ gdg.KODE }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="row mb-1">
        <label class="col-md-2">Customer</label>
        <div class="col-md-4">
            <select class="form-control form-control-sm" id="customer" name="customer">
                <option value="">Semua</option>
                {% for cust in customer %}
                <option value="{{ cust.id_customer }}">{{ cust.nama_customer }}</option>
                {% endfor %}
            </select>
        </div>
        <label class="col-md-2">Importir</label>
        <div class="col-md-4">
            <select class="form-control form-control-sm" id="importir" name="importir">
                <option value="">Semua</option>
                {% for imp in importir %}
                <option value="{{ imp.IMPORTIR_ID }}">{{ imp.NAMA }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-md-2">
            Kategori
        </div>
        <div class="col-md-4">
            <select class="form-control form-control-sm" id="kategori" name="kategori" value="">
                {% for kat in kategori %}
                <option value="{{ kat }}">{{ kat }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-md-2">
            Periode
        </div>
        <div class="col-md-8">
            <input type="text" id="dari" name="dari" value="{{ dari }}" class="datepicker form-control form-control-sm d-inline" style="width: 120px">
            &nbsp;&nbsp;sampai&nbsp;&nbsp;
            <input type="text" id="sampai" value="{{ sampai }}" name="sampai" class="datepicker form-control form-control-sm d-inline" style="width: 120px">
        </div>
        <input type="hidden" name="namalaporan" value="bongkar">
    </div>
{% endblock %}