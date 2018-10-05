@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('admin') }}"><i class="material-icons">arrow_back</i></a>
				<br><br>
				<select onchange="window.location='{{ route('adminFakeEditArticle')  }}/' + this.value">
					@isset($theFeed)
						<option value="-1">@lang('admin.fake.editArticle.selectFeed')</option>
					@else
						<option value="-1" selected="selected">@lang('admin.fake.editArticle.selectFeed')</option>
					@endisset
					@foreach($feeds as $feed)
						@isset($theFeed)
							@if ($theFeed == $feed->id)
								<option value="{{ $feed->id }}" selected="selected">{{ $feed->name }}</option>
							@else
								<option value="{{ $feed->id }}">{{ $feed->name }}</option>
							@endif
						@else
							<option value="{{ $feed->id }}">{{ $feed->name }}</option>
						@endisset
					@endforeach
				</select>
				@isset($theFeed)
					<div class="center-align">
						<a class="btn waves-effect waves-light" href="{{ route('adminFakeAddArticle', $theFeed) }}">@lang('admin.fakeAddArticle')</a>
					</div>
					<table class="highlight">
						<thead>
							<tr>
								<td>@lang('admin.fake.editArticle.date')</td>
								<td>@lang('admin.fake.editArticle.title')</td>
								<td>@lang('admin.fake.editArticle.actions')</td>
							</tr>
						</thead>
						<tbody>
							@foreach($articles as $article)
								<tr>
									<td>{{ $article->date_added }}</td>
									<td>{{ $article->title }}</td>
									<td><a href="{{ route('adminFakeEditArticleReal', $article->id) }}"><i class="material-icons blue-text">info</i></a></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@endisset
			</div>
		</div>
	</div>
@endsection