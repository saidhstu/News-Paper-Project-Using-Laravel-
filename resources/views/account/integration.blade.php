@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('account') }}"><i class="material-icons">arrow_back</i></a>
				<h5 class="bizzblue-text">@lang('account.integrations.BizzMail')</h5>
				<p style="font-weight: bold">{{ $integration->bizzmail ? $integration->bizzmail : '' }}</p>
				@if(!$BizzMailStatus)
					<p class="red-text">@lang('account.integrations.BizzMailInvalid')</p>
				@else
					<p class="green-text">@lang('account.integrations.BizzMailValid')</p>
				@endif
				<a href="{{ route('accountIntegrationBizzMail') }}">@lang('account.integrations.BizzMailEdit')</a>
			</div>
		</div>
	</div>
@endsection