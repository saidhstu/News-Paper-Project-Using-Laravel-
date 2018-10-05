@extends('layouts.app')
@section('content')
	<div class="card-panel">
		<a class="btn btn-floating" href="{{ route('feeds') }}"><i class="material-icons">arrow_back</i></a>
		<table class="highlight">
			<thead>
				<tr>
					<td>@lang('feeds.list.enabled')</td>
					@if(\App\Utils\Utils::isUserAdmin())
						<td>@lang('feeds.list.owner')</td>
					@endif
					<td>@lang('feeds.list.name')</td>
					<td>@lang('feeds.list.public')</td>
					<td>@lang('feeds.list.lastUpdate')</td>
					<td>@lang('feeds.list.category')</td>
					<td>@lang('feeds.list.actions')</td>
				</tr>
			</thead>
			<tbody>
				@foreach($feeds as $feed)
					<tr>
						@if (!$feed->disabled)
							<td><i class="material-icons green-text">check</i></td>
						@else
							<td><i class="material-icons red-text">clear</i></td>
						@endif
						@if(\App\Utils\Utils::isUserAdmin())
							<td>{{ $feed->user->name }}</td>
						@endif
						<td>{{ $feed->name }}</td>
						@if ($feed->public)
							<td><i class="material-icons green-text">check</i></td>
						@else
							<td><i class="material-icons red-text">clear</i></td>
						@endif
						<td>{{ $feed->pubDate }}</td>
						<td>@lang('category.' . $feed->category->name)</td>
						<td><a href="{{ route('feedsInfo', $feed->id) }}"><i class="material-icons blue-text">info</i></a></td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection