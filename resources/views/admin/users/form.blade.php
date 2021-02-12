<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group row">
    <input type="hidden" name="user_id" id="user_id" value="{{ $data->id }}">
    <input type="hidden" name="action" value="{{ $action }}">
    <label class="col-form-label col-md-3">Username</label>
    <div class="col-md-9">
        <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}">
        <div class="invalid-feedback">
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-form-label col-md-3">Email</label>
    <div class="col-md-9">
        <input type="email" class="form-control" name="email" id="email" value="{{ $data->email }}">
        <div class="invalid-feedback">
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-form-label col-md-3">Password</label>
    <div class="col-md-9">
        <input type="password" class="form-control" name="password" id="password" value="">
        <div class="invalid-feedback">
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-form-label col-md-3">Konfirmasi Password</label>
    <div class="col-md-9">
        <input type="password" class="form-control" name="confirm" id="confirm" value="">
        <div class="invalid-feedback">
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-form-label col-md-3">Group User</label>
    <div class="col-md-9">
        <select name="roles[]" id="roles" multiple="multiple" class="form-control">
            <option value=""></option>
            @foreach ($roles as $role)
            <option value="{{ $role->name }}"{{ in_array($role->name, $userRoles) ? ' selected' : '' }}>
                {{ $role->name }}
            </option>
            @endforeach
        </select>
        <div class="invalid-feedback">
        </div>
    </div>
</div>

<script>
    @if($action == 'add')
    $("#form").attr("action", "{{ route('users.store') }}");
    @elseif ($action == 'edit')
    $("#form").attr("action", "{{ route('users.update', $data->id) }}");
    @endif
    $("#roles").select2({
      width: '100%'
    });
</script>
