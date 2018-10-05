@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col s12 m8 offset-m2 xl6 offset-xl3">
			<div class="card-panel">
				<a class="btn btn-floating" href="{{ route('feeds') }}"><i class="material-icons">arrow_back</i></a>
				@if($error === 1)
					<div class="card-panel red white-text smallCard">
						<h5>@lang('feeds.duplicatePublic')</h5>
						<p>{{ $address }}</p>
					</div>
					<br>
					<br>
				@elseif($error === 2)
					<div class="card-panel red white-text smallCard">
						<h5>@lang('feeds.invalidAddress')</h5>
						<p>{{ $address }}</p>
					</div>
					<br>
					<br>
				@endif
				<form method="POST" action="{{ route('feedsAddSubmit') }}">
					{{ csrf_field() }}
					<div class="input-field">
						<i class="material-icons prefix">rss_feed</i>
						<input type="text" id="address" name="address" value="{{ old('address') }}" required="required" autofocus="autofocus"/>
						<label for="address">@lang('feeds.address')</label>
						@if($errors->has('address'))
							<span class="helper-text red-text">{{ $errors->first('address') }}</span>
						@endif
					</div>
					<div class="input-field center-align">
						<button type="submit" class="btn">@lang('feeds.addSubmit')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection