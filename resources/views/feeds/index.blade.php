@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<ul class="actionList">
					<li><i class="material-icons">arrow_forward</i>&nbsp;<a href="{{ route('feedsList') }}">@lang('feeds.list')</a></li>
					@auth
						<li><i class="material-icons">arrow_forward</i>&nbsp;<a href="{{ route('feedsAdd') }}">@lang('feeds.add')</a>
						<li><i class="material-icons">arrow_forward</i>&nbsp;<a href="{{ route('bizzFeedCreator') }}">@lang('feeds.bizzFeedCreator')</a>
					@endauth
				</ul>
			</div>
		</div>
	</div>
@endsection