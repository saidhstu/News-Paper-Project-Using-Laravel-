@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('adminPortal') }}"><i class="material-icons">arrow_back</i></a>
				<a class="btn btn-floating" href="{{ route('adminPortalInfoAddFeed', $portal->id) }}"><i class="material-icons">add</i></a>
				<a class="btn btn-floating red modal-trigger" href="#deleteModal"><i class="material-icons">delete</i></a>
				<div class="row">
					<div class="col s12 m6">
						<h5 class="bizzblue-text">@lang('admin.portal.info.name')</h5>
						<p>{{ $portal->name }}</p>
					</div>
					<div class="col s12 m6">
						<h5 class="bizzblue-text">@lang('admin.portal.info.id')</h5>
						<p>{{ $portal->id }}</p>
					</div>
				</div>
				<div class="center-align">
					<div id="deleteModal" class="modal red darken-3">
						<div class="modal-content white-text">
							<h4>@lang('admin.portal.info.deleteConfirmQuestion')</h4>
						</div>
						<div class="modal-footer red darken-3">
							<a href="" class="white-text modal-action modal-close btn-flat">@lang('admin.portal.info.deleteSubmit')</a>
						</div>
					</div>
				</div>
				<table class="highlight">
					<thead>
						<tr>
							<td>@lang('admin.portal.info.feedName')</td>
							<td>@lang('admin.portal.info.actions')</td>
						</tr>
					</thead>
					<tbody>
						@foreach($portal->feeds as $feed)
							<tr id="feed_{{ $feed->id }}">
								<td>{{ $feed->name }}</td>
								<td><a href="{{ route('adminPortalInfoDeleteFeed', [$portal->id, $feed->id]) }}"><i class="material-icons red-text">delete</i></a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection