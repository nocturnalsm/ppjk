{% extends 'master.php' %}
{% block formbody %}
<form id="form">
    <input type="hidden" id="input-id" name="input-id">
    <input type="hidden" id="input-action" name="input-action">
    <div class="mb-1">                        
        <label for="orangeForm-name">Nama Bank</label>
        <input type="text" id="input-bank" name="input-bank" class="form-control validate">                        
    </div>
</form>
{% endblock %}