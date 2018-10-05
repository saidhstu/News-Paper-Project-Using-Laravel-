@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('admin') }}"><i class="material-icons">arrow_back</i></a>
				<a class="btn btn-floating" href="{{ route('adminPortalAdd') }}"><i class="material-icons">add</i></a>
				<table class="highlight">
					<thead>
						<tr>
							<td>@lang('admin.portal.name')</td>
							<td>@lang('admin.portal.actions')</td>
						</tr>
					</thead>
					<tbody>
						@foreach($portals as $portal)
							<tr>
								<td>{{ $portal->name }}</td>
								<td><a href="{{ route('adminPortalInfo', $portal->id) }}"><i class="material-icons blue-text">info</i></a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection