{% extends 'master.php' %}
{% block formbody %}
<form id="form">
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="mb-1">                        
        <label for="orangeForm-name">Bank</label>
        <select class="form-control validate" id="input-bank" name="input-bank">
            {% for bank in databank %}
            <option value="{{ bank.BANK_ID }}">{{ bank.BANK }}</option>
            {% endfor %}
        </select>                        
    </div>
    <div class="mb-1">                        
        <label for="orangeForm-name">No. Rekening</label>
        <input type="text" id="input-norekening" name="input-norekening" class="form-control validate">                        
    </div>
    <div class="mb-1">                        
        <label for="orangeForm-email">Nama Rekening</label>
        <input type="text" id="input-nama" name="input-nama" class="form-control validate">                        
    </div>
</form>
{% endblock %}