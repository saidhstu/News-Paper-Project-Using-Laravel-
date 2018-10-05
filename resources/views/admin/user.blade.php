@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<form method="POST" action="{{ route('adminUserRoleChange', $user->id) }}">
					{{ csrf_field() }}
					<a class="btn btn-floating" href="{{ route('adminUsers') }}"><i class="material-icons">arrow_back</i></a>
					<button class="btn btn-floating" type="submit"><i class="material-icons">save</i></button>
					<a class="btn btn-floating red" href="{{ route('adminUserDelete', $user->id) }}"><i class="material-icons">delete</i></a>
					<h5 class="bizzblue-text">@lang('admin.users.info.name')</h5>
					<p>{{ $user->name }}</p>
					<h5 class="bizzblue-text">@lang('admin.users.info.email')</h5>
					<p>{{ $user->email }}</p>
					<h5 class="bizzblue-text">@lang('admin.users.info.registerDate')</h5>
					<p>{{ $user->created_at }}</p>
					<h5 class="bizzblue-text">@lang('admin.users.info.role')</h5>
					<div class="input-field">
						<select name="role" id="role">
							@foreach($roles as $role)
								@if ($role->name != 'guest')
									@if ($user->role_id == $role->id)
										<option value="{{ $role->id }}" selected="selected">@lang('admin.users.role' . $role->name)</option>
									@else
										<option value="{{ $role->id }}">@lang('admin.users.role' . $role->name)</option>
									@endif
								@endif
							@endforeach
						</select>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection