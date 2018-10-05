@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('feedsList') }}"><i class="material-icons">arrow_back</i></a>
				@auth
					@if($feed->user_id === \Illuminate\Support\Facades\Auth::id() || \App\Utils\Utils::isUserAdmin())
						@if ($feed->public)
							<a class="btn btn-floating" href="{{ route('feedInfoPublic', $feed->id) }}"><i class="material-icons">lock</i></a>
						@else
							<a class="btn btn-floating" href="{{ route('feedInfoPublic', $feed->id) }}"><i class="material-icons">lock_open</i></a>
						@endif
						@if ($feed->scan)
							<a class="btn btn-floating" href="{{ route('feedsModify', $feed->id) }}"><i class="material-icons">edit</i></a>
						@else
							<a class="btn btn-floating" href="{{ route('adminFakeEdit', $feed->id) }}"><i class="material-icons">edit</i></a>
						@endif
						<a class="btn btn-floating red modal-trigger" href="#deleteModal"><i class="material-icons">delete</i></a>
						<div id="deleteModal" class="modal red darken-3">
							<div class="modal-content white-text">
								<h4>@lang('feeds.info.deleteConfirmQuestion')</h4>
							</div>
							<div class="modal-footer red darken-3">
								<a href="{{ route('feedsDelete', $feed->id) }}" class="white-text modal-action modal-close btn-flat">@lang('feeds.info.deleteSubmit')</a>
							</div>
						</div>
					@endif
					<a class="btn btn-floating orange" href="{{ route('feedsReport', $feed->id) }}"><i class="material-icons">report_problem</i></a>
				@endauth
				<div class="center-align">
					<h3 class="bizzblue-text">{{ $feed->name }}</h3>
				</div>
				<table class="highlight">
					<tbody>
						<tr>
							<td>@lang('feeds.info.description')</td>
							<td>{{ $feed->description }}</td>
						</tr>
						@if (\App\Utils\Utils::isUserAdmin())
							<tr>
								<td>@lang('feeds.info.owner')</td>
								<td>{{ $feed->user->name }}</td>
							</tr>
						@endif
						<tr>
							<td>@lang('feeds.info.url')</td>
							<td>{{ $feed->url }}</td>
						</tr>
						@if ($feed->scan)
							<tr>
								<td>@lang('feeds.info.language')</td>
								<td>{{ $feed->language }}</td>
							</tr>
							<tr>
								<td>@lang('feeds.info.rss')</td>
								<td>{{$feed->rss }}</td>
							</tr>
							<tr>
								<td>@lang('feeds.info.version')</td>
								<td>{{ $feed->version }}</td>
							</tr>
						@endif
						<tr>
							<td>@lang('feeds.info.copyright')</td>
							<td>{{ $feed->copyright }}</td>
						</tr>
						<tr>
							<td>@lang('feeds.info.pubDate')</td>
							<td>{{ $feed->pubDate }}</td>
						</tr>
						<tr>
							<td>@lang('feeds.info.addedDate')</td>
							<td>{{ $feed->date_added }}</td>
						</tr>
						<tr>
							<td>@lang('feeds.info.updatedDate')</td>
							<td>{{ $feed->date_updated }}</td>
						</tr>
						<tr>
							<td>@lang('feeds.info.articles')</td>
							<td>{{ $articles }}</td>
						</tr>
						{{-- TODO add reported state --}}
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection