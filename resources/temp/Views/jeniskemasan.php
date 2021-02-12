{% extends 'master.php' %}
{% block formbody %}
<form id="form">
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="mb-1">                        
        <label for="orangeForm-name">Kode</label>
        <input type="text" maxlength="2" id="input-kode" name="input-kode" class="form-control validate">                        
    </div>
    <div class="mb-1">                        
        <label for="orangeForm-email">Nama Kemasan</label>
        <input type="text" maxlength="99" id="input-uraian" name="input-uraian" class="form-control validate">                        
    </div>
</form>
{% endblock %}