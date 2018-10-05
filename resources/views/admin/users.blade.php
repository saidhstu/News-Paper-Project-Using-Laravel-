@extends('layouts.app')
@section('content')
	<div class="card-panel">
		<a class="btn btn-floating" href="{{ route('admin') }}"><i class="material-icons">arrow_back</i></a>
		<table class="highlight">
			<thead>
				<tr>
					<td>@lang('admin.users.name')</td>
					<td>@lang('admin.users.role')</td>
					<td>@lang('admin.users.email')</td>
					<td>@lang('admin.users.registerDate')</td>
					<td>@lang('admin.users.actions')</td>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $user)
					<tr>
						<td>{{ $user->name }}</td>
						<td>@lang('admin.users.role' . $user->role->name)</td>
						<td>{{ $user->email }}</td>
						<td>{{ $user->created_at }}</td>
						<td class="center-align"><a href="{{  route('adminUserInfo', $user->id) }}"><i class="material-icons blue-text">info</i></a></td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection