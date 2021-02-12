{% extends 'master.php' %}
{% block formbody %}
<form id="form">                    
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="mb-1">                        
        <label for="input-kode">Kode</label>
        <input type="text" maxlength="10" id="input-kode" name="input-kode" class="form-control">                        
    </div>
    <div class="mb-1">                        
        <label for="input-uraian">Jenis Barang</label>
        <input type="text" maxlength="99" id="input-uraian" name="input-uraian" class="form-control">                        
    </div>
</form>
{% endblock %}