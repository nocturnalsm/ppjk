{% extends 'master.php' %}
{% block formbody %}
<form id="form">
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="mb-1">                        
        <label for="input-npwp">NPWP</label>
        <input type="text" id="input-npwp" name="input-npwp" class="form-control validate">                        
    </div>
    <div class="mb-1">                        
        <label for="input-nama">Nama</label>
        <input type="text" id="input-nama" name="input-nama" class="form-control validate">                        
    </div>
    <div class="mb-1">                        
        <label for="input-alamat">Alamat</label>
        <input type="text" id="input-alamat" name="input-alamat" class="form-control validate">                        
    </div>
    <div class="mb-1">                        
        <label for="input-telepon">Telepon</label>
        <input type="text" id="input-telepon" name="input-telepon" class="form-control validate">                        
    </div>
    <div class="mb-1">                        
        <label for="input-email">Email</label>
        <input type="email" id="input-email" name="input-email" class="form-control validate">                        
    </div>
</form>
{% endblock %}