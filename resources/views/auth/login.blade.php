@extends('layouts.app')

@section('body')

<body class="login">

<!-- Modal HTML -->
<div id="myModal">
	<div class="modal-dialog modal-login mx-auto">
		<div class="modal-content">
			<div class="modal-header">
				<div class="avatar">
					<i class="fa fa-user fa-3x text-white"></i>
				</div>
				<h4 class="modal-title">Member Login</h4>
			</div>
			<div class="modal-body">
                <form method="POST" action="{{ route('login') }}">
                        @csrf
					<div class="form-group">
						<input type="text" class="form-control @error('email') is-invalid @enderror" name="name" placeholder="Username" value="{{ old('name') }}" required="required" autofocus>
					</div>
					<div class="form-group">
						<input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required="required">
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-primary btn-lg btn-block" value="Login">
					</div>
				</form>
			</div>
			<div class="modal-footer">
        @if($errors->any())
            @foreach($errors->all() as $err)
                {{ $err }}
            @endforeach
        @else
            Silahkan memasukkan username dan password Anda
        @endif
			</div>
		</div>
	</div>
</div>
</body>
@endsection
