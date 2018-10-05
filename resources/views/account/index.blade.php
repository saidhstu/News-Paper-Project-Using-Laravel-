@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<h5 class="bizzblue-text">@lang('account.name')</h5>
				<p>{{ Auth::user()->name }}</p>
				<h5 class="bizzblue-text">@lang('account.email')</h5>
				<p>{{ Auth::user()->email }}</p>
				{{-- TODO TEMPORARELY DISABLED ACCOUNT INTEGRATIONS --}}
				{{--<div class="center">--}}
				{{--<a class="btn" href="{{ route('accountIntegration') }}">@lang('account.integrations')</a>--}}
				{{--</div>--}}
				<br>
				<div class="center">
					<a class="btn" href="{{ route('accountModify') }}">@lang('account.modify')</a>
					<a class="btn" href="{{ route('accountModifyPassword') }}">@lang('account.modifyPassword')</a>
				</div>
			</div>
		</div>
	</div>
@endsection