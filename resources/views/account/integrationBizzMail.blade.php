@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('accountIntegration') }}"><i class="material-icons">arrow_back</i></a>
				<form method="POST" action="{{ route('accountIntegrationBizzMailSubmit') }}">
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">code</i>
						@if($integration->bizzmail)
							<input type="text" value="{{ $integration->bizzmail }}" name="token" id="token" required="required"/>
						@else
							<input type="text" name="token" id="token" required="required"/>
						@endif
						<label for="token">@lang('account.integrations.BizzMail.token')</label>
					</div>
					<div class="center-align">
						<button type="submit" class="btn">@lang('account.integrations.BizzMail.submit')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection