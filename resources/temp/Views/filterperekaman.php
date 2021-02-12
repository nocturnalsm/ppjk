{% extends "filter.php" %}
{% block filter %}
    <div class="row mb-1">
        <div class="col-md-4">
            Kantor
        </div>
        <input type="hidden" name="namalaporan" value="{{ laporan }}">
        <div class="col-md-8">
            <select class="form-control" id="kantor" name="kantor" value="">
                {% for kantor in kodekantor %}
                <option value="{{ kantor.KANTOR_ID }}">{{ kantor.URAIAN }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-md-4">
            Periode Perekaman
        </div>
        <div class="col-md-8">
            <input type="text" id="dari" name="dari" value="{{ dari }}" class="datepicker form-control d-inline" style="width: 120px">
            &nbsp;&nbsp;sampai&nbsp;&nbsp;
            <input type="text" id="sampai" value="{{ sampai }}" name="sampai" class="datepicker form-control d-inline" style="width: 120px">
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-md-4">
            Importir            
        </div>
        <div class="col-md-8">
            <select class="form-control" id="importir" name="importir">
                {% for imp in importir %}
                <option value="{{ imp.IMPORTIR_ID }}">{{ imp.NAMA }}</option>
                {% endfor %}
            </select>
        </div>                    
    </div>
{% endblock %}