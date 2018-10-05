@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<ul class="actionList">
					<li><i class="material-icons">arrow_forward</i>&nbsp;<a href="{{ route('adminCategory') }}">@lang('admin.category')</a></li>
					<li><i class="material-icons">arrow_forward</i>&nbsp;<a href="{{ route('adminPortal') }}">@lang('admin.portal')</a></li>
					<li><i class="material-icons">arrow_forward</i>&nbsp;<a href="{{ route('adminUsers') }}">@lang('admin.users')</a></li>
					<li><i class="material-icons">arrow_forward</i>&nbsp;<a href="{{ route('adminFakeAdd') }}">@lang('admin.fakeAdd')</a></li>
					<li><i class="material-icons">arrow_forward</i>&nbsp;<a href="{{ route('adminFakeEditArticle') }}">@lang('admin.fakeEditArticle')</a></li>
				</ul>
			</div>
		</div>
	</div>
@endsection